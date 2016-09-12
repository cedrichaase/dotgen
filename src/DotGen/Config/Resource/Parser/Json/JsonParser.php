<?php
namespace DotGen\Config\Resource\Parser\Json;

use DotGen\Config\Resource\Parser\IParser;
use DotGen\Config\Resource\Parser\ParserException;

/**
 * Class JsonParser
 *
 * Parses a JSON string to an associative array
 *
 * @package DotGen\Config\Resource\Parser\Json
 */
class JsonParser implements IParser
{
    /**
     * Maximum parsing depth
     */
    const MAX_DEPTH = 512;

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
        $parsed = json_decode($string, true, self::MAX_DEPTH);
        
        if(!$parsed)
        {
            throw new JsonParserException("Error parsing JSON string");
        }

        return $parsed;
    }
}