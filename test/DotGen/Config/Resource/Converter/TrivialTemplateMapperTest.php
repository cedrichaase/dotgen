<?php
namespace DotGen\Config\Resource\Converter;

class TrivialTemplateMapperTest extends \PHPUnit_Framework_TestCase
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
     * @param $string
     */
    public function testMap($string)
    {
        // arrange
        $mapper = new TrivialTemplateMapper();

        // act
        $mapped = $mapper->map($string);

        // assert
        self::assertSame($string, $mapped);
    }
}