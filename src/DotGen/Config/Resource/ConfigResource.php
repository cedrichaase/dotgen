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
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $extends;

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
        $this->extends = '';

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
    public function getTemplatePath($template): string
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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Return the name of the resource this
     * resource extends
     *
     * @return string
     */
    public function getExtends(): string
    {
        return $this->extends;
    }

    /**
     * @param string $extends
     */
    public function setExtends(string $extends)
    {
        $this->extends = $extends;
    }
}