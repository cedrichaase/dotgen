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
     * @param $key
     * @return TemplatingEngineInterface
     *
     * @throws TemplatingEngineException
     */
    public static function createFromEngineKey($key)
    {
        switch ($key)
        {
            case Engine::ENGINE_TWIG:
                $engine = new TwigTemplatingEngine();
                break;
            default:
                throw new TemplatingEngineException('No valid templating engine found for key ' . $key);
        }

        return $engine;
    }

}