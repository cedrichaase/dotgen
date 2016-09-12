<?php
namespace DotGen\Config\Resource\Parser;

/**
 * Interface IParser
 *
 * @package DotGen\Config\Resource\Parser
 */
interface IParser
{
    /**
     * Parse the given string to an array
     *
     * @param string $string
     * @return array
     *
     * @throws ParserException
     */
    public function parse(string $string): array;
}