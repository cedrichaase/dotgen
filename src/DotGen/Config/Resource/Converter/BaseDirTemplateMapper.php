<?php
namespace DotGen\Config\Resource\Converter;

/**
 * Class BaseDirTemplateMapper
 *
 * In case you need to prepend a base directory
 * to the template name
 *
 * @package DotGen\Config\Resource\Converter
 */
class BaseDirTemplateMapper implements ITemplateMapper
{
    /**
     * @var string
     */
    private $baseDir;

    /**
     * BaseDirTemplateMapper constructor.
     *
     * @param string $baseDir
     */
    public function __construct(string $baseDir)
    {
        $this->baseDir = $baseDir;
    }

    /**
     * Maps a template's name to its relative file path
     *
     * @param $templateName
     * @return string $templatePath
     */
    public function map($templateName): string
    {
        return $this->baseDir . $templateName;
    }
}