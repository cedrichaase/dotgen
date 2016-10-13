<?php
namespace DotGen\Config\Resource\Converter;

use DotGen\Config\Resource\ICache;

class ArrayConversionCache implements ICache
{
    private $cache;

    /**
     * @param $array
     *
     * @return mixed
     */
    public function find($array)
    {
        return $this->cache[sha1(json_encode($array))] ?? null;
    }

    /**
     * @param $resource
     * @param null $array
     */
    public function save($resource, $array = null)
    {
        $this->cache[sha1(json_encode($array))] = $resource;
    }
}