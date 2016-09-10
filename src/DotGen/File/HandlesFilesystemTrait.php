<?php
namespace DotGen\File;


trait HandlesFilesystemTrait
{
    /**
     * @param string $path
     */
    protected static function createPathIfNotExists(string $path)
    {
        $dir = dirname(realpath($path));
        if($dir && !is_dir($dir))
        {
            mkdir($dir, 0777, true);
        }
    }

    /**
     * @param string $path
     *
     * @throws FileHandlingException
     */
    protected static function assertFileExists(string $path)
    {
        $path = realpath($path);

        if(!file_exists($path))
        {
            throw new FileHandlingException('File does not exist: ' . $path);
        }
    }

    /**
     * Check if path is a relative path
     *
     * @param string $path
     * @return bool
     */
    protected static function isRelativePath(string $path)
    {
        $beginsWith = '/';
        return !(substr($path, 0, strlen($beginsWith)) === $beginsWith);
    }
}