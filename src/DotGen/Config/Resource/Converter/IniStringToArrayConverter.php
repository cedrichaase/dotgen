<?php
namespace DotGen\Config\Resource\Converter;

use DotGen\Config\Resource\Converter\IStringToArrayResourceConverter;
use DotGen\Config\Resource\ArrayResource;
use DotGen\File\HandlesFilesystemTrait;
use DotGen\TemplateEngine\Engine;

class IniStringToArrayConverter implements IStringToArrayResourceConverter
{
    use HandlesFilesystemTrait;

    /**
     * The ini string parsed to an array
     *
     * @var array
     */
    private $parsedIni;

    /**
     * The base path to use for all relative paths
     *
     * @var string
     */
    private $basePath;

    /**
     * Convert given string to an ArrayResource
     *
     * @param string $string
     *
     * @return ArrayResource
     */
    public function convert(string $string): ArrayResource
    {
        // parse ini
        $this->parsedIni = self::parseIni($string);

        $collections = self::buildCollectionsArray($this->parsedIni);

        $inputPath = $this->getPathKey(self::KEY_INPUT_PATH);
        $outputPath = $this->getPathKey(self::KEY_OUTPUT_PATH);

        $engine = $this->getTemplatingEngine();

        return new ArrayResource($collections, $inputPath, $outputPath, $engine);
    }

    /**
     * Returns the raw collections to be further processed by ArrayResource
     *
     * @param array $parsedIni
     * @return array
     */
    private static function buildCollectionsArray(array $parsedIni)
    {
        $collections = [];

        // inherit values from global section
        foreach($parsedIni as $name => $content)
        {
            $content = array_merge(
                $parsedIni[self::SECTION_GLOBAL],
                $content
            );

            $collections[$name] = $content;
        }

        unset($collections[self::SECTION_GLOBAL]);
        return $collections;
    }

    /**
     * @param $iniString
     *
     * @return array
     */
    private static function parseIni($iniString)
    {
        $parsedIni = parse_ini_string($iniString, true, INI_SCANNER_TYPED);
        return $parsedIni;
    }

    /**
     * Returns the path to read templates from
     *
     * @param $key
     * @return string
     */
    private function getPathKey(string $key): string
    {
        $path = $this->getGlobal($key) ?? '';

        if(self::isRelativePath($path))
        {
            $path = $this->basePath . DIRECTORY_SEPARATOR . $path;
        }

        if(!$path)
        {
            $path = $this->basePath;
        }

        if(!$path)
        {
            $path = getenv('HOME');
        }

        return realpath($path);
    }

    /**
     * Return a global config variable by given key
     *
     * @param $key
     * @return string
     */
    private function getGlobal($key)
    {
        return $this->parsedIni[self::SECTION_GLOBAL][$key] ?? null;
    }

    /**
     * Returns the name of the templating engine to use
     *
     * @see Engine
     *
     * @return string
     */
    private function getTemplatingEngine(): string
    {
        return $this->getGlobal(self::KEY_TEMPLATING_ENGINE) ?? Engine::ENGINE_DEFAULT;
    }

    /**
     * Sets the base path for all relative paths
     *
     * @param $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }
}