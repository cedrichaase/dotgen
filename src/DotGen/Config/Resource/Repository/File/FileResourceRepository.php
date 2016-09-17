<?php
namespace DotGen\Config\Resource\Repository\File;

use DotGen\Config\Resource\Converter\ArrayToResourceConverter;
use DotGen\Config\Resource\IResource;
use DotGen\Config\Resource\Parser\ParserException;
use DotGen\Config\Resource\Parser\ParserManager;
use DotGen\Config\Resource\Repository\IResourceRepository;
use DotGen\Config\Resource\Repository\ResourceRepositoryException;

/**
 * Class FileResourceRepository
 *
 * @package DotGen\Config\Resource\Repository
 */
class FileResourceRepository implements IResourceRepository
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var ParserManager
     */
    private $parser;

    /**
     * FileResourceRepository constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->parser = new ParserManager();
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
        $files = scandir($this->path);
        
        if(!$files)
        {
            throw new FileResourceRepositoryException('No files found in given path ' . $this->path);
        }

        foreach($files as $file)
        {
            $filePath = realpath($this->path . DIRECTORY_SEPARATOR . $file);
            $string = file_get_contents($filePath);

            // if the file can not be parsed, just continue
            try {
                $array = $this->parser->parse($string);
            } catch(ParserException $e)
            {
                continue;
            }

            $converter = new ArrayToResourceConverter();
            $resource = $converter->convert($array);

            if($resource->getName() === $name)
            {
                return $resource;
            }
        }

        throw new FileResourceRepositoryException(
            'Resource with name ' . $name . ' not found in path ' . $this->path
        );
    }
}