<?php
namespace DotGen\Config\Resource;
use DotGen\Config\Entity\Collection;
use DotGen\Config\Resource\IResource;

/**
 * Class FakeResource
 *
 * Fake IResource for testing
 *
 * @package DotGen\Config
 */
class FakeResource implements IResource
{
    /**
     * @var Collection[]
     */
    private $collections;

    /**
     * @var string
     */
    private $templatePath;

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
        return $this->templatePath;
    }

    /**
     * @param \DotGen\Config\Entity\Collection[] $collections
     */
    public function setCollections($collections)
    {
        $this->collections = $collections;
    }

    /**
     * @param string $templatePath
     */
    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;
    }
}