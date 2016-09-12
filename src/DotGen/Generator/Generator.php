<?php
namespace DotGen\Generator;

use DotGen\Config\Entity\Collection;
use DotGen\Config\Resource\IResource;
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

        $this->log->info('Begin rendering templates', [
            'count' => count($collections),
        ]);

        $renderedFiles = [];
        foreach($collections as $i => $collection)
        {
            $renderedFiles = array_merge($renderedFiles, $this->renderCollection($collection));
        }

        $this->log->info('Done rendering', [
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
        $templates = $collection->getTemplates();
        $renderedFiles = [];

        foreach ($templates as $i => $template)
        {
            $renderedFiles[] = $this->renderTemplate($collection, $template);
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
    private function renderTemplate(Collection $collection, $templateName): RenderedFile
    {
        $collectionName = $collection->getName();

        $this->log->debug('Rendering template', [
            'collection' => $collectionName,
            'template' => $templateName,
        ]);

        $contents = $this->engine->render(
            $this->resource->getTemplatePath($templateName),
            $collection->getContent()
        );

        $this->log->debug('Rendered text file', [
            'contents' => $contents,
            'collection' => $collectionName,
        ]);

        return new RenderedFile($contents, $templateName);
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->log = $logger;
    }
}