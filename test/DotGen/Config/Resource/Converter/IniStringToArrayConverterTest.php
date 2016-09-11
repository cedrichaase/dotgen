<?php
namespace DotGen\Config\Resource\Converter;

use DotGen\Config\Resource\ArrayResource;
use DotGen\Config\Resource\Converter\IniStringToArrayConverter;

class IniStringToArrayConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testConvertHappyPath()
    {
        // arrange
        $converter = new IniStringToArrayConverter();
        $ini = file_get_contents(self::resourceDir() . '/base_example.ini');
        if(!is_dir('/tmp/a')) mkdir('/tmp/a', 0777, true);
        if(!is_dir('/tmp/b')) mkdir('/tmp/b', 0777, true);
        
        // act
        $resource = $converter->convert($ini);
        
        // assert
        self::assertSame($resource->getInputPath(), '/tmp/a');
        self::assertSame($resource->getOutputPath(), '/tmp/b');
        self::assertSame(count($resource->getCollections()), 1);
        
        $collection = $resource->getCollections()[0];
        self::assertSame($collection->getName(), 'my_collection');

        $files = $collection->getFiles();
        self::assertSame($files[0], 'a.txt');
        self::assertSame($files[1], 'b.txt');

        $content = $collection->getContent();
        self::assertSame($content['some_var'], 'some_value');
        self::assertSame($content['some_bool'], true);
        self::assertSame($content['some_number'], 5);
        self::assertArrayNotHasKey(ArrayResource::COLLECTION_KEY_FILES, $content);
    }

    public function testConvertWithOmittedConfig()
    {
        // arrange
        $converter = new IniStringToArrayConverter();
        $ini = file_get_contents(self::resourceDir() . '/omitted_config.ini');

        // act
        $resource = $converter->convert($ini);

        // assert
        self::assertSame($resource->getInputPath(), getenv('HOME'));
        self::assertSame($resource->getOutputPath(), getenv('HOME'));
    }
    
    public function testAccessGlobalVariable()
    {
        // arrange
        $converter = new IniStringToArrayConverter();
        $ini = file_get_contents(self::resourceDir() . '/access_global_variable.ini');

        // act
        $resource = $converter->convert($ini);
        
        // assert
        self::assertSame(count($resource->getCollections()), 1);

        $collection = $resource->getCollections()[0];
        self::assertSame($collection->getName(), 'my_collection');

        $content = $collection->getContent();
        self::assertSame($content['some_var'], 'a');
    }

    public function testOverrideGlobalVariable()
    {
        // arrange
        $converter = new IniStringToArrayConverter();
        $ini = file_get_contents(self::resourceDir() . '/override_global_variable.ini');

        // act
        $resource = $converter->convert($ini);

        // assert
        $collections = $resource->getCollections();
        self::assertSame(count($collections), 2);

        $firstCollection = $collections[0];
        self::assertSame($firstCollection->getName(), 'my_collection');
        $firstContent = $firstCollection->getContent();
        self::assertSame($firstContent['some_var'], 'a');

        $secondCollection = $collections[1];
        self::assertSame($secondCollection->getName(), 'my_second_collection');
        $secondContent = $secondCollection->getContent();
        self::assertSame($secondContent['some_var'], 'b');
    }

    private static function resourceDir()
    {
        return realpath(__DIR__ . '/../../../../resource/ini');
    }
}