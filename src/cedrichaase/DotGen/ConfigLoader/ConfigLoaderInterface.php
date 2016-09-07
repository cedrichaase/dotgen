<?php
namespace cedrichaase\DotGen\ConfigLoader;

interface ConfigLoaderInterface
{
    /**
     * Returns the config options for the given config name
     *
     * @param string $name
     *
     * @return array
     */
    public function getConfigOptions($name);

    /**
     * Returns the path of the dotfile for given config name
     *
     * @param string $name
     *
     * @return string
     */
    public function getDotfilePathByName($name);

    /**
     * Returns the names of all dotfiles with available configuration
     *
     * @return string[]
     */
    public function getDotfileNames();
}