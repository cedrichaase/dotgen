<?php
namespace DotGen\Config\Resource\Parser\Json;

use DotGen\Traits\UsesTestResourcesTrait;

class JsonParserTest extends \PHPUnit_Framework_TestCase
{
    use UsesTestResourcesTrait;

    public function jsonDataProvider()
    {
        return [
            [
                'file' => '/base_example.json',
                'expected' => [
                    'global' => [
                        '__templates_dir' => '/tmp/a',
                        '__target_dir' => '/tmp/b',
                        '__engine' => 'twig',
                    ],
                    'my_collection' => [
                        '__files' => [
                            'a.txt',
                            'b.txt',
                        ],
                        'some_var' => 'some_value',
                        'some_bool' => true,
                        'some_number' => 5,
                    ]
                ],
            ],
        ];
    }

    /**
     * @dataProvider jsonDataProvider
     *
     * @param $file
     * @param $expected
     */
    public function testParseCorrectJson($file, $expected)
    {
        // arrange
        $parser = new JsonParser();
        $string = file_get_contents(self::jsonResourcesDir() . $file);

        // act
        $supported = $parser->supports($string);
        $array = $parser->parse($string);

        // assert
        self::assertSame($supported, true);
        self::assertSame($array, $expected);
    }

    public function testParseJsonWithSyntaxErrors()
    {
        // arrange
        $parser = new JsonParser();
        $string = file_get_contents(self::jsonResourcesDir() . '/incorrect_syntax.json');

        // expect
        $this->expectException(JsonParserException::class);

        // act
        $supported = $parser->supports($string);
        $array = $parser->parse($string);

        // assert
        assertSame($supported, false);
        assertSame((bool) $array, false);
    }
}