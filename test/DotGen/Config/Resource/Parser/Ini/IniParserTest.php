<?php
namespace DotGen\Config\Resource\Parser\Ini;

use DotGen\Traits\UsesTestResourcesTrait;

class IniParserTest extends \PHPUnit_Framework_TestCase
{
    use UsesTestResourcesTrait;

    public function iniDataProvider()
    {
        return [
            [
                'file' => '/base_example.ini',
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
            [
                'file' => '/omitted_config.ini',
                'expected' => [
                    'global' => [],
                    'my_collection' => [
                        '__files' => [
                            '/tmp/a'
                        ],
                    ],
                ]
            ]
        ];
    }

    public function nonIniDataProvider()
    {
        $files = array_merge(
            self::getAllJsonResources(),
            self::getAllYamlResources()
        );

        $files = array_map(function($file) {
            return ['file' => $file];
        }, $files);

        return $files;
    }

    /**
     * @dataProvider iniDataProvider
     *
     * @param $file
     * @param $expected
     */
    public function testParseCorrectIni($file, $expected)
    {
        // arrange
        $parser = new IniParser();
        $string = file_get_contents(self::iniResourcesDir() . $file);

        // act
        $supported = $parser->supports($string);
        $array = $parser->parse($string);
        
        // assert
        self::assertSame($supported, true);
        self::assertSame($array, $expected);
    }

    /**
     * @dataProvider nonIniDataProvider
     *
     * @param $file
     */
    public function testDoNotSupportNonIni($file)
    {
        // arrange
        $parser = new IniParser();
        $string = file_get_contents($file);

        // act
        $supported = $parser->supports($string);

        // assert
        self::assertSame($supported, false);
    }

    /**
     * @dataProvider nonIniDataProvider
     *
     * @param $file
     */
    public function testThrowExceptionOnParseNonIni($file)
    {
        // arrange
        $parser = new IniParser();
        $string = file_get_contents($file);

        // expect
        $this->expectException(IniParserException::class);

        // act
        $parser->parse($string);
    }
}