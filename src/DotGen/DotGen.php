<?php
namespace DotGen;

use DotGen\Config\ResourceInterface;
use DotGen\Generator\Generator;
use DotGen\File\GuessesFileTypeTrait;
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
        
        $this->generator = new Generator($resource);
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