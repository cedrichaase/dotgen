<?php
namespace DotGen\Config\Resource\Converter;

class ArrayToResourceConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testConvertBaseExample()
    {
        // arrange
        $converter = new ArrayToResourceConverter();

        $array = [
            ArrayToResourceConverter::COLLECTION_NAME_GLOBAL => [
                'some_global_var' => 'x',
            ],
            'my_collection' => [
                ArrayToResourceConverter::COLLECTION_KEY_TEMPLATES => [
                    'first-template-name',
                    'second-template-name',
                ],
                'a_string' => 'string_content',
                'a_number' => 5,
                'a_bool' => true,
                'an_array' => [
                    'key1' => true,
                    'key2' => 'value_two',
                    'key3' => 3,
                    'key_4' => [
                        1,
                        2,
                        3,
                        5,
                        7,
                        11,
                        13,
                    ],
                ],
            ],
            'another_collection' => [
                ArrayToResourceConverter::COLLECTION_KEY_TEMPLATES => [
                    'third-template-name',
                    'fourth-template-name',
                ],
                'some_global_var' => 'y',
                'some_local_var' => 'z',
            ],
        ];

        // act
        $resource = $converter->convert($array);
        $collections = $resource->getCollections();

        // assert
        self::assertSame(count($collections), 2);

        $myCollection = $collections[0];
        self::assertSame($myCollection->getName(), 'my_collection');
        self::assertSame($myCollection->getContent(), [
            // some_global_var gets added
            'some_global_var' => 'x',
            'a_string' => 'string_content',
            'a_number' => 5,
            'a_bool' => true,
            'an_array' => [
                'key1' => true,
                'key2' => 'value_two',
                'key3' => 3,
                'key_4' => [
                    1,
                    2,
                    3,
                    5,
                    7,
                    11,
                    13,
                ],
            ],
        ]);
        self::assertSame($myCollection->getTemplates(), [
            'first-template-name',
            'second-template-name',
        ]);

        $anotherCollection = $collections[1];
        self::assertSame($anotherCollection->getName(), 'another_collection');
        self::assertSame($anotherCollection->getContent(), [
            'some_global_var' => 'y',
            'some_local_var' => 'z',
        ]);
        self::assertSame($anotherCollection->getTemplates(), [
            'third-template-name',
            'fourth-template-name',
        ]);
    }

    public function testDropEmptyCollections()
    {
        // arrange
        $converter = new ArrayToResourceConverter();

        $array = [
            'my_collection' => [
                'a' => 'b',
            ],
        ];

        // act
        $resource = $converter->convert($array);

        // assert
        self::assertSame(count($resource->getCollections()), 0);
    }
}