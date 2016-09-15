<?php
namespace DotGen\TemplateEngine;

/**
 * Interface ITemplateEngineManager
 * 
 * @package DotGen\TemplateEngine
 */
interface ITemplateEngineManager
{
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
    public function render(string $template, array $content);

    /**
     * Register a template engine with this Manager.
     * If a template is supported by multiple Engines, the most recently
     * registered one will take precedence.
     *
     * @param ITemplateEngine $engine
     */
    public function registerEngine(ITemplateEngine $engine);

    /**
     * Exclusively register one template engine
     *
     * @param ITemplateEngine $engine
     */
    public function registerExclusive(ITemplateEngine $engine);
}