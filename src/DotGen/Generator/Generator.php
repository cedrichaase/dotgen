<?php
namespace DotGen\Generator;

use DotGen\ConfigLoader\ConfigLoaderInterface;
use DotGen\File\HandlesFilesystemTrait;
use DotGen\TemplatingEngine\TemplatingEngineInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class Generator
 *
 * @package cedrichaase\DotGen\Generator
 */
class Generator
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
     * Generator constructor.
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
     * render ALL the files!
     */
    public function render()
    {
        $names = $this->config->getFileNames();

        $this->log->info('Begin rendering text files', [
            'count' => count($names),
            'time' => microtime(true),
        ]);

        foreach($names as $i => $name)
        {
            $paths = $this->config->getFilePathsForCollection($name);
            foreach($paths as $j => $path)
            {
                $this->renderFile($name, $path);
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
    private function renderFile($name, $path)
    {
        $srcPath = $path . '.' . $this->engine->getFileExtension();
        $dstPath = $this->config->getOutputPath() . DIRECTORY_SEPARATOR . $path;

        $this->log->debug('Rendering text file', [
            'name' => $name,
            'src_path' => $srcPath,
            'dst_path' => $dstPath,
        ]);

        self::createPathIfNotExists($dstPath);

        $contents = $this->engine->render(
            $srcPath,
            $this->config->getConfigOptions($name)
        );

        file_put_contents($dstPath, $contents);
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->log = $logger;
    }
}