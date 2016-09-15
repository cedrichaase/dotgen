<?php
namespace DotGen\TemplateEngine;

/**
 * Class FakeTemplateEngineManager
 *
 * @package DotGen\TemplateEngine
 */
class FakeTemplateEngineManager implements ITemplateEngineManager
{
    /**
     * @var string
     */
    private $rendered;

    /**
     * Render a template while auto detecting the correct
     * TemplateEngine to use
     *
     * @param string $template
     * @param array $content
     *
     * @return string
     * @throws TemplateEngineException
     */
    public function render(string $template, array $content)
    {
        return $this->rendered;
    }

    /**
     * Register a template engine with this Manager.
     * If a template is supported by multiple Engines, the most recently
     * registered one will take precedence.
     *
     * @param ITemplateEngine $engine
     */
    public function registerEngine(ITemplateEngine $engine)
    {
        // no-op
    }

    /**
     * Exclusively register one template engine
     *
     * @param ITemplateEngine $engine
     */
    public function registerExclusive(ITemplateEngine $engine)
    {
        // no-op
    }

    /**
     * @return string
     */
    public function getRendered()
    {
        return $this->rendered;
    }

    /**
     * @param string $rendered
     */
    public function setRendered($rendered)
    {
        $this->rendered = $rendered;
    }
}