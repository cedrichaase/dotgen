<?php
namespace DotGen\Config\Resource;

/**
 * Interface ICache
 * 
 * @package DotGen\Config\Resource
 */
interface ICache
{
    /**
     * @param $key
     *
     * @return mixed
     */
    public function find($key);

    /**
     * @param $object
     * @param null $key
     *
     * @return void
     */
    public function save($object, $key = null);
}