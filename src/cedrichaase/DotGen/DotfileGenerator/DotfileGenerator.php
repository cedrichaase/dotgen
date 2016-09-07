<?php
namespace cedrichaase\DotGen\DotfileGenerator;

use cedrichaase\DotGen\ConfigLoader\ConfigLoaderInterface;
use cedrichaase\DotGen\ConfigLoader\IniConfigLoader;
use cedrichaase\DotGen\TemplatingEngine\TwigTemplatingEngine;

/**
 * Class DotfileGenerator
 *
 * @package cedrichaase\DotGen\DotfileGenerator
 */
class DotfileGenerator
{
    const BASE_DIR = __DIR__ . '/../../../..';

    const DOTFILES_DIR = self::BASE_DIR . '/dotfiles';

    const CONFIG_PATH = self::BASE_DIR . '/config/config.ini';

    /**
     * @var TwigTemplatingEngine
     */
    private $twig;

    /**
     * @var ConfigLoaderInterface
     */
    private $config;

    /**
     * render ALL the dotfiles!
     */
    public function renderDotfiles()
    {
        $names = $this->config()->getDotfileNames();
        foreach($names as $i => $name)
        {
            $paths = $this->config()->getDotfilePathsByName($name);
            foreach($paths as $j => $path)
            {
                $this->renderDotfile($name, $path);
            }
        }
    }

    /**
     * @param string $name
     * @param string $path
     */
    private function renderDotfile($name, $path)
    {
        $srcPath = $path . '.twig';
        $dstPath = $this->config()->getOutputPath() . DIRECTORY_SEPARATOR . $path;

        # region create dir structure if not exists
        $dstDir = explode(DIRECTORY_SEPARATOR, $dstPath);
        array_pop($dstDir);
        $dstDir = implode(DIRECTORY_SEPARATOR, $dstDir);

        if(!is_dir($dstDir))
        {
            mkdir($dstDir, 0777, true);
        }
        # endregion

        $dotfile = $this->twig()->render(
            $srcPath,
            $this->config()->getConfigOptions($name)
        );

        file_put_contents($dstPath, $dotfile);
    }

    /**
     * @return ConfigLoaderInterface
     */
    private function config()
    {
        if(!$this->config)
        {
            $this->config = new IniConfigLoader(self::CONFIG_PATH);
        }

        return $this->config;
    }

    /**
     * @return TwigTemplatingEngine
     */
    private function twig()
    {
        if(!$this->twig)
        {
            $this->twig = new TwigTemplatingEngine();
        }
        
        return $this->twig;
    }
}