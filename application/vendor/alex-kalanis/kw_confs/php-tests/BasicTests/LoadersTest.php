<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_confs\ConfException;
use kalanis\kw_confs\Interfaces\IConf;
use kalanis\kw_confs\Interfaces\ILoader;
use kalanis\kw_confs\Loaders\ClassLoader;
use kalanis\kw_confs\Loaders\MultiLoader;


class LoadersTest extends CommonTestClass
{
    /**
     * @throws ConfException
     */
    public function testMulti(): void
    {
        $lib = new MultiLoader();
        $this->assertEmpty($lib->load('dummy', 'none'));
        $lib->addLoader(new XYLoader());
        $set = $lib->load('dummy', 'after loaded');
        $this->assertEquals(['abc' => 'mno', 'jkl' => 'vwx%s', ], $set);
    }

    /**
     * @throws ConfException
     */
    public function testClass(): void
    {
        $lib = new ClassLoader();
        $this->assertEmpty($lib->load('not set', 'none'));
        $lib->addClass(new XYConf());
        $this->assertEmpty($lib->load('still not set', 'none'));
        $this->assertEquals(['abc01' => '98mno', 'jkl67' => '32vwx%s', ], $lib->load('testing', 'ignore now'));
    }
}


class XYLoader implements ILoader
{
    public function load(string $module, string $conf = ''): array
    {
        return [
            'abc' => 'mno',
            'jkl' => 'vwx%s',
        ];
    }
}


class XYConf implements IConf
{
    public function setPart(string $part): void
    {
        // nothing
    }

    public function getConfName(): string
    {
        return 'testing';
    }

    public function getSettings(): array
    {
        return [
            'abc01' => '98mno',
            'jkl67' => '32vwx%s',
        ];
    }
}
