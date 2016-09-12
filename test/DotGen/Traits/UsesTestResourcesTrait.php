<?php
namespace DotGen\Traits;

trait UsesTestResourcesTrait
{
    protected static function resourcesDir()
    {
        $path = realpath(__DIR__ . '/../../resource');

        return realpath($path);
    }

    protected static function iniResourcesDir()
    {
        return self::resourcesDir() . '/ini';
    }

    protected static function jsonResourcesDir()
    {
        return self::resourcesDir() . '/json';
    }
}