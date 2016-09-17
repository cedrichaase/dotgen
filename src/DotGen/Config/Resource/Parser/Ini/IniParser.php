<?php
namespace DotGen\Config\Resource\Parser\Ini;

use DotGen\Config\Resource\Parser\IParser;
use DotGen\Config\Resource\Parser\ParserException;

/**
 * Class IniParser
 *
 * Parses an INI string to an associative array
 *
 * @package DotGen\Config\Resource\Parser\Ini
 */
class IniParser implements IParser
{
    /**
     * Parse the given string to an array
     *
     * @param string $string
     * @return array
     *
     * @throws ParserException
     */
    public function parse(string $string): array
    {
        set_error_handler(function() {
            restore_error_handler();
            throw new IniParserException("Error parsing INI string");
        }, E_WARNING);

        $parsed = parse_ini_string($string, true, INI_SCANNER_TYPED);

        if(!$parsed)
        {
            throw new IniParserException("Error parsing INI string");
        }

        return $parsed;
    }

    /**
     * @param string $string
     * @return bool
     */
    public function supports(string $string): bool
    {
        $supports = true;

        try
        {
            $this->parse($string);
        }
        catch(IniParserException $e)
        {
            $supports = false;
        }

        return $supports;
    }
}