<?php
namespace DotGen\Config\Resource\InheritanceHandler;

use DotGen\Config\Entity\Collection;
use DotGen\Config\Resource\ConfigResource;
use DotGen\Config\Resource\IResource;
use DotGen\Config\Resource\Repository\IResourceRepository;
use DotGen\Config\Resource\Repository\ResourceRepositoryException;

/**
 * Class InheritanceHandler
 *
 * @package DotGen\Config\Resource\InheritanceHandler
 */
class InheritanceHandler
{
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
        do {
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

        foreach($childCollections as $childCollection)
        {
            $parentCollection = $parentCollections[$childCollection->getName()] ?? '';

            if($parentCollection && $childCollection)
            {
                $mergedContent = array_replace_recursive(
                    $parentCollection->getContent(),
                    $childCollection->getContent()
                );

                $mergedTemplates = array_replace_recursive(
                    $parentCollection->getTemplates(),
                    $childCollection->getTemplates()
                );

                $mergedCollections[] = new Collection(
                    $childCollection->getName(),
                    $mergedContent,
                    $mergedTemplates
                );
            }
            elseif(!$parentCollection && $childCollection)
            {
                $mergedCollections[] = $childCollection;
            }
            elseif($parentCollection && !$childCollection)
            {
                $mergedCollections[] = $parentCollection;
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
}