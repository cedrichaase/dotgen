<?php
namespace DotGen\Config\Resource\Parser;

/**
 * Class ParseCache
 *
 * @package DotGen\Config\Resource\Parser
 */
class ParseCache
{
    /**
     * @var array
     */
    private $cache;

    /**
     * ParseCache constructor.
     */
    public function __construct()
    {
        $this->cache = [];
    }

    /**
     * @param string $string
     *
     * @return array
     */
    public function find(string $string): array
    {
        return ($this->cache[sha1($string)]) ?? [];
    }

    /**
     * @param string $string
     *
     * @param array $parsed
     */
    public function save(string $string, array $parsed)
    {
        $this->cache[sha1($string)] = $parsed;
    }
}