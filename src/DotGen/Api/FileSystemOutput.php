<?php
namespace DotGen\Api;

use DotGen\Config\Resource\Converter\BaseDirTemplateMapper;
use DotGen\Generator\Generator;
use DotGen\TemplateEngine\TemplateEngineManager;
use DotGen\TemplateEngine\TwigTemplateEngine;
use DotGen\Twig\PhpColorsExtension;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class FileSystemOutput
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    public function process($templateDir, $outputDir, $resource)
    {
        // begin render process
        $engine = new TemplateEngineManager($templateDir);

        $twig = new TwigTemplateEngine($templateDir);
        $twig->addExtension(new PhpColorsExtension());
        $engine->registerExclusive($twig);

        $generator = new Generator($resource, $engine);
        $generator->setLogger($this->logger);

        $renderedFiles = $generator->render();

        $mapper = new BaseDirTemplateMapper($outputDir);
        foreach($renderedFiles as $renderedFile)
        {
            $renderedFilePath = $mapper->map($renderedFile->getTemplateName());
            file_put_contents($renderedFilePath, $renderedFile->getContents());
        }
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}