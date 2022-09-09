<?php

namespace StorageTests;


use CommonTestClass;
use kalanis\kw_storage\Storage\Target;
use kalanis\kw_storage\StorageException;


class MemoryTest extends CommonTestClass
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
        $memory = new Target\Memory();
        $this->assertTrue($memory->check($this->getTestDir()));
        $this->assertFalse($memory->exists($this->mockTestFile()));
        $this->expectException(StorageException::class);
        $memory->load($this->mockTestFile());
    }

    /**
     * @throws StorageException
     */
    public function testOperations(): void
    {
        $memory = new Target\Memory();
        $this->assertFalse($memory->exists($this->mockTestFile()));
        $this->assertTrue($memory->save($this->mockTestFile(), 'asdfghjklpoiuztrewqyxcvbnm'));
        $this->assertTrue($memory->exists($this->mockTestFile()));
        $this->assertEquals('asdfghjklpoiuztrewqyxcvbnm', $memory->load($this->mockTestFile()));
        $this->assertTrue($memory->remove($this->mockTestFile()));
        $this->assertFalse($memory->exists($this->mockTestFile()));
    }

    /**
     * @throws StorageException
     */
    public function testLookup(): void
    {
        $memory = new Target\Memory();
        $this->assertTrue($memory->check($this->getTestDir()));
        $testFiles = [
            'dummyFile.tst' => $this->getTestDir() . 'dummyFile.tst',
            'dummyFile.0.tst' => $this->getTestDir() . 'dummyFile.0.tst',
            'dummyFile.1.tst' => $this->getTestDir() . 'dummyFile.1.tst',
            'dummyFile.2.tst' => $this->getTestDir() . 'dummyFile.2.tst',
        ];
        $removal = $memory->removeMulti($testFiles);
        $this->assertEquals([
            'dummyFile.tst' => false,
            'dummyFile.0.tst' => false,
            'dummyFile.1.tst' => false,
            'dummyFile.2.tst' => false,
        ], $removal);

        iterator_to_array($memory->lookup('this path does not exists'));
        $this->assertEquals(0, count(array_filter(iterator_to_array($memory->lookup($this->getTestDir())), [$this, 'dotDirs'])));

        $memory->save($this->getTestDir() . 'dummyFile.tst', 'asdfghjklqwertzuiopyxcvbnm');
        $memory->save($this->getTestDir() . 'dummyFile.0.tst', 'asdfghjklqwertzuiopyxcvbnm');
        $memory->save($this->getTestDir() . 'dummyFile.1.tst', 'asdfghjklqwertzuiopyxcvbnm');
        $memory->save($this->getTestDir() . 'dummyFile.2.tst', 'asdfghjklqwertzuiopyxcvbnm');

        $files = iterator_to_array($memory->lookup($this->getTestDir()));
        sort($files);
        $files = array_filter($files, [$this, 'dotDirs']);

        $this->assertEquals(count($testFiles), count($files));
        $this->assertEquals('dummyFile.0.tst', reset($files));
        $this->assertEquals('dummyFile.1.tst', next($files));
        $this->assertEquals('dummyFile.2.tst', next($files));
        $this->assertEquals('dummyFile.tst', next($files));

        $removal = $memory->removeMulti($testFiles);
        $this->assertFalse($memory->exists($this->getTestDir() . 'dummyFile.tst'));
        $this->assertFalse($memory->exists($this->getTestDir() . 'dummyFile.0.tst'));
        $this->assertFalse($memory->exists($this->getTestDir() . 'dummyFile.1.tst'));
        $this->assertFalse($memory->exists($this->getTestDir() . 'dummyFile.2.tst'));

        $this->assertEquals([
            'dummyFile.tst' => true,
            'dummyFile.0.tst' => true,
            'dummyFile.1.tst' => true,
            'dummyFile.2.tst' => true,
        ], $removal);
    }

    public function dotDirs(string $path): bool
    {
        return !in_array($path, ['.', '..']);
    }

    /**
     * @throws StorageException
     */
    public function testSimpleCounter(): void
    {
        $memory = new Target\Memory();
        $this->assertFalse($memory->exists($this->mockTestFile()));
        $this->assertTrue($memory->save($this->mockTestFile(), 15));
        $this->assertTrue($memory->decrement($this->mockTestFile()));
        $this->assertTrue($memory->decrement($this->mockTestFile()));
        $this->assertTrue($memory->increment($this->mockTestFile()));
        $this->assertEquals(14, $memory->load($this->mockTestFile()));
        $this->assertTrue($memory->remove($this->mockTestFile()));
        $this->assertFalse($memory->exists($this->mockTestFile()));
    }

    /**
     * @throws StorageException
     */
    public function testHarderCounter(): void
    {
        $memory = new Target\Memory();
        $this->assertFalse($memory->exists($this->mockTestFile()));
        $this->assertTrue($memory->decrement($this->mockTestFile()));
        $this->assertTrue($memory->increment($this->mockTestFile()));
        $this->assertEquals(1, $memory->load($this->mockTestFile()));
        $this->assertTrue($memory->remove($this->mockTestFile()));
        $this->assertTrue($memory->increment($this->mockTestFile()));
        $this->assertTrue($memory->decrement($this->mockTestFile()));
        $this->assertEquals(0, $memory->load($this->mockTestFile()));
        $this->assertTrue($memory->remove($this->mockTestFile()));
        $this->assertFalse($memory->exists($this->mockTestFile()));
    }
}
