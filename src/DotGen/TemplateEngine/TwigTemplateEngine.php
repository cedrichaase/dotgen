<?php
namespace DotGen\TemplateEngine;

use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_LoaderInterface;

/**
 * Class TwigTemplateEngine
 *
 * @package cedrichaase\DotGen\TemplateEngine
 */
class TwigITemplateEngine implements ITemplateEngine
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

    /**
     * TwigTemplatingEngine constructor.
     * 
     * @param string $templateDir
     */
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
        return $this->env->render($name . '.' . self::FILE_EXTENSION, $context);
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