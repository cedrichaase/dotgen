<?php
namespace DotGen\Config\Resource\Repository;

use DotGen\Config\Resource\IResource;

/**
 * Interface IResourceRepository
 *
 * @package DotGen\Config\Resource\Repository
 */
interface IResourceRepository
{
    /**
     * Finds an IResource by given $name
     *
     * @param $name
     *
     * @return IResource
     * 
     * @throws ResourceRepositoryException
     */
    public function findResourceByName(string $name): IResource;
}