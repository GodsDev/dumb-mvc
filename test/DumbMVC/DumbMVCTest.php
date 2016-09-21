<?php

namespace DumbMVC\Test;

$returnValFromRequire = 1;

class DumbMVCTest extends \PHPUnit_Framework_TestCase {

    const ROOT_PATH = "/example/root/path";

    protected $dmc;
    protected $dmcWithTruePath;
    protected $testLogger;

    protected function setUp() {
        $this->dmc = new \DumbMVC\DumbMVC(new \PhpIncluder\PI(self::ROOT_PATH), new \DumbMVC\FakeLogger());
        $this->dmcWithTruePath = new \DumbMVC\DumbMVC(new \PhpIncluder\PI(), new \DumbMVC\FakeLogger());
        $this->testLogger = new \DumbMVC\FakeLogger();
    }


    public function testFullPath() {
        $this->assertEquals(self::ROOT_PATH, $this->dmc->fullPath());
        $this->assertEquals(self::ROOT_PATH, $this->dmc->fullPath(""));
        $this->assertEquals(self::ROOT_PATH . "/tools", $this->dmc->fullPath("tools"));
    }


    public function testLoggerSetGet() {
        $this->assertTrue($this->dmc->contextLogger() !== $this->testLogger);
        $this->dmc->setContextLogger($this->testLogger);
        $this->assertTrue($this->dmc->contextLogger() === $this->testLogger);
    }

    public function testContextSetGet() {
        $this->assertFalse(isset($this->dmc->context["hello"]));
        $this->dmc->context["hello"] = "ok";
        $this->assertTrue(isset($this->dmc->context["hello"]));
        $this->assertEquals("ok", $this->dmc->context["hello"]);

        $this->assertTrue(isset($this->dmc->context["out"]));
        $this->dmc->context["out"]["num"] = 1234;
        $this->assertEquals(1234, $this->dmc->context["out"]["num"]);

        //isolation
        $this->assertFalse(isset($this->dmcWithTruePath->context["out"]["num"]));
    }

    public function testContextHelper() {
        $this->dmc->context["out"]["num"] = 1234;
        $this->assertEquals(1234, $this->dmc->context["out"]["num"]);
        $this->assertEquals(1234, $this->dmc->contextOut()["num"]);
    }

    public function testRequireFile() {
        global $returnValFromRequire;
        $this->assertEquals(1, $returnValFromRequire);
        $this->dmcWithTruePath->requireFile("test/DumbMVC/fixture/fileToRequire.php");
        $this->assertEquals(123, $returnValFromRequire);
    }


    public function testExceptionStack() {
        $this->assertFalse($this->dmc->isAnyContextException());
        $this->dmc->addContextException(new \Exception("exc example 1"));
        $this->assertTrue($this->dmc->isAnyContextException());
        $this->assertCount(1, $this->dmc->contextExceptions());
        $this->dmc->addContextException(new \Exception("exc example 2"));
        $this->assertCount(2, $this->dmc->contextExceptions());
    }


    public function testDumbSingleton() {
        $this->assertNull(\DumbMVC\DumbMVC::instance());
        \DumbMVC\DumbMVC::setInstance($this->dmc);
        $this->assertInstanceOf("\DumbMVC\DumbMVC", \DumbMVC\DumbMVC::instance());
    }
}

