<?php
namespace DotGen\Generator;

/**
 * Class RenderedFile
 *
 * @codeCoverageIgnore
 *
 * @package DotGen\Generator
 */
class RenderedFile
{
    /**
     * @var string
     */
    private $contents;

    /**
     * @var string
     */
    private $templateName;

    /**
     * RenderedFile constructor.
     *
     * @param string $contents
     * @param string $templateName
     */
    public function __construct($contents, $templateName)
    {
        $this->contents = $contents;
        $this->templateName = $templateName;
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }

    /**
     * @param string $templateName
     */
    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;
    }

    /**
     * @return string
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param string $contents
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
    }
}