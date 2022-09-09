<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_storage\Extras\SeparatorSigns;


class SeparatorsTest extends CommonTestClass
{
    /**
     * @param string $what
     * @param string $expected
     * @param string $separator
     * @dataProvider startProvider
     */
    public function testStartRemoval(string $what, string $expected, string $separator): void
    {
        $this->assertEquals($expected, SeparatorSigns::removeFromStart($what, $separator));
    }

    public function startProvider(): array
    {
        return [
            ['/foo/', 'foo/', '/'],
            ['--bar--baz--', 'bar--baz--', '--'],
            ['tgb', 'tgb', ''],
        ];
    }

    /**
     * @param string $what
     * @param string $expected
     * @param string $separator
     * @dataProvider endProvider
     */
    public function testEndRemoval(string $what, string $expected, string $separator): void
    {
        $this->assertEquals($expected, SeparatorSigns::removeFromEnd($what, $separator));
    }

    public function endProvider(): array
    {
        return [
            ['/foo/', '/foo', '/'],
            ['--bar--baz--', '--bar--baz', '--'],
            ['tgb', 'tgb', ''],
        ];
    }
}
