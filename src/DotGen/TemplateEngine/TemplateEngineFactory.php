<?php
namespace DotGen\TemplateEngine;

/**
 * Class TemplatingEngineFactory
 * 
 * @package cedrichaase\DotGen\TemplatingEngine
 */
class TemplateEngineFactory
{
    /**
     * Creates a @see TemplatingEngineInterface by given Engine key
     *
     * @param string $key
     * @param string $templateDir
     * @return TemplateEngineInterface
     *
     * @throws TemplateEngineException
     */
    public static function createFromEngineKeyAndTemplateDir(string $key, string $templateDir)
    {
        switch ($key)
        {
            case Engine::ENGINE_TWIG:
                $engine = new TwigTemplateEngine($templateDir);
                break;
            default:
                throw new TemplateEngineException('No valid templating engine found for key ' . $key);
        }

        return $engine;
    }

}