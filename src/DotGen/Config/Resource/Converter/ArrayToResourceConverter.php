<?php
namespace DotGen\Config\Resource\Converter;

use DotGen\Config\Entity\Collection;
use DotGen\Config\Resource\ConfigResource;
use DotGen\Config\Resource\IResource;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ArrayToResourceConverter implements IArrayToResourceConverter
{
    /**
     * The reserved collection array key for names of templates
     * that should be rendered using the variables from the same collection
     */
    const COLLECTION_KEY_TEMPLATES = '__templates';

    /**
     * The reserved collection name for the global collection
     */
    const COLLECTION_NAME_GLOBAL = 'global';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ArrayToResourceConverter constructor.
     */
    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    /**
     * @param array $array
     * @return IResource
     */
    public function convert(array $array): IResource
    {
        $collections = $this->extractCollections($array);

        $resource = new ConfigResource();
        $resource->setCollections($collections);

        return $resource;
    }

    /**
     * @param array $rawCollections
     *
     * @return Collection[]
     */
    private function extractCollections(array $rawCollections)
    {
        $collections = [];

        $global = $rawCollections[self::COLLECTION_NAME_GLOBAL];
        unset($rawCollections[self::COLLECTION_NAME_GLOBAL]);

        foreach($rawCollections as $name => $rawCollection)
        {
            // extract and remove templates array from collection
            $templates = $rawCollection[self::COLLECTION_KEY_TEMPLATES];
            if(!$templates)
            {
                $this->logger->warning('No templates found for collection', [
                    'collection' => $name,
                ]);
                continue;
            }

            unset($rawCollection[self::COLLECTION_KEY_TEMPLATES]);

            // merge with global
            $rawCollection = array_merge($global, $rawCollection);

            // push collection
            $collections[] = new Collection($name, $rawCollection, $templates);
        }

        return $collections;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}