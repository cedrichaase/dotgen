<?php
namespace DotGen\TemplateEngine;

/**
 * Interface TemplatingEngineInterface
 *
 * @package cedrichaase\DotGen\TemplateEngine
 */
interface ITemplateEngine
{
    /**
     * Render the template at given relative $path
     * with provided $data
     *
     * Return the rendered text string
     *
     * @param string    $path
     * @param array     $data
     *
     * @return string
     */
    public function render(string $path, array $data): string;

    /**
     * Check if the TemplateEngine can render the file located
     * at given path
     *
     * @param string $path
     * @return bool
     */
    public function supports(string $path): bool;
}