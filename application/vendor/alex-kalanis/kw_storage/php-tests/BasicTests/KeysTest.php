<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_storage\Storage\Key;
use kalanis\kw_storage\Storage\Target;


class KeysTest extends CommonTestClass
{
    public function testInit(): void
    {
        $factory = new Key\Factory();
        $this->assertInstanceOf('\kalanis\kw_storage\Storage\Key\DirKey', $factory->getKey(new Target\Volume()));
        $this->assertInstanceOf('\kalanis\kw_storage\Storage\Key\DefaultKey', $factory->getKey(new \TargetMock()));
    }

    public function testDefaultKey(): void
    {
        $key = new Key\DefaultKey();
        $this->assertEquals('aaaaaaa', $key->fromSharedKey('aaaaaaa'));
        $this->assertEquals('ear/a4vw-z.7v2!3#z', $key->fromSharedKey('ear/a4vw-z.7v2!3#z'));
    }

    public function testDirKey(): void
    {
        $key = new Key\DirKey();
        $this->assertEquals('/var/cache/wwwcache/aaaaaaa', $key->fromSharedKey('aaaaaaa'));
        $key::setDir('/var/other/');
        $this->assertEquals('/var/other/ear/a4vw-z.7v2!3#z', $key->fromSharedKey('ear/a4vw-z.7v2!3#z'));
        $key::setDir('/var/cache/wwwcache/');
    }
}
