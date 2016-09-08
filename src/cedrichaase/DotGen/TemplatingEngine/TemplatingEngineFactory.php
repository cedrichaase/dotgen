<?php
namespace cedrichaase\DotGen\TemplatingEngine;

/**
 * Class TemplatingEngineFactory
 * 
 * @package cedrichaase\DotGen\TemplatingEngine
 */
class TemplatingEngineFactory
{
    /**
     * Creates a @see TemplatingEngineInterface by given Engine key
     *
     * @param string $key
     * @param string $templateDir
     * @return TemplatingEngineInterface
     *
     * @throws TemplatingEngineException
     */
    public static function createFromEngineKeyAndTemplateDir(string $key, string $templateDir)
    {
        switch ($key)
        {
            case Engine::ENGINE_TWIG:
                $engine = new TwigTemplatingEngine($templateDir);
                break;
            default:
                throw new TemplatingEngineException('No valid templating engine found for key ' . $key);
        }

        return $engine;
    }

}