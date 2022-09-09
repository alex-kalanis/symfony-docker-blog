<?php

namespace StorageTests;


use CommonTestClass;
use kalanis\kw_storage\Extras\TVolumeCopy;


class VolumeCopyTest extends CommonTestClass
{
    public function setUp(): void
    {
        parent::setUp();
        mkdir($this->getTestDir() . 'some');
        mkdir($this->getTestDir() . 'some' . DIRECTORY_SEPARATOR . 'any');
        touch($this->getTestDir() . 'some' . DIRECTORY_SEPARATOR . 'tst1');
        touch($this->getTestDir() . 'some' . DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'tst2');
        touch($this->getTestDir() . 'some' . DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'tst3');
    }

    public function tearDown(): void
    {
        // copy
        $this->rmFile('other' . DIRECTORY_SEPARATOR . 'tst1');
        $this->rmFile('other' . DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'tst2');
        $this->rmFile('other' . DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'tst3');
        $this->rmDir('other' . DIRECTORY_SEPARATOR . 'any');
        $this->rmDir('other');
        // original
        $this->rmFile('some' . DIRECTORY_SEPARATOR . 'tst1');
        $this->rmFile('some' . DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'tst2');
        $this->rmFile('some' . DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'tst3');
        $this->rmDir('some' . DIRECTORY_SEPARATOR . 'any');
        $this->rmDir('some');
        parent::tearDown();
    }

    public function testCopy(): void
    {
        $volume = new XVolumeCopy();
        $this->assertTrue(file_exists($this->getTestDir() . 'some' . DIRECTORY_SEPARATOR . 'tst1'));
        $this->assertFalse(file_exists($this->getTestDir() . 'other' . DIRECTORY_SEPARATOR . 'tst1'));
        $this->assertTrue(file_exists($this->getTestDir() . 'some' . DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'tst2'));
        $this->assertFalse(file_exists($this->getTestDir() . 'other' . DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'tst2'));
        $this->assertTrue(file_exists($this->getTestDir() . 'some' . DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'tst3'));
        $this->assertFalse(file_exists($this->getTestDir() . 'other' . DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'tst3'));
        $this->assertTrue($volume->copy($this->getTestDir() . 'some', $this->getTestDir() . 'other'));
        $this->assertTrue(file_exists($this->getTestDir() . 'other' . DIRECTORY_SEPARATOR . 'tst1'));
        $this->assertTrue(file_exists($this->getTestDir() . 'other' . DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'tst2'));
        $this->assertTrue(file_exists($this->getTestDir() . 'other' . DIRECTORY_SEPARATOR . 'any' . DIRECTORY_SEPARATOR . 'tst3'));
        $this->assertFalse($volume->copy($this->getTestDir() . 'unknown', $this->getTestDir() . 'target'));
    }
}


class XVolumeCopy
{
    use TVolumeCopy;

    public function copy(string $source, string $dest): bool
    {
        return $this->xcopy($source, $dest);
    }
}

