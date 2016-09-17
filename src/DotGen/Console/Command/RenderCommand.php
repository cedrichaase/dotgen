<?php
namespace DotGen\Console\Command;

use DotGen\Config\Resource\Converter\ArrayToResourceConverter;
use DotGen\Config\Resource\Converter\BaseDirTemplateMapper;
use DotGen\Config\Resource\InheritanceHandler\InheritanceHandler;
use DotGen\Config\Resource\Parser\ParserManager;
use DotGen\Config\Resource\Repository\File\FileResourceRepository;
use DotGen\File\HandlesFilesystemTrait;
use DotGen\Generator\Generator;
use DotGen\TemplateEngine\TemplateEngineManager;
use Monolog\Logger;
use Psr\Log\NullLogger;
use Symfony\Bridge\Monolog\Handler\ConsoleHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RenderCommand
 *
 * @codeCoverageIgnore
 *
 * @package DotGen\Console\Command
 */
class RenderCommand extends Command
{
    use HandlesFilesystemTrait;

    /**
     * Command name
     */
    const NAME = 'render';

    /**
     * config file argument
     */
    const ARG_CONFIG_FILE = 'config';

    /**
     * template dir option
     */
    const OPT_TEMPLATE_PATH = 'template-dir';

    const OPT_TEMPLATE_PATH_SHORT = 't';

    /**
     * output path option
     */
    const OPT_OUTPUT_PATH = 'output-path';

    const OPT_OUTPUT_PATH_SHORT = 'o';

    /**
     * resource repository include option
     */
    const OPT_INCLUDE = 'include';

    const OPT_INCLUDE_SHORT = 'I';

    /**
     * verbose option
     */
    const OPT_VERBOSE = 'verbose';

    protected function configure()
    {
        $this->setName('render')
            ->setDescription('Render templates')
            ->setHelp('This command renders all templates configured in the given resource');

        $this->addArgument(
            self::ARG_CONFIG_FILE,
            InputArgument::REQUIRED,
            'path to config file'
        );

        $this->addOption(
            self::OPT_TEMPLATE_PATH,
            self::OPT_TEMPLATE_PATH_SHORT,
            InputOption::VALUE_OPTIONAL,
            'path to templates (defaults to config file path)'
        );

        $this->addOption(
            self::OPT_OUTPUT_PATH,
            self::OPT_OUTPUT_PATH_SHORT,
            InputOption::VALUE_OPTIONAL,
            'output path for rendered files (defaults to config file path)'
        );

        $this->addOption(
            self::OPT_INCLUDE,
            self::OPT_INCLUDE_SHORT,
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            "Path to a resource include.
            Right now, these can only be paths to directories containing config files prefixed with \"file:\"
            Config file path is always included."
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startExecute = microtime(true);

        $path = $input->getArgument(self::ARG_CONFIG_FILE);
        if(!$path || !file_exists($path))
        {
            $output->writeln('No valid source file specified');
        }

        $templateDir = $input->getOption(self::OPT_TEMPLATE_PATH);
        if(!$templateDir)
        {
            $templateDir = realpath(dirname($path));
        }

        $outputDir = $input->getOption(self::OPT_OUTPUT_PATH);
        if(!$outputDir)
        {
            $outputDir = realpath(dirname($path));
        }

        // process include option
        $includes = $input->getOption(self::OPT_INCLUDE);
        $includes[] = realpath(dirname($path));
        // remove file: prefix from paths
        $includes = array_map(function($element) {
            if(substr($element, 0, strlen('file:')) === 'file:')
            {
                $element = substr($element, strlen('file:'));
            }

            return realpath($element);
        }, $includes);

        $verbose  = $input->getOption(self::OPT_VERBOSE);
        $logger = new NullLogger();
        if($verbose)
        {
            $logger = new Logger(self::NAME);
            $logger->pushHandler(new ConsoleHandler($output));
        }

        $config = file_get_contents($path);
        $parser = new ParserManager();
        $parsed = $parser->parse($config);

        // convert parsed array to resource
        $converter = new ArrayToResourceConverter();
        $converter->setLogger($logger);
        $resource = $converter->convert($parsed);


        $startInheritance = microtime(true);
        // handle inheritance
        $handler = new InheritanceHandler();
        foreach($includes as $include)
        {
            $repo = new FileResourceRepository($include);
            $handler->registerRepository($repo);
        }
        $resource = $handler->extend($resource);

        $logger->info('Done handling inheritance', [
            'time' => microtime(true) - $startInheritance,
        ]);

        // begin render process
        $engine = new TemplateEngineManager($templateDir);

        $generator = new Generator($resource, $engine);
        $generator->setLogger($logger);

        $renderedFiles = $generator->render();

        $mapper = new BaseDirTemplateMapper($outputDir);
        foreach($renderedFiles as $renderedFile)
        {
            $renderedFilePath = $mapper->map($renderedFile->getTemplateName());
            file_put_contents($renderedFilePath, $renderedFile->getContents());
        }

        $logger->info('Finished execution', [
            'time' => microtime(true) - $startExecute,
        ]);

        return;
    }
}