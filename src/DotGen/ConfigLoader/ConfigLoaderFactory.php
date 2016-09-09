<?php
namespace DotGen\ConfigLoader;

use DotGen\ConfigLoader\Resource\FileResource;
use DotGen\ConfigLoader\Resource\ResourceInterface;
use DotGen\ConfigLoader\Resource\UnknownResourceTypeException;
use DotGen\File\GuessesFileTypeTrait;
use DotGen\File\UnknownFileTypeException;
use DotGen\File\FileType;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class ConfigLoaderFactory
 * 
 * @package cedrichaase\DotGen\ConfigLoader
 */
class ConfigLoaderFactory
{
    use GuessesFileTypeTrait;

    /**
     * Creates a ConfigLoader from file by given $filePath
     *
     * @param $filePath
     * @return ConfigLoaderInterface
     *
     * @throws ConfigLoaderException
     * @throws UnknownFileTypeException
     */
    public static function createFromFileResource(FileResource $resource)
    {
        $type = $resource->getFileType();
        $path = $resource->getPath();
        
        switch($type)
        {
            case FileType::FILE_TYPE_INI:
                $loader = new IniConfigLoader($path);
                break;
            default:
                throw new ConfigLoaderException('No config loader found for file of type ' . $type);
        }
        
        return $loader;
    }

    /**
     * @param ResourceInterface $resource
     *
     * @return ConfigLoaderInterface
     *
     * @throws ConfigLoaderException
     * @throws UnknownResourceTypeException
     */
    public static function createFromResource(ResourceInterface $resource)
    {
        if($resource instanceof FileResource)
        {
            return self::createFromFileResource($resource);
        }
        
        throw new UnknownResourceTypeException('Creating ConfigLoader from resource of following type is not implemented: ' . get_class($resource));
    }
}