<?php
namespace cedrichaase\DotGen\ConfigLoader;

use cedrichaase\DotGen\FileType\GuessesFileTypeTrait;
use cedrichaase\DotGen\FileType\UnknownFileTypeException;
use cedrichaase\DotGen\FileType\FileType;

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
    public static function createFromFile($filePath)
    {
        $type = self::guessFileType($filePath);
        
        switch($type)
        {
            case FileType::FILE_TYPE_INI:
                $loader = new IniConfigLoader($filePath);
                break;
            default:
                throw new ConfigLoaderException('No config loader found for file of type ' . $type);
        }
        
        return $loader;
    }
}