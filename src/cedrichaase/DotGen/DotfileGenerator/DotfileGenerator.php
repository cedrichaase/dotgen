<?php
namespace cedrichaase\DotGen\DotfileGenerator;

use cedrichaase\DotGen\ConfigLoader\ConfigLoaderInterface;
use cedrichaase\DotGen\File\HandlesFilesystemTrait;
use cedrichaase\DotGen\TemplatingEngine\TemplatingEngineInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

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
     * @var LoggerInterface
     */
    private $log;

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
        $this->log = new NullLogger();
    }

    /**
     * render ALL the dotfiles!
     */
    public function renderDotfiles()
    {
        $names = $this->config->getDotfileNames();

        $this->log->info('Begin rendering text files', [
            'count' => count($names),
            'time' => microtime(true),
        ]);

        foreach($names as $i => $name)
        {
            $paths = $this->config->getDotfilePathsByName($name);
            foreach($paths as $j => $path)
            {
                $this->renderDotfile($name, $path);
            }
        }

        $this->log->info('Done rendering text files', [
            'count' => count($names),
            'time' => microtime(true),
        ]);
    }

    /**
     * @param string $name
     * @param string $path
     */
    private function renderDotfile($name, $path)
    {
        $srcPath = $path . '.' . $this->engine->getFileExtension();
        $dstPath = $this->config->getOutputPath() . DIRECTORY_SEPARATOR . $path;

        $this->log->debug('Rendering text file', [
            'name' => $name,
            'src_path' => $srcPath,
            'dst_path' => $dstPath,
        ]);

        self::createPathIfNotExists($dstPath);

        $dotfile = $this->engine->render(
            $srcPath,
            $this->config->getConfigOptions($name)
        );

        file_put_contents($dstPath, $dotfile);
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->log = $logger;
    }
}