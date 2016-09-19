<?php
namespace DotGen\Config\Resource\Repository\File;

use DotGen\Config\Resource\Converter\ArrayToResourceConverter;
use DotGen\Config\Resource\Converter\IArrayToResourceConverter;
use DotGen\Config\Resource\IResource;
use DotGen\Config\Resource\Parser\ParserException;
use DotGen\Config\Resource\Parser\ParserManager;
use DotGen\Config\Resource\Repository\IResourceRepository;
use DotGen\Config\Resource\Repository\ResourceCache;
use DotGen\Config\Resource\Repository\ResourceRepositoryException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class FileResourceRepository
 *
 * @package DotGen\Config\Resource\Repository
 */
class FileResourceRepository implements IResourceRepository
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ResourceCache
     */
    private $cache;

    /**
     * @var IArrayToResourceConverter
     */
    private $converter;

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
        $this->converter = new ArrayToResourceConverter();
        $this->logger = new NullLogger();
        $this->cache = new ResourceCache();
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
        $this->logger->debug('find resource by name', [
            'name' => $name,
        ]);

        if($resource = $this->cache->find($name))
        {
            return $resource;
        }

        $files = scandir($this->path, -1);
        
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

            $resource = $this->converter->convert($array);

            $this->logger->debug('built resource', [
               'source' => $file,
            ]);

            if($resource->getName() === $name)
            {
                $this->cache->save($resource);
                return $resource;
            }
        }

        throw new FileResourceRepositoryException(
            'Resource with name ' . $name . ' not found in path ' . $this->path
        );
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->converter->setLogger($logger);
    }
}