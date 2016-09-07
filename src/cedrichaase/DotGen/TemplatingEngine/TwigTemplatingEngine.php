<?php
namespace cedrichaase\DotGen\TemplatingEngine;

use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_LoaderInterface;

class TwigTemplatingEngine
{
    const BASE_DIR = __DIR__ . '/../../../..';

    const TWIG_BASE_DIR = self::BASE_DIR . '/twig';

    const TEMPLATE_DIR = self::TWIG_BASE_DIR . '/templates';

    const CACHE_DIR = self::TWIG_BASE_DIR . '/cache';

    /**
     * @var Twig_LoaderInterface
     */
    private $loader;

    /**
     * @var Twig_Environment
     */
    private $env;

    /**
     * @param $name
     * @param $context
     *
     * @return string
     */
    public function render($name, $context)
    {
        return $this->env()->render($name, $context);
    }

    /**
     * @return Twig_Environment
     */
    private function env()
    {
        if(!$this->loader)
        {
            $this->loader = new Twig_Loader_Filesystem(self::TEMPLATE_DIR);
        }

        if(!$this->env)
        {
            $this->env = new Twig_Environment($this->loader, [
                'cache' => self::CACHE_DIR,
            ]);

            $this->env->setCache(false);
        }

        return $this->env;
    }
}