<?php
namespace DotGen\Config\Validator;

use DotGen\Config\Entity\Collection;
use DotGen\Config\FakeResource;

class ResourceValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testValidateNoDuplicateFileReferencesError()
    {
        // arrange
        $resource = new FakeResource();

        $collection1 = new Collection('my_collection', [], ['a.txt', 'b.txt']);
        $collection2 = new Collection('my_other_collection', [], ['a.txt']);

        $resource->setCollections([$collection1, $collection2]);
        $resource->setEngine('twig');
        $resource->setInputPath('/tmp');
        $resource->setOutputPath('/tmp');

        // expect
        $this->expectException(DuplicateFileReferenceException::class);

        // act
        ResourceValidator::validate($resource);
    }

    public function testValidateNoDuplicateFileReferencesNoError()
    {
        // arrange
        $resource = new FakeResource();

        $collection1 = new Collection('my_collection', [], ['a.txt', 'b.txt']);
        $collection2 = new Collection('my_other_collection', [], ['c.txt', 'd.txt']);

        $resource->setCollections([$collection1, $collection2]);
        $resource->setEngine('twig');
        $resource->setInputPath('/tmp');
        $resource->setOutputPath('/tmp');

        // act
        $exn = null;
        try {
            ResourceValidator::validate($resource);
        } catch (ResourceValidationException $e)
        {
            $exn = $e;
        }

        // assert
        self::assertNull($exn);
    }
}