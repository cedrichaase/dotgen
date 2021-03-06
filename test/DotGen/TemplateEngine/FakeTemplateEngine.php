<?php
namespace DotGen\TemplateEngine;

class FakeTemplateEngine implements ITemplateEngine
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var bool
     */
    private $supports;

    /**
     * Render the template at given relative $path
     * with provided $data
     *
     * Return the rendered text string
     *
     * @param string $path
     * @param array $data
     *
     * @return string
     */
    public function render(string $path, array $data): string
    {
        return $this->content;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function supports(string $path): bool
    {
        return $this->supports;
    }

    /**
     * @param boolean $supports
     */
    public function setSupports($supports)
    {
        $this->supports = $supports;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}