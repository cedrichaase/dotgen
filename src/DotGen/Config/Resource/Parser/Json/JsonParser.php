<?php
namespace DotGen\Config\Resource\Parser\Json;

use DotGen\Config\Resource\Parser\IParser;
use DotGen\Config\Resource\Parser\ParseCache;
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
     * @var ParseCache
     */
    private $cache;

    /**
     * JsonParser constructor.
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
     * Determine whether or not the given string
     * can be parsed by this parser
     *
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
        catch(JsonParserException $e)
        {
            $supports = false;
        }

        return $supports;
    }

    /**
     * @param $string
     * @return array
     *
     * @throws JsonParserException
     */
    private function doParse($string): array
    {
        if(!$parsed = $this->cache->find($string))
        {
            $parsed = json_decode($string, true, self::MAX_DEPTH);

            if(!$parsed)
            {
                throw new JsonParserException("Error parsing JSON string");
            }
            
            $this->cache->save($string, $parsed);
        }

        return $parsed;
    }
}