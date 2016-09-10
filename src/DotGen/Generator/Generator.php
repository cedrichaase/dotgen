<?php
namespace DotGen\Generator;

use DotGen\Config\Collection;
use DotGen\Config\ResourceInterface;
use DotGen\File\HandlesFilesystemTrait;
use DotGen\TemplatingEngine\TemplatingEngineException;
use DotGen\TemplatingEngine\TemplatingEngineFactory;
use DotGen\TemplatingEngine\TemplatingEngineInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class Generator
 *
 * @package cedrichaase\DotGen\Generator
 */
class Generator
{
    use HandlesFilesystemTrait;
    
    /**
     * @var TemplatingEngineInterface
     */
    private $engine;

    /**
     * @var ResourceInterface
     */
    private $resource;

    /**
     * @var LoggerInterface
     */
    private $log;

    /**
     * Generator constructor.
     *
     * @param ResourceInterface $resource
     *
     * @throws TemplatingEngineException
     */
    public function __construct(ResourceInterface $resource)
    {
        $engineKey = $resource->getEngine();
        $templateDir = $resource->getOutputPath();

        $engine = TemplatingEngineFactory::createFromEngineKeyAndTemplateDir($engineKey, $templateDir);

        $this->resource = $resource;
        $this->engine = $engine;
        $this->log = new NullLogger();
    }

    /**
     * Render all collections
     */
    public function render()
    {
        $startTime = microtime(true);

        $collections = $this->resource->getCollections();

        $this->log->info('Begin rendering text files', [
            'count' => count($collections),
        ]);

        foreach($collections as $i => $collection)
        {
            $this->renderCollection($collection);
        }

        $this->log->info('Done rendering text files', [
            'count' => count($collections),
            'time' => microtime(true) - $startTime,
        ]);
    }

    /**
     * Render a collection
     *
     * @param Collection $collection
     */
    private function renderCollection(Collection $collection)
    {
        $files = $collection->getFiles();
        foreach ($files as $i => $file)
        {
            $this->renderFile($collection, $file);
        }
    }

    /**
     * Render a file from a collection
     *
     * @param Collection $collection
     * @param $file
     */
    private function renderFile(Collection $collection, $file)
    {
        $srcPath = $file . '.' . $this->engine->getFileExtension();
        $dstPath = $this->resource->getOutputPath() . DIRECTORY_SEPARATOR . $file;

        $name = $collection->getName();

        $this->log->debug('Rendering text file', [
            'name' => $name,
            'src_path' => $srcPath,
            'dst_path' => $dstPath,
        ]);

        self::createPathIfNotExists($dstPath);

        $contents = $this->engine->render(
            $srcPath,
            $collection->getContent()
        );

        file_put_contents($dstPath, $contents);
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->log = $logger;
    }
}