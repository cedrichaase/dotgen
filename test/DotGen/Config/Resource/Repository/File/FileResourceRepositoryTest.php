<?php
namespace DotGen\Config\Resource\Repository\File;

use DotGen\Config\Resource\IResource;
use DotGen\Traits\UsesTestResourcesTrait;

class FileResourceRepositoryTest extends \PHPUnit_Framework_TestCase
{
    use UsesTestResourcesTrait;

    public function testGetResource()
    {
        // arrange
        $repo = new FileResourceRepository(self::yamlResourceDir());
                
        // act
        $resource = $repo->findResourceByName('base_example');
        
        // assert
        self::assertInstanceOf(IResource::class, $resource);
        self::assertSame(count($resource->getCollections()), 1);
        self::assertSame($resource->getName(), 'base_example');
    }

    public function testGetResourceCached()
    {
        // arrange
        $repo = new FileResourceRepository(self::yamlResourceDir());

        // act
        $resource = $repo->findResourceByName('base_example');
        $resource2 = $repo->findResourceByName('base_example');

        // assert
        self::assertSame($resource, $resource2);
    }

    public function testNoFilesInPath()
    {
        // arrange
        $repo = new FileResourceRepository(self::resourcesDir() . '/none');

        // expect
        $this->expectException(FileResourceRepositoryException::class);

        // act
        $resource = $repo->findResourceByName('my_resource');
    }

    public function testResourceNotFound()
    {
        // arrange
        $repo = new FileResourceRepository(self::yamlResourceDir());

        // expect
        $this->expectException(FileResourceRepositoryException::class);

        // act
        $resource = $repo->findResourceByName('xxyyxx');
    }
}