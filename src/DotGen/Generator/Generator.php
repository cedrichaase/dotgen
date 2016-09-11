<?php
namespace DotGen\Generator;

use DotGen\Config\Collection;
use DotGen\Config\IResource;
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
     *
     * @return RenderedFile[]
     */
    public function render()
    {
        $startTime = microtime(true);

        $collections = $this->resource->getCollections();

        $this->log->info('Begin rendering text files', [
            'count' => count($collections),
        ]);

        $renderedFiles = [];
        foreach($collections as $i => $collection)
        {
            $renderedFiles = array_merge($renderedFiles, $this->renderCollection($collection));
        }

        $this->log->info('Done rendering text files', [
            'count' => count($collections),
            'time' => microtime(true) - $startTime,
        ]);

        return $renderedFiles;
    }

    /**
     * Render a collection
     *
     * @param Collection $collection
     *
     * @return RenderedFile[]
     */
    private function renderCollection(Collection $collection)
    {
        $files = $collection->getFiles();
        $renderedFiles = [];

        foreach ($files as $i => $file)
        {
            $renderedFiles[] = $this->renderFile($collection, $file);
        }

        return $renderedFiles;
    }

    /**
     * Render a file from a collection
     *
     * @param Collection $collection
     * @param $file
     *
     * @return RenderedFile
     */
    private function renderFile(Collection $collection, $file): RenderedFile
    {
        $srcPath = $file . '.' . $this->engine->getFileExtension();
        $dstPath = $this->resource->getOutputPath() . DIRECTORY_SEPARATOR . $file;

        $name = $collection->getName();

        $this->log->debug('Rendering text file', [
            'name' => $name,
            'src_path' => $srcPath,
            'dst_path' => $dstPath,
        ]);

        $contents = $this->engine->render(
            $srcPath,
            $collection->getContent()
        );

        $this->log->debug('Rendered text file', [
            'contents' => $contents,
            'dstPath' => $dstPath,
        ]);

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