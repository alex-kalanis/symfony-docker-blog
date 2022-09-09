# kw_confs

[![Build Status](https://travis-ci.org/alex-kalanis/kw_confs.svg?branch=master)](https://travis-ci.org/alex-kalanis/kw_confs)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alex-kalanis/kw_confs/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alex-kalanis/kw_confs/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/alex-kalanis/kw_confs/v/stable.svg?v=1)](https://packagist.org/packages/alex-kalanis/kw_confs)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)
[![Downloads](https://img.shields.io/packagist/dt/alex-kalanis/kw_confs.svg?v1)](https://packagist.org/packages/alex-kalanis/kw_confs)
[![License](https://poser.pugx.org/alex-kalanis/kw_confs/license.svg?v=1)](https://packagist.org/packages/alex-kalanis/kw_confs)
[![Code Coverage](https://scrutinizer-ci.com/g/alex-kalanis/kw_confs/badges/coverage.png?b=master&v=1)](https://scrutinizer-ci.com/g/alex-kalanis/kw_confs/?branch=master)

Define used configurations inside the KWCMS tree. Parse them and return them.

## PHP Installation

```
{
    "require": {
        "alex-kalanis/kw_confs": "1.0"
    }
}
```

(Refer to [Composer Documentation](https://github.com/composer/composer/blob/master/doc/00-intro.md#introduction) if you are not
familiar with composer)

This package contains example file from KWCMS bootstrap. Use it as reference.

This config bootstrap is connected with KWCMS modules. Using it outside KWCMS means
you need to know the tree structure of module system and positioning configs there.

The basic config itself is simple php file with defined array variable "$config" in
which are stored key-value pairs like in normal php array. You do not need to specify
module - it will be automatically set into content array when config loads.

It's also possible to use your own loader which will read your config files by your own
rules. So you can connect reading configurations from DB or INI file and that all will
still behave the same way. Just it's need to respect that loader's input is module and
sometimes conf name and output is array of key-value pairs which will be set into config
array with module as primary key.
