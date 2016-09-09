<?php
namespace cedrichaase\DotGen\Console\Command;

use cedrichaase\DotGen\DotGen;
use Monolog\Logger;
use Symfony\Bridge\Monolog\Handler\ConsoleHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RenderCommand extends Command
{
    /**
     * Command name
     */
    const NAME = 'render';

    /**
     * config file argument
     */
    const ARG_CONFIG_INI = 'ini';

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
            self::ARG_CONFIG_INI,
            InputArgument::REQUIRED,
            'path to ini source file'
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
        $path = $input->getArgument(self::ARG_CONFIG_INI);
        if(!$path || !file_exists($path))
        {
            $output->writeln('No valid source file specified');
        }

        $dotgen = new DotGen($path);

        $verbose  = $input->getOption(self::OPT_VERBOSE);
        if($verbose)
        {
            $logger = new Logger(self::NAME);
            $logger->pushHandler(new ConsoleHandler($output));
            $dotgen->setLogger($logger);
        }

        $dotgen->generate();
    }
}