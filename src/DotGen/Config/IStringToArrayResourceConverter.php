<?php
namespace DotGen\Config;

/**
 * Interface IStringToArrayResourceConverter
 * 
 * @package DotGen\Config
 */
interface IStringToArrayResourceConverter
{
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
     * Convert given string to an ArrayResource
     *
     * @param string $string
     *
     * @return ArrayResource
     */
    public function convert(string $string): ArrayResource;

    /**
     * Sets the base path for all relative paths
     *
     * @param $basePath
     */
    public function setBasePath($basePath);
}