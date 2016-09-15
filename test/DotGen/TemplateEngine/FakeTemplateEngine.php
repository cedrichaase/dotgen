<?php
namespace DotGen\TemplateEngine;

class FakeITemplateEngine implements ITemplateEngine
{
    const FILE_EXTENSION = 'fake';

    const CONTENT = 'fake rendered content';

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
        return self::CONTENT;
    }

    /**
     * Returns the file extension for files that
     * are written for this templating engine
     *
     * @return string
     */
    public function getFileExtension(): string
    {
        return self::FILE_EXTENSION;
    }
}