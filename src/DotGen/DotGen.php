<?php
namespace DotGen;

use DotGen\Config\IResource;
use DotGen\Config\Validator\ResourceValidator;
use DotGen\Generator\Generator;
use DotGen\File\GuessesFileTypeTrait;
use DotGen\Generator\RenderedFile;
use DotGen\TemplateEngine\TemplateEngineFactory;
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
     * @param IResource $resource
     */
    public function __construct(IResource $resource)
    {
        $this->log = new NullLogger();

        ResourceValidator::validate($resource);

        $engineKey = $resource->getEngine();
        $templateDir = $resource->getInputPath();
        $engine = TemplateEngineFactory::createFromEngineKeyAndTemplateDir($engineKey, $templateDir);
        
        $this->generator = new Generator($resource, $engine);
    }

    /**
     * Renders all text files
     *
     * @return RenderedFile[]
     */
    public function render(): array
    {
        return $this->generator->render();
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