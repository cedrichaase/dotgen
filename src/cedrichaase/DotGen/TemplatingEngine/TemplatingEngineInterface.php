<?php
namespace cedrichaase\DotGen\TemplatingEngine;

/**
 * Interface TemplatingEngineInterface
 *
 * @package cedrichaase\DotGen\TemplatingEngine
 */
interface TemplatingEngineInterface
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
     * Returns the file extension for files that
     * are written for this templating engine
     *
     * @return string
     */
    public function getFileExtension(): string;
}