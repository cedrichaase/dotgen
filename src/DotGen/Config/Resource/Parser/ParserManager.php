<?php
namespace DotGen\Config\Resource\Parser;
use DotGen\Config\Resource\Parser\Ini\IniParser;
use DotGen\Config\Resource\Parser\Json\JsonParser;
use DotGen\Config\Resource\Parser\Yaml\YamlParser;

/**
 * Class ParserManager
 *
 * @package DotGen\Config\Resource\Parser
 */
class ParserManager
{
    /**
     * @var IParser[]
     */
    private $parsers = [];

    /**
     * ParserManager constructor.
     */
    public function __construct()
    {
        $this->registerParser(new IniParser());
        $this->registerParser(new JsonParser());
        $this->registerParser(new YamlParser());
    }

    /**
     * @param string $string
     *
     * @return array
     *
     * @throws ParserException
     */
    public function parse(string $string): array
    {
        foreach($this->parsers as $parser)
        {
            if($parser->supports($string))
            {
                return $parser->parse($string);
            }
        }

        throw new ParserException('Do not know how to parse given string');
    }

    /**
     * Registers a new parser with the factory
     * If multiple parsers support the same input
     * the most recently added parser will take precedence
     *
     * @param IParser $parser
     */
    public function registerParser(IParser $parser)
    {
        array_unshift($this->parsers, $parser);
    }

    /**
     * Clears all registered parses and exclusively
     * registers the given Parser
     *
     * @param IParser $parser
     */
    public function registerExclusive(IParser $parser)
    {
        unset($this->parsers);
        $this->parsers = [];
        $this->registerParser($parser);
    }
}