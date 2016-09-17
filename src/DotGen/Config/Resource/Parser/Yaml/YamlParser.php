<?php
namespace DotGen\Config\Resource\Parser\Yaml;

use DotGen\Config\Resource\Parser\IParser;
use DotGen\Config\Resource\Parser\ParserException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class YamlParser implements IParser
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
        try {
            return (array) Yaml::parse($string);
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
            Yaml::parse($string);
        } catch (ParseException $e)
        {
            $supports = false;
        }

        return $supports;
    }
}