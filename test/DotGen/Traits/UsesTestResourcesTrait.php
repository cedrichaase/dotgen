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

    protected static function yamlResourceDir()
    {
        return self::resourcesDir() . '/yaml';
    }

    protected static function getAllJsonResources()
    {
        return self::getAllResourcesForBaseDir(self::jsonResourcesDir());
    }

    protected static function getAllIniResources()
    {
        return self::getAllResourcesForBaseDir(self::iniResourcesDir());
    }

    protected static function getAllYamlResources()
    {
        return self::getAllResourcesForBaseDir(self::yamlResourceDir());
    }

    private static function getAllResourcesForBaseDir($baseDir)
    {
        $files = scandir($baseDir);

        $files = array_filter($files, function($file) {
            return (!in_array($file, ['.', '..']));
        });

        $files = array_map(function ($file) use ($baseDir) {
            return realpath($baseDir . DIRECTORY_SEPARATOR . $file);
        }, $files);

        return $files;
    }
}