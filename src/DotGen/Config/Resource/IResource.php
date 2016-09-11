<?php
namespace DotGen\Config\Resource;
use DotGen\Config\Entity\Collection;

/**
 * Interface IResource
 * 
 * @package DotGen\Config
 */
interface IResource
{
    /**
     * Return the collections managed by this resource
     *
     * @return Collection[]
     */
    public function getCollections();

    /**
     * Return the engine key of the engine to use for this resource
     *
     * @return string
     */
    public function getEngine();

    /**
     * Return the path to find templates in
     *
     * @return string
     */
    public function getInputPath();

    /**
     * Return the path to output rendered text files to
     *
     * @return string
     */
    public function getOutputPath();
}