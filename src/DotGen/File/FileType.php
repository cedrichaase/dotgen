<?php
namespace DotGen\File;

/**
 * Class FileType
 *
 * File type enum
 */
class FileType
{
    const FILE_TYPE_INI = 'ini';

    /**
     * @return string[]
     */
    public static function getFileTypes()
    {
        return [
            self::FILE_TYPE_INI,
        ];
    }
}