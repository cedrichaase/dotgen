<?php
namespace DotGen\ConfigLoader;

interface ConfigLoaderInterface
{
    /**
     * Returns the config options for the given config name
     *
     * @param string $collection
     *
     * @return array
     */
    public function getCollectionValues($collection);

    /**
     * Returns the file paths associated with given collection
     *
     * @param string $collection
     *
     * @return string[]
     */
    public function getFilePathsForCollection($collection);

    /**
     * Returns the names of all file names that are associated with a section
     *
     * @return string[]
     */
    public function getFileNames();

    /**
     * Returns the output path for rendered files
     *
     * @return string
     */
    public function getOutputPath();

    /**
     * Returns the path to read templates from
     *
     * @return string
     */
    public function getInputPath();

    /**
     * @return string
     */
    public function getTemplatingEngine();
}