<?php
namespace DotGen\Config;
use DotGen\File\HandlesFilesystemTrait;
use DotGen\TemplatingEngine\Engine;

/**
 * Class IniResource
 *
 * @package DotGen\Config
 */
class IniResource extends ArrayResource
{
    use HandlesFilesystemTrait;

    /**
     * The identifier of the global config section
     */
    const SECTION_GLOBAL = 'global';

    /**
     * The config key for defining the render output path
     */
    const KEY_OUTPUT_PATH = '__target_dir';

    /**
     * The config key for defining the path to read templates from
     */
    const KEY_INPUT_PATH = '__templates_dir';

    /**
     * Config key for defining which templating engine to use
     */
    const KEY_TEMPLATING_ENGINE = '__engine';

    /**
     * Use ini file path as default path
     */
    const PATH_DEFAULT = '.';

    /**
     * @var array
     */
    private $parsedIni;

    /**
     * The path of the directory the ini file is located in
     *
     * @var string
     */
    private $basePath;

    /**
     * IniResource constructor.
     *
     * @param string $path  The path to create the ini resource from
     */
    public function __construct(string $path)
    {
        // extract path
        $path = realpath($path);
        $this->basePath = dirname($path);

        // parse ini
        $this->parsedIni = self::parseIni($path);

        $collections = self::buildCollectionsArray($this->parsedIni);

        $inputPath = $this->getPathKey(self::KEY_INPUT_PATH);
        $outputPath = $this->getPathKey(self::KEY_OUTPUT_PATH);

        $engine = $this->getTemplatingEngine();
        
        parent::__construct($collections, $inputPath, $outputPath, $engine);
    }


    /**
     * Returns the raw collections to be further processed by ArrayResource
     *
     * @param array $parsedIni
     * @return array
     */
    private static function buildCollectionsArray(array $parsedIni)
    {
        unset($parsedIni[self::SECTION_GLOBAL]);
        return $parsedIni;
    }

    /**
     * @param $path
     *
     * @return array
     */
    private static function parseIni($path)
    {
        $path = realpath($path);
        $parsedIni = parse_ini_file($path, true, INI_SCANNER_TYPED);
        return $parsedIni;
    }

    /**
     * Returns the path to read templates from
     *
     * @param $key
     * @return string
     */
    private function getPathKey($key)
    {
        $configuredPath = $this->getGlobal($key) ?? self::PATH_DEFAULT;

        $path = $configuredPath;

        if(self::isRelativePath($configuredPath))
        {
            $path = $this->basePath . DIRECTORY_SEPARATOR . $configuredPath;
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
    private function getTemplatingEngine()
    {
        return $this->getGlobal(self::KEY_TEMPLATING_ENGINE) ?? Engine::ENGINE_DEFAULT;
    }
}