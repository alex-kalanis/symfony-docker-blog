<?php

namespace StorageTests;


use CommonTestClass;
use kalanis\kw_storage\Storage\Target;
use kalanis\kw_storage\StorageException;


class VolumeStreamTest extends CommonTestClass
{
    public function tearDown(): void
    {
        $this->rmFile($this->mockTestFile('', false));
        $this->rmDir($this->mockTestFile('', false));
        parent::tearDown();
    }

    /**
     * @throws StorageException
     */
    public function testExists(): void
    {
        $volume = new Target\VolumeStream();
        $this->assertTrue($volume->check($this->getTestDir()));
        $this->expectException(StorageException::class);
        $volume->load($this->mockTestFile());
    }

    /**
     * @throws StorageException
     */
    public function testOperations(): void
    {
        $stream = fopen('php://memory', 'r+b');
        fwrite($stream, 'asdfghjklpoiuztrewqyxcvbnm');
        rewind($stream);

        $volume = new Target\VolumeStream();
        $this->assertFalse($volume->exists($this->mockTestFile()));
        $this->assertFalse($volume->isDir($this->mockTestFile()));
        $this->assertTrue($volume->save($this->mockTestFile(), $stream));
        $this->assertTrue($volume->exists($this->mockTestFile()));
        $this->assertFalse($volume->isDir($this->mockTestFile()));
        $this->assertEquals('asdfghjklpoiuztrewqyxcvbnm', stream_get_contents($volume->load($this->mockTestFile()), -1, 0));
        $this->assertTrue($volume->remove($this->mockTestFile()));
        $this->assertFalse($volume->exists($this->mockTestFile()));
        $this->assertFalse($volume->isDir($this->mockTestFile()));
    }

    /**
     * @throws StorageException
     */
    public function testSimpleCounter(): void
    {
        $stream = fopen('php://memory', 'r+b');
        fwrite($stream, '15');
        rewind($stream);

        $volume = new Target\VolumeStream();
        $this->assertFalse($volume->exists($this->mockTestFile()));
        $this->assertTrue($volume->save($this->mockTestFile(), $stream));
        $this->assertTrue($volume->decrement($this->mockTestFile()));
        $this->assertTrue($volume->decrement($this->mockTestFile()));
        $this->assertTrue($volume->increment($this->mockTestFile()));
        $this->assertEquals(14, intval(stream_get_contents($volume->load($this->mockTestFile()), -1, 0)));
        $this->assertTrue($volume->remove($this->mockTestFile()));
        $this->assertFalse($volume->exists($this->mockTestFile()));
    }

    /**
     * @throws StorageException
     */
    public function testHarderCounter(): void
    {
        $volume = new Target\VolumeStream();
        $this->assertFalse($volume->exists($this->mockTestFile()));
        $this->assertTrue($volume->decrement($this->mockTestFile()));
        $this->assertTrue($volume->increment($this->mockTestFile()));
        $this->assertEquals(1, intval(stream_get_contents($volume->load($this->mockTestFile()), -1, 0)));
        $this->assertTrue($volume->remove($this->mockTestFile()));
        $this->assertTrue($volume->increment($this->mockTestFile()));
        $this->assertTrue($volume->decrement($this->mockTestFile()));
        $this->assertEquals(0, intval(stream_get_contents($volume->load($this->mockTestFile()), -1, 0)));
        $this->assertTrue($volume->remove($this->mockTestFile()));
        $this->assertFalse($volume->exists($this->mockTestFile()));
    }
}
