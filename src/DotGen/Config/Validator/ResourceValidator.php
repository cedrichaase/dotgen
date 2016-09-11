<?php
namespace DotGen\Config\Validator;

use DotGen\Config\IResource;

/**
 * Class ResourceValidator
 *
 * @package DotGen\Config\Validator
 */
class ResourceValidator implements IResourceValidator
{
    /**
     * @param IResource $resource
     *
     * @throws ResourceValidationException
     */
    public static function validate(IResource $resource)
    {
        self::validateNoDuplicateFileReferences($resource);
    }

    /**
     * Validate that for the given resource, no two collections
     * reference the same file
     *
     * @param IResource $resource
     *
     * @throws DuplicateFileReferenceException
     */
    protected static function validateNoDuplicateFileReferences(IResource $resource)
    {
        $collections = $resource->getCollections();

        // seen files mapped as file => collection name
        $seenFiles = [];

        foreach($collections as $i => $collection)
        {
            $collectionName = $collection->getName();
            $collectionFiles = $collection->getFiles();
            
            foreach($collectionFiles as $j => $file)
            {
                if(array_key_exists($file, $seenFiles))
                {
                    $firstReference = $seenFiles[$file];
                    $secondReference = $collectionName;
                    throw new DuplicateFileReferenceException("Duplicate reference for file ${file}: first found in ${firstReference}, found again in ${secondReference}");
                }                
                
                $seenFiles[$file] = $collectionName;
            }
        }
    }
}