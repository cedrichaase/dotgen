<?php
namespace DotGen\TemplatingEngine;

use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_LoaderInterface;

/**
 * Class TwigTemplatingEngine
 *
 * @package cedrichaase\DotGen\TemplatingEngine
 */
class TwigTemplatingEngine implements TemplatingEngineInterface
{
    /**
     * File extension for twig templates
     */
    const FILE_EXTENSION = 'twig';

    /**
     * @var Twig_LoaderInterface
     */
    private $loader;

    /**
     * @var Twig_Environment
     */
    private $env;

    /**
     * Base path for templates
     * 
     * @var string
     */
    private $templateDir;

    public function __construct(string $templateDir)
    {
        $this->templateDir = $templateDir;
        $this->loader = new Twig_Loader_Filesystem($this->templateDir);
        $this->env = new Twig_Environment($this->loader, [
            'cache' => false,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function render(string $name, array $context): string
    {
        return $this->env->render($name, $context);
    }

    /**
     * Returns the file extension for files that
     * are written for this templating engine
     *
     * @return string
     */
    public function getFileExtension(): string
    {
        return self::FILE_EXTENSION;
    }
}