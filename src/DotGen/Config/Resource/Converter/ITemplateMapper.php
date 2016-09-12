<?php
namespace DotGen\Config\Resource\Converter;

/**
 * Interface ITemplateMapper
 *
 * @package DotGen\Config\Resource\Converter
 */
interface ITemplateMapper
{
    /**
     * Maps a template's name to its relative file path
     *
     * @param $templateName
     * @return string $templatePath
     */
    public function map($templateName): string;
}