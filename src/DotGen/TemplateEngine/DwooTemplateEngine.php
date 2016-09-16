<?php
namespace DotGen\TemplateEngine;

/**
 * Class DwooTemplateEngine
 *
 * @package DotGen\TemplateEngine
 */
class DwooTemplateEngine implements ITemplateEngine
{
    const FILE_EXTENSION = 'tpl';

    /**
     * @var string
     */
    private $templateDir;

    /**
     * @var \Dwoo
     */
    private $dwoo;

    /**
     * DwooTemplateEngine constructor.
     *
     * @param string $templateDir
     */
    public function __construct(string $templateDir)
    {
        $this->templateDir = $templateDir;
        $this->dwoo = new \Dwoo();
    }

    /**
     * Render the template at given relative $path
     * with provided $data
     *
     * Return the rendered text string
     *
     * @param string $name
     * @param array $data
     *
     * @return string
     */
    public function render(string $name, array $data): string
    {
        return $this->dwoo->get(
            $this->templateDir . DIRECTORY_SEPARATOR . $name . '.' . self::FILE_EXTENSION,
            $data
        );
    }

    /**
     * Check if the TemplateEngine can render the file located
     * at given path
     *
     * @param string $path
     * @return bool
     */
    public function supports(string $path): bool
    {
        $suffix = '.' . self::FILE_EXTENSION;
        return is_file($this->templateDir . DIRECTORY_SEPARATOR . $path . $suffix);
    }
}