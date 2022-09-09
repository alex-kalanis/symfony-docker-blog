<?php

namespace StorageTests;


use CommonTestClass;
use kalanis\kw_storage\Storage\Target;
use kalanis\kw_storage\StorageException;


class VolumeTest extends CommonTestClass
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

    public function testDir(): void
    {
        $volume = new Target\Volume();

        // test dir
        $testDir = $this->getTestDir();
        $this->assertTrue($volume->isDir($testDir));
        $mockPath = substr($testDir, 0, strrpos($testDir, DIRECTORY_SEPARATOR)) . 'dummy';
        if (is_dir($mockPath)) {
            rmdir($mockPath);
        }
        file_put_contents($mockPath, 'just leave it there');
        $this->assertTrue($volume->check($mockPath . DIRECTORY_SEPARATOR));
        rmdir($mockPath);
    }

    /**
     * @throws StorageException
     */
    public function testExists(): void
    {
        $volume = new Target\Volume();
        $this->assertTrue($volume->check($this->getTestDir()));
        $this->assertFalse($volume->exists($this->mockTestFile()));
        $this->assertFalse($volume->isDir($this->mockTestFile()));
        $this->expectException(StorageException::class);
        $volume->load($this->mockTestFile());
    }

    /**
     * @throws StorageException
     */
    public function testOperations(): void
    {
        $volume = new Target\Volume();
        $this->assertFalse($volume->exists($this->mockTestFile()));
        $this->assertFalse($volume->isDir($this->mockTestFile()));
        $this->assertTrue($volume->save($this->mockTestFile(), 'asdfghjklpoiuztrewqyxcvbnm'));
        $this->assertTrue($volume->exists($this->mockTestFile()));
        $this->assertFalse($volume->isDir($this->mockTestFile()));
        $this->assertTrue($volume->isFile($this->mockTestFile()));
        $this->assertEquals(26, $volume->size($this->mockTestFile()));
        $created = $volume->created($this->mockTestFile());
        $now = time();
        $this->assertTrue($created > $now - 10);
        $this->assertTrue($created < $now + 10);
        $this->assertEquals('asdfghjklpoiuztrewqyxcvbnm', $volume->load($this->mockTestFile()));
        $this->assertTrue($volume->copy($this->mockTestFile(), $this->mockTestFile('2')));
        $this->assertTrue($volume->move($this->mockTestFile(), $this->mockTestFile('3')));
        $this->assertFalse($volume->copy($this->mockTestFile(), $this->mockTestFile('4')));
        $this->assertFalse($volume->move($this->mockTestFile(), $this->mockTestFile('5')));
        $this->assertFalse($volume->remove($this->mockTestFile()));
        $this->assertTrue($volume->remove($this->mockTestFile('2')));
        $this->assertTrue($volume->remove($this->mockTestFile('3')));
        $this->assertFalse($volume->remove($this->mockTestFile('4')));
        $this->assertFalse($volume->remove($this->mockTestFile('5')));
        $this->assertFalse($volume->exists($this->mockTestFile()));
        $this->assertFalse($volume->isDir($this->mockTestFile()));
        $this->assertNull($volume->size($this->mockTestFile()));
        $this->assertNull($volume->created($this->mockTestFile()));
    }

    /**
     * @throws StorageException
     */
    public function testDirsRecursive(): void
    {
        $volume = new Target\Volume();
        $this->assertTrue($volume->mkDir($this->getTestDir() . implode(DIRECTORY_SEPARATOR, ['some', 'none', 'hoo']), true));
        $this->assertTrue($volume->exists($this->getTestDir() . 'some'));
        $this->assertFalse($volume->exists($this->getTestDir() . 'some' . DIRECTORY_SEPARATOR . 'hint.none'));
        $this->assertTrue($volume->save($this->getTestDir() . 'some' . DIRECTORY_SEPARATOR . 'hint.none', 'asdfghjklpoiuztrewqyxcvbnm'));
        $this->assertTrue($volume->rmDir($this->getTestDir() . 'some', true));
        $this->assertFalse($volume->isDir($this->getTestDir() . 'some'));
        $this->assertFalse($volume->exists($this->getTestDir() . 'some'));
    }

    /**
     * @throws StorageException
     */
    public function testLookup(): void
    {
        $volume = new Target\Volume();
        $this->assertTrue($volume->check($this->getTestDir()));
        $testFiles = [
            'dummyFile.tst' => $this->getTestDir() . 'dummyFile.tst',
            'dummyFile.0.tst' => $this->getTestDir() . 'dummyFile.0.tst',
            'dummyFile.1.tst' => $this->getTestDir() . 'dummyFile.1.tst',
            'dummyFile.2.tst' => $this->getTestDir() . 'dummyFile.2.tst',
        ];
        $removal = $volume->removeMulti($testFiles);
        $this->assertEquals([
            'dummyFile.tst' => false,
            'dummyFile.0.tst' => false,
            'dummyFile.1.tst' => false,
            'dummyFile.2.tst' => false,
        ], $removal);

        iterator_to_array($volume->lookup('this path does not exists'));
        $this->assertEquals(1, count(array_filter(iterator_to_array($volume->lookup($this->getTestDir())), [$this, 'dotDirs'])));

        file_put_contents($this->getTestDir() . 'dummyFile.tst', 'asdfghjklqwertzuiopyxcvbnm');
        file_put_contents($this->getTestDir() . 'dummyFile.0.tst', 'asdfghjklqwertzuiopyxcvbnm');
        file_put_contents($this->getTestDir() . 'dummyFile.1.tst', 'asdfghjklqwertzuiopyxcvbnm');
        file_put_contents($this->getTestDir() . 'dummyFile.2.tst', 'asdfghjklqwertzuiopyxcvbnm');

        $files = iterator_to_array($volume->lookup($this->getTestDir()));
        sort($files);
        $files = array_filter($files, [$this, 'dotDirs']);

        $this->assertEquals(count($testFiles) + 1, count($files)); // because gitkeep
        $this->assertEquals('.gitkeep', reset($files));
        $this->assertEquals('dummyFile.0.tst', next($files));
        $this->assertEquals('dummyFile.1.tst', next($files));
        $this->assertEquals('dummyFile.2.tst', next($files));
        $this->assertEquals('dummyFile.tst', next($files));

        $removal = $volume->removeMulti($testFiles);
        $this->assertFalse($volume->exists($this->getTestDir() . 'dummyFile.tst'));
        $this->assertFalse($volume->exists($this->getTestDir() . 'dummyFile.0.tst'));
        $this->assertFalse($volume->exists($this->getTestDir() . 'dummyFile.1.tst'));
        $this->assertFalse($volume->exists($this->getTestDir() . 'dummyFile.2.tst'));

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
        $volume = new Target\Volume();
        $this->assertFalse($volume->exists($this->mockTestFile()));
        $this->assertTrue($volume->save($this->mockTestFile(), 15));
        $this->assertTrue($volume->decrement($this->mockTestFile()));
        $this->assertTrue($volume->decrement($this->mockTestFile()));
        $this->assertTrue($volume->increment($this->mockTestFile()));
        $this->assertEquals(14, $volume->load($this->mockTestFile()));
        $this->assertTrue($volume->remove($this->mockTestFile()));
        $this->assertFalse($volume->exists($this->mockTestFile()));
    }

    /**
     * @throws StorageException
     */
    public function testHarderCounter(): void
    {
        $volume = new Target\Volume();
        $this->assertFalse($volume->exists($this->mockTestFile()));
        $this->assertTrue($volume->decrement($this->mockTestFile()));
        $this->assertTrue($volume->increment($this->mockTestFile()));
        $this->assertEquals(1, $volume->load($this->mockTestFile()));
        $this->assertTrue($volume->remove($this->mockTestFile()));
        $this->assertTrue($volume->increment($this->mockTestFile()));
        $this->assertTrue($volume->decrement($this->mockTestFile()));
        $this->assertEquals(0, $volume->load($this->mockTestFile()));
        $this->assertTrue($volume->remove($this->mockTestFile()));
        $this->assertFalse($volume->exists($this->mockTestFile()));
    }
}
