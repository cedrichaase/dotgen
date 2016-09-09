<?php
namespace DotGen;

use DotGen\ConfigLoader\ConfigLoaderFactory;
use DotGen\ConfigLoader\Resource\ResourceInterface;
use DotGen\Generator\Generator;
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
     * @var Generator
     */
    private $generator;

    /**
     * @var LoggerInterface
     */
    private $log;

    /**
     * DotGen constructor.
     *
     * @param ResourceInterface $resource
     */
    public function __construct(ResourceInterface $resource)
    {
        $this->log = new NullLogger();

        $loader = ConfigLoaderFactory::createFromResource($resource);
        
        $engineKey = $loader->getTemplatingEngine();
        $templateDir = $loader->getInputPath();
        $engine = TemplatingEngineFactory::createFromEngineKeyAndTemplateDir($engineKey, $templateDir);
        
        $this->generator = new Generator($loader, $engine);
    }

    /**
     * Renders all text files
     */
    public function render()
    {
        $this->generator->render();
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