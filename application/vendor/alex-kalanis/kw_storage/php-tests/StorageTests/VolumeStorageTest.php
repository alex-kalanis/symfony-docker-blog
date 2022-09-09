<?php

namespace StorageTests;


use CommonTestClass;
use kalanis\kw_storage\Interfaces\IPassDirs;
use kalanis\kw_storage\Storage\Key\DirKey;
use kalanis\kw_storage\Storage\StorageDirs;
use kalanis\kw_storage\Storage\Target;
use kalanis\kw_storage\StorageException;


class VolumeStorageTest extends CommonTestClass
{
    public function tearDown(): void
    {
        $this->rmFile($this->mockTestFile('', false));
        $this->rmFile($this->mockTestFile('2', false));
        $this->rmFile($this->mockTestFile('3', false));
        $this->rmFile($this->mockTestFile('4', false));
        $this->rmFile($this->mockTestFile('5', false));
        $this->rmDir($this->mockTestFile('', false));
        $this->rmDir('some');
        parent::tearDown();
    }

    /**
     * @throws StorageException
     */
    public function testDir(): void
    {
        $volume = $this->getLib();
        $this->assertTrue($volume->isDir('.'));
    }

    /**
     * @throws StorageException
     */
    public function testExists(): void
    {
        $volume = $this->getLib();
        $this->assertFalse($volume->exists($this->mockTestFile('', false)));
        $this->assertFalse($volume->isDir($this->mockTestFile('', false)));
        $this->expectException(StorageException::class);
        $volume->read($this->mockTestFile('', false));
    }

    /**
     * @throws StorageException
     */
    public function testOperations(): void
    {
        $volume = $this->getLib();
        $this->assertFalse($volume->exists($this->mockTestFile('', false)));
        $this->assertFalse($volume->isDir($this->mockTestFile('', false)));
        $this->assertTrue($volume->write($this->mockTestFile('', false), 'asdfghjklpoiuztrewqyxcvbnm'));
        $this->assertTrue($volume->exists($this->mockTestFile('', false)));
        $this->assertFalse($volume->isDir($this->mockTestFile('', false)));
        $this->assertTrue($volume->isFile($this->mockTestFile('', false)));
        $this->assertEquals(26, $volume->size($this->mockTestFile('', false)));
        $created = $volume->created($this->mockTestFile('', false));
        $now = time();
        $this->assertTrue($created > $now - 10);
        $this->assertTrue($created < $now + 10);
        $this->assertEquals('asdfghjklpoiuztrewqyxcvbnm', $volume->read($this->mockTestFile('', false)));
        $this->assertTrue($volume->copy($this->mockTestFile('', false), $this->mockTestFile('2', false)));
        $this->assertTrue($volume->move($this->mockTestFile('', false), $this->mockTestFile('3', false)));
        $this->assertFalse($volume->copy($this->mockTestFile('', false), $this->mockTestFile('4', false)));
        $this->assertFalse($volume->move($this->mockTestFile('', false), $this->mockTestFile('5', false)));
        $this->assertFalse($volume->remove($this->mockTestFile('', false)));
        $this->assertTrue($volume->remove($this->mockTestFile('2', false)));
        $this->assertTrue($volume->remove($this->mockTestFile('3', false)));
        $this->assertFalse($volume->remove($this->mockTestFile('4', false)));
        $this->assertFalse($volume->remove($this->mockTestFile('5', false)));
        $this->assertFalse($volume->exists($this->mockTestFile('', false)));
        $this->assertFalse($volume->isDir($this->mockTestFile('', false)));
        $this->assertNull($volume->size($this->mockTestFile('', false)));
        $this->assertNull($volume->created($this->mockTestFile('', false)));
    }

    /**
     * @throws StorageException
     */
    public function testDirsRecursive(): void
    {
        $volume = $this->getLib();
        $this->assertTrue($volume->mkDir(implode(DIRECTORY_SEPARATOR, ['some', 'none', 'hoo']), true));
        $this->assertTrue($volume->exists('some'));
        $this->assertFalse($volume->exists('some' . DIRECTORY_SEPARATOR . 'hint.none'));
        $this->assertTrue($volume->write('some' . DIRECTORY_SEPARATOR . 'hint.none', 'asdfghjklpoiuztrewqyxcvbnm'));
        $this->assertTrue($volume->rmDir('some', true));
        $this->assertFalse($volume->isDir('some'));
        $this->assertFalse($volume->exists('some'));
    }

