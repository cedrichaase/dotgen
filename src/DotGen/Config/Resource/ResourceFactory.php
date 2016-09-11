<?php
namespace DotGen\Config\Resource;

use DotGen\Config\Resource\Converter\IniStringToArrayConverter;
use DotGen\Config\Resource\Converter\IStringToArrayResourceConverter;

/**
 * Class ResourceFactory
 * 
 * @package DotGen\Config\Resource
 */
class ResourceFactory
{
    /**
     * @var IStringToArrayResourceConverter[]
     */
    private $converters;

    /**
     * ResourceFactory constructor.
     */
    public function __construct()
    {
        $this->registerConverter(new IniStringToArrayConverter());
    }

    /**
     * @param string $path
     *
     * @return IResource
     *
     * @throws UnsupportedFormatException
     */
    public function createFromFile(string $path): IResource
    {
        $content = file_get_contents($path);

        foreach($this->converters as $i => $converter)
        {
            if($converter->supports($content))
            {
                return $this->convertUsing($path, $converter);
            }
        }
        
        throw new UnsupportedFormatException('Do not know how to convert given string to a Resource');
    }

    /**
     * Converts file of given path to an IResource using given $converter
     *
     * @param string $path
     * @param IStringToArrayResourceConverter $converter
     *
     * @return IResource
     */
    private function convertUsing(string $path, IStringToArrayResourceConverter $converter): IResource
    {
        $ini = file_get_contents($path);
        $baseDir = dirname($path);
        $converter->setBasePath($baseDir);

        return $converter->convert($ini);
    }

    /**
     * Registers a new converter with the factory
     * If multiple converters support the same input
     * the most recently added converter will take precedence
     *
     * @param IniStringToArrayConverter $converter
     */
    public function registerConverter(IniStringToArrayConverter $converter)
    {
        array_unshift($this->converters, $converter);
    }
}