<?php
namespace DotGen\Config\Resource\Converter;

class BaseDirTemplateMapperTest extends \PHPUnit_Framework_TestCase
{
    public function mapDataProvider()
    {
        return [
            ['a'],
            ['b'],
            ['c'],
            ['x'],
            ['y'],
            ['z']
        ];
    }

    /**
     * @dataProvider mapDataProvider
     *
     * @param $input
     */
    public function testMap($input)
    {
        // arrange
        $basePath = '/tmp';
        $mapper = new BaseDirTemplateMapper($basePath);

        // act
        $output = $mapper->map($input);

        // assert
        self::assertSame($basePath . DIRECTORY_SEPARATOR . $input, $output);
    }
}