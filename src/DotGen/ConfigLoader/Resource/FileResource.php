<?php
namespace DotGen\ConfigLoader\Resource;
use DotGen\File\GuessesFileTypeTrait;
use DotGen\File\HandlesFilesystemTrait;

/**
 * Class FileResource
 *
 * @package DotGen\ConfigLoader\Resource
 */
class FileResource implements ResourceInterface
{
    use HandlesFilesystemTrait;
    use GuessesFileTypeTrait;

    /**
     * @var string
     */
    private $path;

    /**
     * FileResource constructor.
     * 
     * @param string $path
     */
    public function __construct(string $path)
    {
        self::assertFileExists($path);

        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @see FileType
     *
     * @return string
     */
    public function getFileType(): string
    {
        return self::guessFileType($this->path);
    }
}