<?php
namespace DotGen\Config\Resource\Parser;

class ParserManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        // arrange
        $manager = new ParserManager();

        $parser1 = new FakeParser(['a'], true);
        $parser2 = new FakeParser(['b'], false);
        $parser3 = new FakeParser(['c'], false);

        $manager->registerParser($parser1);
        $manager->registerParser($parser2);
        $manager->registerParser($parser3);

        // act
        $parsed = $manager->parse('xyz');

        // assert
        self::assertSame($parsed, ['a']);
    }

    public function testNoAvailableParser()
    {
        // arrange
        $manager = new ParserManager();
        $parser = new FakeParser([], false);
        $manager->registerExclusive($parser);

        // expect
        $this->expectException(ParserException::class);

        // act
        $parsed = $manager->parse('');
    }
}