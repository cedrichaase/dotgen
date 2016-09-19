<?php
namespace DotGen\Config\Resource\Converter;

use DotGen\Config\Resource\IResource;
use Psr\Log\LoggerInterface;

/**
 * Interface IArrayToResourceConverter
 * @package DotGen\Config\Resource\Converter
 */
interface IArrayToResourceConverter
{
    /**
     * @param array $array
     * @return IResource
     */
    public function convert(array $array): IResource;

    /**
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger);
}