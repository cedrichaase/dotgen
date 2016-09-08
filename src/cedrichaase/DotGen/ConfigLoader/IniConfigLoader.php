<?php
namespace cedrichaase\DotGen\ConfigLoader;
use cedrichaase\DotGen\File\HandlesFilesystemTrait;

/**
 * Class IniConfigLoader
 *
 * @package cedrichaase\DotGen\ConfigLoader
 */
class IniConfigLoader implements ConfigLoaderInterface
{
    use HandlesFilesystemTrait;

    /**
     * The identifier of the global config section
     */
    const CONFIG_SECTION_GLOBAL = 'global';

    /**
     * The config key for defining the render output path
     */
    const CONFIG_KEY_OUTPUT_PATH = 'output_dir';

    /**
     * The config key for defining the path to read templates from
     */
    const CONFIG_KEY_INPUT_PATH = 'template_dir';

    /**
     * Config key for defining which templating engine to use
     */
    const CONFIG_KEY_TEMPLATING_ENGINE = 'template_engine';

    /**
     * The configuration key for defining the dotfile path
     */
    const CONFIG_KEY_DOTFILE_PATH = 'config_file_paths';

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
     * @param string $configFilePath
     */
    public function __construct($configFilePath)
    {
        $path = realpath($configFilePath);
        self::assertFileExists($path);

        $this->config = parse_ini_file($configFilePath, true);
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
     * Returns the path of the dotfile for given config name
     *
     * @param string $name
     *
     * @return string[]
     */
    public function getDotfilePathsByName($name)
    {
        return $this->getConfigOptions($name)[self::CONFIG_KEY_DOTFILE_PATH];
    }

    /**
     * Returns the names of all dotfiles with available configuration
     *
     * @return string[]
     */
    public function getDotfileNames()
    {
        $config = $this->config;
        unset($config[self::CONFIG_SECTION_GLOBAL]);
        $names = array_keys($config);
        return $names;
    }

    /**
     * Returns the output path for rendered dotfiles
     *
     * @return string
     */
    public function getOutputPath()
    {
        $relative = $this->getGlobal(self::CONFIG_KEY_OUTPUT_PATH);
        return $this->baseDir . DIRECTORY_SEPARATOR . $relative;
    }

    /**
     * Returns the path to read templates from
     *
     * @return string
     */
    public function getInputPath()
    {
        $relative = $this->getGlobal(self::CONFIG_KEY_INPUT_PATH);
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
        return $this->getGlobal(self::CONFIG_KEY_TEMPLATING_ENGINE);
    }

    /**
     * Return a global config variable by given key
     *
     * @param $key
     * @return string
     */
    private function getGlobal($key)
    {
        return $this->config[self::CONFIG_SECTION_GLOBAL][$key];
    }
}