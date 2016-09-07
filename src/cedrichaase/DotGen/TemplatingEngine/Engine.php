<?php
namespace cedrichaase\DotGen\TemplatingEngine;

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