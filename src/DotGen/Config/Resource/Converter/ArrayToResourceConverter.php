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
     * The reserved collection array key for extending another resource
     */
    const COLLECTION_KEY_EXTENDS = '__extends';

    /**
     * The reserved collection array key for naming a resource
     */
    const COLLECTION_KEY_NAME = '__name';

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
        $name = $this->extractName($array);
        $extends = $this->extractExtends($array);

        $resource = new ConfigResource();
        $resource->setCollections($collections);
        $resource->setName($name);
        $resource->setExtends($extends);

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

        $global = $rawCollections[self::COLLECTION_NAME_GLOBAL] ?? [];
        unset($rawCollections[self::COLLECTION_NAME_GLOBAL]);

        foreach($rawCollections as $name => $rawCollection)
        {
            // extract and remove templates array from collection
            $templates = $rawCollection[self::COLLECTION_KEY_TEMPLATES] ?? [];
            if(!$templates)
            {
                $this->logger->warning('No templates found for collection', [
                    'collection' => $name,
                ]);
                unset($rawCollection);
                continue;
            }

            // merge with global
            $rawCollection = array_merge($global, $rawCollection);

            foreach($this->reservedKeys() as $key)
            {
                unset($rawCollection[$key]);
            }

            // push collection
            $collections[$name] = new Collection($name, $rawCollection, $templates);
        }

        return $collections;
    }

    /**
     * @param array $array
     * 
     * @return string
     */
    private function extractName(array $array): string
    {
        $name = $array[self::COLLECTION_NAME_GLOBAL][self::COLLECTION_KEY_NAME] ?? null;

        if(!$name)
        {
            $name = substr(sha1(json_encode($array)), 0, 7);
        }

        return $name;
    }

    /*
     * @return string
     */
    private function extractExtends(array $array): string
    {
        return $array[self::COLLECTION_NAME_GLOBAL][self::COLLECTION_KEY_EXTENDS] ?? '';
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

    /**
     * @return string[]
     */
    private function reservedKeys()
    {
        return [
            self::COLLECTION_KEY_NAME,
            self::COLLECTION_KEY_EXTENDS,
            self::COLLECTION_KEY_TEMPLATES,
        ];
    }
}