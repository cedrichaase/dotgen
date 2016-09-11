<?php
namespace DotGen\TemplateEngine;

/**
 * Class Engine
 *
 * Enum for available templating engines
 *
 * @package cedrichaase\DotGen\TemplatingEngine
 */
class Engine
{
    /**
     * The default engine, used if none is specified
     */
    const ENGINE_DEFAULT = self::ENGINE_TWIG;

    /**
     * twig.
     *
     * @link http://twig.sensiolabs.org/
     */
    const ENGINE_TWIG = 'twig';

    /**
     * @return array
     */
    public static function getEngines()
    {
        return [
            self::ENGINE_TWIG,
        ];
    }
}