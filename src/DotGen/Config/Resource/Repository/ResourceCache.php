<?php
namespace DotGen\Config\Resource\Repository;

use DotGen\Config\Resource\ICache;
use DotGen\Config\Resource\IResource;

class ResourceCache implements ICache
{
    /**
     * @var array
     */
    private $cache;

    /**
     * @param string $name
     * 
     * @return IResource
     */
    public function find($name)
    {
        return ($this->cache[sha1($name)]) ?? null;
    }

    /**
     * @param IResource $resource
     */
    public function save($resource, $key = null)
    {
        $name = $resource->getName();
        $this->cache[sha1($name)] = $resource;
    }
}