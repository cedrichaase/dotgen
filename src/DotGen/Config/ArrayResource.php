<?php
namespace DotGen\Config;

use DotGen\Config\Entity\Collection;

class ArrayResource implements IResource
{
    /**
     * The reserved collection array key for files
     * that are managed by the respective array collection
     */
    const COLLECTION_KEY_FILES = '__files';

    /**
     * @var Collection[]
     */
    private $collections;

    /**
     * @var string
     */
    private $inputPath;

    /**
     * @var string
     */
    private $outputPath;

    /**
     * @var string
     */
    private $engine;

    /**
     * ArrayResource constructor.
     *
     * @param array     $collections    The collections as name => [key => value]
     * @param string    $inputPath      The absolute path to the templates managed by this resource
     * @param string    $outputPath     The absolute path to the output directory for rendered files
     * @param string    $engine         The name of the engine to use for files managed by this resource
     */
    public function __construct(array $collections, string $inputPath, string $outputPath, string $engine)
    {
        $this->collections = self::buildCollectionArray($collections);
        $this->inputPath = $inputPath;
        $this->outputPath = $outputPath;
        $this->engine = $engine;
    }

    /**
     * @return Collection[]
     */
    public function getCollections()
    {
        return $this->collections;
    }

    /**
     * @return string
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @return string
     */
    public function getInputPath()
    {
        return $this->inputPath;
    }

    /**
     * @return string
     */
    public function getOutputPath()
    {
        return $this->outputPath;
    }

    /**
     * @param array $rawCollections
     *
     * @return Collection[]
     */
    private static function buildCollectionArray(array $rawCollections)
    {
        $collections = [];

        foreach($rawCollections as $name => $rawCollection)
        {
            // extract and remove files array from collection
            $files = $rawCollection[self::COLLECTION_KEY_FILES];
            unset($rawCollection[self::COLLECTION_KEY_FILES]);

            // push collection
            $collections[] = new Collection($name, $rawCollection, $files);
        }

        return $collections;
    }
}