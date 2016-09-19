<?php
namespace DotGen\Config\Resource\Parser\Yaml;

use DotGen\Traits\UsesTestResourcesTrait;

/**
 * Class YamlParserTest
 *
 * @package DotGen\Config\Resource\Parser\Yaml
 */
class YamlParserTest extends \PHPUnit_Framework_TestCase
{
    use UsesTestResourcesTrait;

    public function yamlDataProvider()
    {
        return [
            [
                'file' => '/base_example.yml',
                'expected' => [
                    'global' => [
                        '__name' => 'base_example'
                    ],
                    'my_collection' => [
                        '__templates' => [
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

    public function nonYamlDataProvider()
    {
        $files = array_merge(
            self::getAllJsonResources(),
            self::getAllIniResources()
        );

        $files = array_map(function($file) {
            return ['file' => $file];
        }, $files);

        return $files;
    }

    /**
     * @dataProvider yamlDataProvider
     *
     * @param $file
     * @param $expected
     */
    public function testParseCorrectYaml($file, $expected)
    {
        // arrange
        $parser = new YamlParser();
        $string = file_get_contents(self::yamlResourceDir() . $file);

        // act
        $supported = $parser->supports($string);
        $array = $parser->parse($string);

        // assert
        self::assertSame($supported, true);
        self::assertSame($array, $expected);
    }

    /**
     * @dataProvider nonYamlDataProvider
     *
     * @param $file
     */
    public function testDoNotSupportNonYaml($file)
    {
        // arrange
        $parser = new YamlParser();
        $string = file_get_contents($file);

        // act
        $supported = $parser->supports($string);

        // assert
        self::assertSame($supported, false);
    }

    /**
     * @dataProvider nonYamlDataProvider
     *
     * @param $file
     */
    public function testThrowExceptionOnParseNonYaml($file)
    {
        // arrange
        $parser = new YamlParser();
        $string = file_get_contents($file);

        // expect
        $this->expectException(YamlParserException::class);

        // act
        $parser->parse($string);
    }
}