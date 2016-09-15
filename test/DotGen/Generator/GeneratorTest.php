<?php
namespace DotGen\Generator;

use DotGen\Config\Entity\Collection;
use DotGen\Config\Resource\FakeResource;
use DotGen\TemplateEngine\FakeITemplateEngine;

class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testRender()
    {
        // arrange
        $engine = new FakeITemplateEngine();
        $resource = new FakeResource();

        $firstTemplateNames = ['a', 'b', 'c'];
        $secondTemplateNames = ['h', 'j', 'k'];
        $allTemplateNames = array_merge($firstTemplateNames, $secondTemplateNames);

        $firstCollection = new Collection('my_collection', ['a' => 'b'], $firstTemplateNames);
        $secondCollection = new Collection('my_2nd_collection', ['x' => 'y'], $secondTemplateNames);
        $resource->setTemplatePath('/template/path');
        $resource->setCollections([$firstCollection, $secondCollection]);

        $generator = new Generator($resource, $engine);

        // act
        $renderedFiles = $generator->render();

        // assert
        self::assertSame(count($renderedFiles), 6);

        foreach($renderedFiles as $i => $renderedFile)
        {
            self::assertSame($renderedFile->getTemplateName(), $allTemplateNames[$i]);
            self::assertSame($renderedFile->getContents(), FakeITemplateEngine::CONTENT);
        }
    }
}