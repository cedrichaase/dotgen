<?php
namespace cedrichaase\DotGen\ConfigLoader;

/**
 * Class IniConfigLoader
 *
 * @package cedrichaase\DotGen\ConfigLoader
 */
class IniConfigLoader implements ConfigLoaderInterface
{
    /**
     * The identifier of the global config section
     */
    const CONFIG_SECTION_GLOBAL = 'global';

    /**
     * The config key for defining the render output path
     */
    const CONFIG_KEY_OUTPUT_PATH = 'deploy_path';

    /**
     * The configuration key for defining the dotfile path
     */
    const CONFIG_KEY_DOTFILE_PATH = 'config_file_path';

    /**
     * The entire ini file parsed to an array
     *
     * @var array
     */
    private $config;

    /**
     * IniConfigLoader constructor.
     *
     * @param string $configFilePath
     */
    public function __construct($configFilePath)
    {
        $this->config = parse_ini_file($configFilePath, true);
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
            $this->config[$name],
            $this->config[self::CONFIG_SECTION_GLOBAL]
        );
    }

    /**
     * Returns the path of the dotfile for given config name
     *
     * @param string $name
     *
     * @return string
     */
    public function getDotfilePathByName($name)
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
        return $this->config[self::CONFIG_SECTION_GLOBAL][self::CONFIG_KEY_OUTPUT_PATH];
    }
}