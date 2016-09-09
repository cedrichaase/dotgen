<?php
namespace DotGen\File;

trait GuessesFileTypeTrait
{
    /**
     * Guesses the file type of the given file
     *
     * @param $filePath
     * @return string
     *
     * @throws UnknownFileTypeException
     */
    protected static function guessFileType($filePath)
    {
        $filePath = explode('.', $filePath);
        $extension = array_pop($filePath);
        
        switch($extension)
        {
            case 'ini':
                return FileType::FILE_TYPE_INI;
        }
        
        throw new UnknownFileTypeException('Unknown file type for file with extension ' . $extension);
    }
}