    /**
     * @throws StorageException
     */
    public function testLookup(): void
    {
        $volume = $this->getLib();
        $testFiles = [
            'dummyFile.tst' => 'dummyFile.tst',
            'dummyFile.0.tst' => 'dummyFile.0.tst',
            'dummyFile.1.tst' => 'dummyFile.1.tst',
            'dummyFile.2.tst' => 'dummyFile.2.tst',
        ];
        $removal = $volume->removeMulti($testFiles);
        $this->assertEquals([
            'dummyFile.tst' => false,
            'dummyFile.0.tst' => false,
            'dummyFile.1.tst' => false,
            'dummyFile.2.tst' => false,
        ], $removal);

        iterator_to_array($volume->lookup('this path does not exists'));
        $this->assertEquals(1, count(array_filter(iterator_to_array($volume->lookup('')), [$this, 'dotDirs'])));

        file_put_contents($this->getTestDir() . 'dummyFile.tst', 'asdfghjklqwertzuiopyxcvbnm');
        file_put_contents($this->getTestDir() . 'dummyFile.0.tst', 'asdfghjklqwertzuiopyxcvbnm');
        file_put_contents($this->getTestDir() . 'dummyFile.1.tst', 'asdfghjklqwertzuiopyxcvbnm');
        file_put_contents($this->getTestDir() . 'dummyFile.2.tst', 'asdfghjklqwertzuiopyxcvbnm');

        $files = iterator_to_array($volume->lookup(''));
        sort($files);
        $files = array_filter($files, [$this, 'dotDirs']);

        $this->assertEquals(count($testFiles) + 1, count($files)); // because gitkeep
        $this->assertEquals('.gitkeep', reset($files));
        $this->assertEquals('dummyFile.0.tst', next($files));
        $this->assertEquals('dummyFile.1.tst', next($files));
        $this->assertEquals('dummyFile.2.tst', next($files));
        $this->assertEquals('dummyFile.tst', next($files));

        $removal = $volume->removeMulti($testFiles);
        $this->assertFalse($volume->exists('dummyFile.tst'));
        $this->assertFalse($volume->exists('dummyFile.0.tst'));
        $this->assertFalse($volume->exists('dummyFile.1.tst'));
        $this->assertFalse($volume->exists('dummyFile.2.tst'));

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
        $volume = $this->getLib();
        $this->assertFalse($volume->exists($this->mockTestFile('', false)));
        $this->assertTrue($volume->write($this->mockTestFile('', false), 15));
        $this->assertTrue($volume->decrement($this->mockTestFile('', false)));
        $this->assertTrue($volume->decrement($this->mockTestFile('', false)));
        $this->assertTrue($volume->increment($this->mockTestFile('', false)));
        $this->assertEquals(14, $volume->read($this->mockTestFile('', false)));
        $this->assertTrue($volume->remove($this->mockTestFile('', false)));
        $this->assertFalse($volume->exists($this->mockTestFile('', false)));
    }

    /**
     * @throws StorageException
     */
    public function testHarderCounter(): void
    {
        $volume = $this->getLib();
        $this->assertFalse($volume->exists($this->mockTestFile('', false)));
        $this->assertTrue($volume->decrement($this->mockTestFile('', false)));
        $this->assertTrue($volume->increment($this->mockTestFile('', false)));
        $this->assertEquals(1, $volume->read($this->mockTestFile('', false)));
        $this->assertTrue($volume->remove($this->mockTestFile('', false)));
        $this->assertTrue($volume->increment($this->mockTestFile('', false)));
        $this->assertTrue($volume->decrement($this->mockTestFile('', false)));
        $this->assertEquals(0, $volume->read($this->mockTestFile('', false)));
        $this->assertTrue($volume->remove($this->mockTestFile('', false)));
        $this->assertFalse($volume->exists($this->mockTestFile('', false)));
    }

    protected function getLib(): IPassDirs
    {
        DirKey::setDir($this->getTestDir());
        $volume = new Target\Volume();
        $volume->check($this->getTestDir());
        return new StorageDirs(new DirKey(), $volume);
    }
}
