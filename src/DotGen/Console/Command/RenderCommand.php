<?php
namespace DotGen\Console\Command;

use DotGen\Config\Resource\Converter\ArrayToResourceConverter;
use DotGen\Config\Resource\Converter\BaseDirTemplateMapper;
use DotGen\Config\Resource\Parser\ParserManager;
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
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
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

        $converter = new ArrayToResourceConverter();
        $converter->setLogger($logger);
        $resource = $converter->convert($parsed);

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

        return;
    }
}