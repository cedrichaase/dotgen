<?php
namespace cedrichaase\DotGen;

use cedrichaase\DotGen\ConfigLoader\ConfigLoaderFactory;
use cedrichaase\DotGen\DotfileGenerator\DotfileGenerator;
use cedrichaase\DotGen\FileType\GuessesFileTypeTrait;
use cedrichaase\DotGen\TemplatingEngine\TemplatingEngineFactory;

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
     * DotGen constructor.
     *
     * @param string $resource
     */
    public function __construct($resource)
    {
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
}