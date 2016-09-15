<?php
namespace DotGen\TemplateEngine;

class TemplateEngineManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testRender()
    {
        // arrange
        $manager = new TemplateEngineManager('/tmp');

        $engine1 = new FakeTemplateEngine();
        $engine1->setSupports(true);
        $engine1->setContent('a');

        $engine2 = new FakeTemplateEngine();
        $engine2->setSupports(true);
        $engine2->setContent('b');

        $engine3 = new FakeTemplateEngine();
        $engine3->setSupports(false);
        $engine3->setContent('c');

        $manager->registerEngine($engine1);
        $manager->registerEngine($engine2);
        $manager->registerEngine($engine3);

        // act
        $rendered = $manager->render('x', []);

        // assert
        self::assertSame($rendered, $engine2->getContent());
    }

    public function testNoAvailableEngine()
    {
        // arrange
        $manager = new TemplateEngineManager('/tmp');

        $engine = new FakeTemplateEngine();
        $engine->setSupports(false);

        $manager->registerExclusive($engine);

        // expect
        $this->expectException(TemplateEngineException::class);

        // act
        $manager->render('x', []);
    }
}