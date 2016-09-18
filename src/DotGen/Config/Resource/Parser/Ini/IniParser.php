<?php
namespace DotGen\Config\Resource\Parser\Ini;

use DotGen\Config\Resource\Parser\IParser;
use DotGen\Config\Resource\Parser\ParseCache;
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
     * @var ParseCache
     */
    private $cache;

    /**
     * IniParser constructor.
     */
    public function __construct()
    {
        $this->cache = new ParseCache();
    }

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
        return $this->doParse($string);
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
            $this->doParse($string);
        }
        catch(IniParserException $e)
        {
            $supports = false;
        }

        return $supports;
    }

    /**
     * @param $string
     * @return array
     *
     * @throws IniParserException
     */
    private function doParse($string)
    {
        if(!$parsed = $this->cache->find($string))
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

            $this->cache->save($string, $parsed);
        }

        return $parsed;
    }
}