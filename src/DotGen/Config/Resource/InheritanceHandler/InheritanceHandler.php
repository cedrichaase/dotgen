<?php
namespace DotGen\Config\Resource\InheritanceHandler;

use DotGen\Config\Entity\Collection;
use DotGen\Config\Resource\ConfigResource;
use DotGen\Config\Resource\IResource;
use DotGen\Config\Resource\Repository\IResourceRepository;
use DotGen\Config\Resource\Repository\ResourceRepositoryException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class InheritanceHandler
 *
 * @package DotGen\Config\Resource\InheritanceHandler
 */
class InheritanceHandler
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var IResourceRepository[]
     */
    private $repositories;

    /**
     * InheritanceHandler constructor.
     */
    public function __construct()
    {
        $this->repositories = [];
        $this->logger = new NullLogger();
    }

    /**
     * Extends $child with $parent
     *
     * @param IResource $child
     * @return IResource
     *
     * @throws InheritanceHandlerException
     */
    public function extend(IResource $child): IResource
    {
        // first, we visit the child
        $visited = [$child->getName() => true];

        do {
            if (true === ($visited[$child->getExtends()] ?? false))
            {
                $this->logger->warning('You have a cyclical dependency in your inheritance hierarchy', [
                    'resources' => array_keys($visited),
                ]);

                break;
            }

            // next, we visit the parents
            $visited[$child->getExtends()] = true;
            $child = $this->doExtend($child);

        } while($child->getExtends());

        return $child;
    }

    private function doExtend(IResource $child): IResource
    {
        $parentName = $child->getExtends();

        if(!$parentName)
        {
            return $child;
        }

        $parent = $this->findResourceByName($parentName);

        $mergedCollections = $this->extendCollections(
            $child->getCollections(),
            $parent->getCollections()
        );

        $merged = new ConfigResource();
        $merged->setName($child->getName());
        $merged->setExtends($parent->getExtends());
        $merged->setCollections($mergedCollections);

        return $merged;
    }

    /**
     * @param Collection[] $childCollections
     * @param Collection[] $parentCollections
     *
     * @return Collection[] $mergedCollections
     */
    private function extendCollections(array $childCollections, array $parentCollections)
    {
        $mergedCollections = [];

        foreach($parentCollections as $parentCollection)
        {
            $childCollection = $childCollections[$parentCollection->getName()] ?? null;

            if($childCollection)
            {
                $mergedContent = array_replace_recursive(
                    $parentCollection->getContent(),
                    $childCollection->getContent()
                );

                $mergedTemplates = array_merge(
                    $parentCollection->getTemplates(),
                    $childCollection->getTemplates()
                );

                $mergedCollections[$childCollection->getName()] = new Collection(
                    $childCollection->getName(),
                    $mergedContent,
                    $mergedTemplates
                );
            }
            else
            {
                $mergedCollections[$parentCollection->getName()] = $parentCollection;
            }
        }

        return $mergedCollections;
    }

    /**
     * @param string $name
     *
     * @return IResource
     * @throws InheritanceHandlerException
     */
    private function findResourceByName(string $name)
    {
        foreach($this->repositories as $repo)
        {
            try {
                $resource = $repo->findResourceByName($name);
            } catch(ResourceRepositoryException $e)
            {
                continue;
            }
            
            return $resource;
        }

        throw new InheritanceHandlerException('Could not find resource ' . $name . ' in any repository.');
    }

    /**
     * @param IResourceRepository $repository
     */
    public function registerRepository(IResourceRepository $repository)
    {
        array_unshift($this->repositories, $repository);
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}