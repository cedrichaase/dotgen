<?php
namespace DotGen\Config\Entity;

/**
 * Class Collection
 *
 * A single collection of files along with the variable values
 * that it should be rendered with
 *
 * @codeCoverageIgnore
 *
 * @package DotGen\Config
 */
class Collection
{
    /**
     * The collection's name (id)
     *
     * @var string
     */
    private $name;

    /**
     * Key => value array of values the templates contained in the collection
     * should be rendered with
     *
     * @var array
     */
    private $content;

    /**
     * Absolute paths to files that are part of this collection
     *
     * @var string[]
     */
    private $templates;

    /**
     * Collection constructor.
     *
     * @param $name
     * @param $content
     * @param array $files
     */
    public function __construct(string $name, array $content, array $files = [])
    {
        $this->name = $name;
        $this->content = $content;
        $this->templates = $files;
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
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * @param array $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string[]
     */
    public function getTemplates(): array
    {
        return $this->templates;
    }

    /**
     * @param string[] $templates
     */
    public function setTemplates($templates)
    {
        $this->templates = $templates;
    }
}