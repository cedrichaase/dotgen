<?php
namespace DotGen\Config\Resource;

use DotGen\Config\Entity\Collection;
use DotGen\Config\Resource\Converter\ITemplateMapper;
use DotGen\Config\Resource\Converter\TrivialTemplateMapper;

/**
 * Class ConfigResource
 *
 * @codeCoverageIgnore
 *
 * @package DotGen\Config\Resource
 */
class ConfigResource implements IResource
{
    /**
     * @var Collection[]
     */
    private $collections;

    /**
     * @var ITemplateMapper
     */
    private $templateMapper;

    /**
     * ConfigResource constructor.
     */
    public function __construct()
    {
        // by default, use the template's file path as its name
        $this->templateMapper = new TrivialTemplateMapper();
    }

    /**
     * Return the collections managed by this resource
     *
     * @return Collection[]
     */
    public function getCollections()
    {
        return $this->collections;
    }

    /**
     * Returns relative path of the template file
     * for given template name
     *
     * @param $template
     * @return string
     */
    public function getTemplatePath($template)
    {
        return $this->templateMapper->map($template);
    }

    /**
     * @param Collection[] $collections
     */
    public function setCollections(array $collections)
    {
        $this->collections = $collections;
    }
}