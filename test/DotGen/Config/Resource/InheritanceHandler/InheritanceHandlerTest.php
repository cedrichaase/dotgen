<?php
namespace DotGen\Config\Resource\InheritanceHandler;

use DotGen\Config\Entity\Collection;
use DotGen\Config\Resource\FakeResource;
use DotGen\Config\Resource\IResource;
use DotGen\Config\Resource\Repository\FakeResourceRepository;

class InheritanceHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testExtend()
    {
        // arrange

        // create child
        $child = new FakeResource();
        $child->setName('child');
        $child->setExtends('parent');

        $childContent = [
            'a' => 1,
            'b' => 2,
            'c' => 3,
        ];
        $childFiles = ['x', 'y', 'z'];
        $childCollections['my_collection'] = new Collection('my_collection', $childContent, $childFiles);
        $child->setCollections($childCollections);

        // create parent
        $parent = new FakeResource();
        $parent->setName('parent');
        $parent->setExtends('grandparent');

        $parentContent = [
            'a' => 2,
            'x' => 1,
            'y' => 3,
        ];
        $parentFiles = ['a'];
        $parentCollections['my_collection'] = new Collection('my_collection', $parentContent, $parentFiles);
        $parent->setCollections($parentCollections);

        // create grandparent
        $grandparent = new FakeResource();
        $grandparent->setName('grandparent');
        $grandparent->setExtends('');

        $grandparentContent = [
            'c' => 1,
            'y' => 2,
            'z' => 3,
        ];
        $grandparentFiles = ['b'];
        $grandparentCollections['my_collection'] = new Collection('my_collection', $grandparentContent, $grandparentFiles);
        $grandparent->setCollections($grandparentCollections);

        // create repo
        $repo = new FakeResourceRepository();
        $repo->pushResource($child);
        $repo->pushResource($parent);
        $repo->pushResource($grandparent);

        // setup inheritance handler
        $inheritor = new InheritanceHandler();
        $inheritor->registerRepository($repo);

        // act
        $extended = $inheritor->extend($child);

        // assert
        $content = $extended->getCollections()['my_collection']->getContent();

        self::assertInstanceOf(IResource::class, $extended);
        self::assertSame($extended->getName(), $child->getName());
        self::assertSame(count($extended->getCollections()), 1);

        self::assertSame($content['a'], $childContent['a']);
        self::assertSame($content['b'], $childContent['b']);
        self::assertSame($content['c'], $childContent['c']);
        self::assertSame($content['x'], $parentContent['x']);
        self::assertSame($content['y'], $parentContent['y']);
        self::assertSame($content['z'], $grandparentContent['z']);

        $templates = $extended->getCollections()['my_collection']->getTemplates();
        self::assertSame($templates, ['b', 'a', 'x', 'y', 'z']);

        $allTemplates = array_merge($childFiles, $parentFiles, $grandparentFiles);
        self::assertSame(count($allTemplates), count($templates));
        foreach($allTemplates as $template)
        {
            self::assertContains($template, $templates);
        }
    }
}