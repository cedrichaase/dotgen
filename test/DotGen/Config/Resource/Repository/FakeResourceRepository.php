<?php
namespace DotGen\Config\Resource\Repository;

use DotGen\Config\Resource\IResource;

/**
 * Class FakeResourceRepository
 * @package DotGen\Config\Resource\Repository
 */
class FakeResourceRepository implements IResourceRepository
{
    /**
     * @var IResource[]
     */
    private $resources;

    /**
     * FakeResourceRepository constructor.
     */
    public function __construct()
    {
        $this->resources = [];
    }

    /**
     * Finds an IResource by given $name
     *
     * @param $name
     *
     * @return mixed
     *
     * @throws ResourceRepositoryException
     */
    public function findResourceByName(string $name): IResource
    {
        $resource = $this->resources[$name] ?? null;

        if(!$resource)
        {
            throw new ResourceRepositoryException('could not find resource by name ' . $name);
        }

        return $resource;
    }

    /**
     * @param IResource $resource
     */
    public function pushResource(IResource $resource)
    {
        $this->resources[$resource->getName()] = $resource;
    }
}