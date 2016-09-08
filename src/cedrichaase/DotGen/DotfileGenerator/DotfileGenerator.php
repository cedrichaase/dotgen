<?php
namespace cedrichaase\DotGen\DotfileGenerator;

use cedrichaase\DotGen\ConfigLoader\ConfigLoaderInterface;
use cedrichaase\DotGen\File\HandlesFilesystemTrait;
use cedrichaase\DotGen\TemplatingEngine\TemplatingEngineInterface;

/**
 * Class DotfileGenerator
 *
 * @package cedrichaase\DotGen\DotfileGenerator
 */
class DotfileGenerator
{
    use HandlesFilesystemTrait;
    
    /**
     * @var TemplatingEngineInterface
     */
    private $engine;

    /**
     * @var ConfigLoaderInterface
     */
    private $config;

    /**
     * DotfileGenerator constructor.
     *
     * @param ConfigLoaderInterface $loader
     * @param TemplatingEngineInterface $engine
     */
    public function __construct(ConfigLoaderInterface $loader, TemplatingEngineInterface $engine)
    {
        $this->config = $loader;
        $this->engine = $engine;
    }

    /**
     * render ALL the dotfiles!
     */
    public function renderDotfiles()
    {
        $names = $this->config->getDotfileNames();
        foreach($names as $i => $name)
        {
            $paths = $this->config->getDotfilePathsByName($name);
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
        $srcPath = $path . '.' . $this->engine->getFileExtension();
        $dstPath = $this->config->getOutputPath() . DIRECTORY_SEPARATOR . $path;

        self::createPathIfNotExists($dstPath);

        $dotfile = $this->engine->render(
            $srcPath,
            $this->config->getConfigOptions($name)
        );

        file_put_contents($dstPath, $dotfile);
    }
}