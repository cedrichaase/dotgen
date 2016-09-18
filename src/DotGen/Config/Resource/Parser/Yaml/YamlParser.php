<?php
namespace DotGen\Config\Resource\Parser\Yaml;

use DotGen\Config\Resource\Parser\IParser;
use DotGen\Config\Resource\Parser\ParseCache;
use DotGen\Config\Resource\Parser\ParserException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class YamlParser implements IParser
{
    /**
     * @var ParseCache
     */
    private $cache;

    /**
     * YamlParser constructor.
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
        try {
            return (array) $this->doParse($string);
        } catch (ParseException $e)
        {
            throw new YamlParserException('Unable to parse string as yaml');
        }
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

        try {
            $this->doParse($string);
        } catch (ParseException $e)
        {
            $supports = false;
        }

        return $supports;
    }

    /**
     * @param $string
     *
     * @return array
     */
    private function doParse($string)
    {
        if(!$parsed = $this->cache->find($string))
        {
            $parsed = (array) Yaml::parse($string);

            $this->cache->save($string, $parsed);
        }

        return $parsed;
    }
}