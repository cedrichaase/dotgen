<?php
namespace DotGen\Config\Resource\Parser;

class FakeParser implements IParser
{
    /**
     * @var array
     */
    private $parsed;

    /**
     * @var bool
     */
    private $supports;

    /**
     * FakeParser constructor.
     *
     * @param array $parsed
     * @param $supports
     */
    public function __construct(array $parsed, $supports)
    {
        $this->parsed = $parsed;
        $this->supports = $supports;
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
        return $this->parsed;
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
        return $this->supports;
    }

    /**
     * @param boolean $supports
     */
    public function setSupports($supports)
    {
        $this->supports = $supports;
    }

    /**
     * @param array $parsed
     */
    public function setParsed($parsed)
    {
        $this->parsed = $parsed;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}