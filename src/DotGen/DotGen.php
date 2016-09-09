<?php
namespace DotGen;

use DotGen\ConfigLoader\ConfigLoaderFactory;
use DotGen\DotfileGenerator\DotfileGenerator;
use DotGen\File\GuessesFileTypeTrait;
use DotGen\TemplatingEngine\TemplatingEngineFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class DotGen
 * @package cedrichaase\DotGen
 */
class DotGen
{
    use GuessesFileTypeTrait;

    /**
     * @var DotfileGenerator
     */
    private $generator;

    /**
     * @var LoggerInterface
     */
    private $log;

    /**
     * DotGen constructor.
     *
     * @param string $resource
     */
    public function __construct($resource)
    {
        $this->log = new NullLogger();

        $loader = ConfigLoaderFactory::createFromFile($resource);
        
        $engineKey = $loader->getTemplatingEngine();
        $templateDir = $loader->getInputPath();
        $engine = TemplatingEngineFactory::createFromEngineKeyAndTemplateDir($engineKey, $templateDir);
        
        $this->generator = new DotfileGenerator($loader, $engine);
    }

    /**
     * Generates dotfiles
     */
    public function generate()
    {
        $this->generator->renderDotfiles();
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->log = $logger;
        $this->generator->setLogger($logger);
    }
}