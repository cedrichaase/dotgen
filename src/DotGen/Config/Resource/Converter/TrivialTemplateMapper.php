<?php
namespace DotGen\Config\Resource\Converter;

/**
 * Class TrivialTemplateMapper
 *
 * For the cases in which you can use the template's
 * relative file path as its name
 *
 * @package DotGen\Config\Resource\Converter
 */
class TrivialTemplateMapper implements ITemplateMapper
{
    /**
     * Maps a template's name to its relative file path
     *
     * @param $templateName
     * @return string $templatePath
     */
    public function map($templateName): string
    {
        return $templateName;
    }
}