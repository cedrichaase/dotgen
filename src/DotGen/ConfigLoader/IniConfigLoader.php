<?php
namespace DotGen\ConfigLoader;
use DotGen\TemplatingEngine\Engine;

/**
 * Class IniConfigLoader
 *
 * @package cedrichaase\DotGen\ConfigLoader
 */
class IniConfigLoader implements ConfigLoaderInterface
{
    /**
     * Use the path of the config file as default
     */
    const PATH_DEFAULT = '.';

    /**
     * The identifier of the global config section
     */
    const CONFIG_SECTION_GLOBAL = 'global';

    /**
     * The config key for defining the render output path
     */
    const CONFIG_KEY_OUTPUT_PATH = '__target_dir';

    /**
     * The config key for defining the path to read templates from
     */
    const CONFIG_KEY_INPUT_PATH = '__templates_dir';

    /**
     * Config key for defining which templating engine to use
     */
    const CONFIG_KEY_TEMPLATING_ENGINE = '__engine';

    /**
     * The configuration key for defining the paths to render
     *
     */
    const CONFIG_KEY_FILE_PATHS = '__files';

    /**
     * The entire ini file parsed to an array
     *
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $baseDir;

    /**
     * IniConfigLoader constructor.
     *
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        $path = realpath($filePath);

        $this->config = parse_ini_file($path, true, INI_SCANNER_TYPED);
        $this->baseDir = dirname($path);
    }

    /**
     * Returns the config options for the given config name
     *
     * @param string $name
     *
     * @return array
     */
    public function getConfigOptions($name)
    {
        return array_merge(
            $this->config[self::CONFIG_SECTION_GLOBAL],
            $this->config[$name]
        );
    }

    /**
     * Returns the file paths associated with given collection
     *
     * @param string $collection
     *
     * @return string[]
     */
    public function getFilePathsForCollection($collection)
    {
        return $this->getConfigOptions($collection)[self::CONFIG_KEY_FILE_PATHS];
    }


    /**
     * Returns the names of all file names that are associated with a section
     *
     * @return string[]
     */
    public function getFileNames()
    {
        $config = $this->config;
        unset($config[self::CONFIG_SECTION_GLOBAL]);
        $names = array_keys($config);
        return $names;
    }

    /**
     * Returns the output path for rendered files
     *
     * @return string
     */
    public function getOutputPath()
    {
        $relative = $this->getGlobal(self::CONFIG_KEY_OUTPUT_PATH) ?? self::PATH_DEFAULT;
        return $this->baseDir . DIRECTORY_SEPARATOR . $relative;
    }

    /**
     * Returns the path to read templates from
     *
     * @return string
     */
    public function getInputPath()
    {
        $relative = $this->getGlobal(self::CONFIG_KEY_INPUT_PATH) ?? self::PATH_DEFAULT;
        return $this->baseDir . DIRECTORY_SEPARATOR . $relative;
    }

    /**
     * Returns the name of the templating engine to use
     * 
     * @see Engine
     *
     * @return string
     */
    public function getTemplatingEngine()
    {
        return $this->getGlobal(self::CONFIG_KEY_TEMPLATING_ENGINE) ?? Engine::ENGINE_DEFAULT;
    }

    /**
     * Return a global config variable by given key
     *
     * @param $key
     * @return string
     */
    private function getGlobal($key)
    {
        return $this->config[self::CONFIG_SECTION_GLOBAL][$key] ?? null;
    }
}