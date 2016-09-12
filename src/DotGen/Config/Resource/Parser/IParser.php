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

    /**
     * Determine whether or not the given string
     * can be parsed by this parser
     *
     * @param string $string
     * @return bool
     */
    public function supports(string $string): bool;
}