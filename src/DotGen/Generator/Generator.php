<?php
namespace DotGen\Generator;

use DotGen\Config\Collection;
use DotGen\Config\IResource;
use DotGen\File\HandlesFilesystemTrait;
use DotGen\TemplateEngine\TemplateEngineException;
use DotGen\TemplateEngine\TemplateEngineFactory;
use DotGen\TemplateEngine\TemplateEngineInterface;
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
     * @var TemplateEngineInterface
     */
    private $engine;

    /**
     * @var IResource
     */
    private $resource;

    /**
     * @var LoggerInterface
     */
    private $log;

    /**
     * Generator constructor.
     *
     * @param IResource $resource
     * @param TemplateEngineInterface $engine
     */
    public function __construct(IResource $resource, TemplateEngineInterface $engine)
    {
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
            yield $this->renderCollection($collection);
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
     *
     * @return \Generator
     */
    private function renderCollection(Collection $collection)
    {
        $files = $collection->getFiles();
        foreach ($files as $i => $file)
        {
            yield $this->renderFile($collection, $file);
        }
    }

    /**
     * Render a file from a collection
     *
     * @param Collection $collection
     * @param $file
     *
     * @return string
     */
    private function renderFile(Collection $collection, $file): string
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

        return new RenderedFile($contents, $dstPath);
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->log = $logger;
    }
}