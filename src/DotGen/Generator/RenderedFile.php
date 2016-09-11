<?php
namespace DotGen\Generator;

/**
 * Class RenderedFile
 *
 * @package DotGen\Generator
 */
class RenderedFile
{
    /**
     * @var string
     */
    private $contents;

    /**
     * @var string
     */
    private $destinationPath;

    /**
     * RenderedFile constructor.
     *
     * @param $contents
     * @param string $destinationPath
     */
    public function __construct($contents, $destinationPath)
    {
        $this->contents = $contents;
        $this->destinationPath = $destinationPath;
    }

    /**
     * @return string
     */
    public function getDestinationPath()
    {
        return $this->destinationPath;
    }

    /**
     * @param string $destinationPath
     */
    public function setDestinationPath($destinationPath)
    {
        $this->destinationPath = $destinationPath;
    }

    /**
     * @return string
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param string $contents
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
    }
}