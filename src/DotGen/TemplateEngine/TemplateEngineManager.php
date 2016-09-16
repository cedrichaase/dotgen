<?php
namespace DotGen\TemplateEngine;

/**
 * Class TemplateEngineManager
 *
 * @package DotGen\TemplateEngine
 */
class TemplateEngineManager implements ITemplateEngineManager
{
    /**
     * @var ITemplateEngine[]
     */
    private $engines;

    /**
     * TemplateEngineManager constructor.
     *
     * @param string $templateDir
     */
    public function __construct(string $templateDir)
    {
        $this->engines = [];
        $this->registerEngine(new DwooTemplateEngine($templateDir));
        $this->registerEngine(new TwigTemplateEngine($templateDir));
    }

    /**
     * Render a template
     *
     * @param string $template
     * @param array $content
     *
     * @return string
     * @throws TemplateEngineException
     */
    public function render(string $template, array $content)
    {
        foreach($this->engines as $engine)
        {
            if($engine->supports($template))
            {
                return $engine->render($template, $content);
            }
        }

        throw new TemplateEngineException('Do not know how to render template ' . $template);
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
        array_unshift($this->engines, $engine);
    }

    /**
     * Exclusively register one template engine
     *
     * @param ITemplateEngine $engine
     */
    public function registerExclusive(ITemplateEngine $engine)
    {
        unset($this->engines);
        $this->engines = [];
        $this->registerEngine($engine);
    }
}