<?php
namespace DotGen\Config;

/**
 * Class FakeResource
 *
 * Fake IResource for testing
 *
 * @package DotGen\Config
 */
class FakeResource implements IResource
{
    /**
     * @var Collection[]
     */
    private $collections;

    /**
     * @var string
     */
    private $engine;

    /**
     * @var string
     */
    private $inputPath;

    /**
     * @var string
     */
    private $outputPath;

    /**
     * Return the collections managed by this resource
     *
     * @return Collection[]
     */
    public function getCollections()
    {
        return $this->collections;
    }

    /**
     * Return the engine key of the engine to use for this resource
     *
     * @return string
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * Return the path to find templates in
     *
     * @return string
     */
    public function getInputPath()
    {
        return $this->inputPath;
    }

    /**
     * Return the path to output rendered text files to
     *
     * @return string
     */
    public function getOutputPath()
    {
        return $this->outputPath;
    }

    /**
     * @param \DotGen\Config\Collection[] $collections
     */
    public function setCollections($collections)
    {
        $this->collections = $collections;
    }

    /**
     * @param string $engine
     */
    public function setEngine($engine)
    {
        $this->engine = $engine;
    }

    /**
     * @param string $inputPath
     */
    public function setInputPath($inputPath)
    {
        $this->inputPath = $inputPath;
    }

    /**
     * @param string $outputPath
     */
    public function setOutputPath($outputPath)
    {
        $this->outputPath = $outputPath;
    }
}