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
     * @return ITemplateEngine
     *
     * @throws TemplateEngineException
     */
    public static function createFromEngineKeyAndTemplateDir(string $key, string $templateDir)
    {
        switch ($key)
        {
            case Engine::ENGINE_TWIG:
                $engine = new TwigITemplateEngine($templateDir);
                break;
            default:
                throw new TemplateEngineException('No valid templating engine found for key ' . $key);
        }

        return $engine;
    }

}