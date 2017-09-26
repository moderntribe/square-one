# JBZoo PimpleDumper  [![Build Status](https://travis-ci.org/JBZoo/PimpleDumper.svg?branch=master)](https://travis-ci.org/JBZoo/PimpleDumper)      [![Coverage Status](https://coveralls.io/repos/github/JBZoo/PimpleDumper/badge.svg?branch=master)](https://coveralls.io/github/JBZoo/PimpleDumper?branch=master)

#### Simple way to auto create [pimple.json](https://github.com/Sorien/silex-idea-plugin) and [.phpstorm.meta.php](https://confluence.jetbrains.com/display/PhpStorm/PhpStorm+Advanced+Metadata) 

[![License](https://poser.pugx.org/JBZoo/PimpleDumper/license)](https://packagist.org/packages/JBZoo/PimpleDumper)  [![Latest Stable Version](https://poser.pugx.org/JBZoo/PimpleDumper/v/stable)](https://packagist.org/packages/JBZoo/PimpleDumper) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/JBZoo/PimpleDumper/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/JBZoo/PimpleDumper/?branch=master)

### Install
```sh
composer require jbzoo/pimpledumper:"1.x-dev"
```

Install and activate [Silex Plugin](https://plugins.jetbrains.com/plugin/7809?pr=) in PhpStorm settings

### Usage

```php
<?php
require_once './vendor/autoload.php'; // composer autoload.php

// Get needed classes
use JBZoo\PimpleDumper\PimpleDumper;
use Pimple\Container;

// Init container
$container = new Container();
$container['somekey'] = function() {
    return new SomeClass(); 
};

// Auto dump pimple.json on PimpleDumper destructor (or PHP die)
$container->register(new PimpleDumper()); // register service

// Manually (in the end of script!)
$dumper = new PimpleDumper();
$dumper->dumpPimple($container); // Create new pimple.json 
$dumper->dumpPimple($container, true); // Append to current pimple.json 
$dumper->dumpPhpstorm($container); // Create new .phpstorm.meta.php (experimental!)

```

### Output example
pimple.json
```json
[
    {
        "name": "somekey",
        "type": "class",
        "value": "SomeClass"
    }
]
```

.phpstorm.meta.php (experimental!)
```php
<?php
/**
 * ProcessWire PhpStorm Meta
 *
 * This file is not a CODE, it makes no sense and won't run or validate
 * Its AST serves PhpStorm IDE as DATA source to make advanced type inference decisions.
 * 
 * @see https://confluence.jetbrains.com/display/PhpStorm/PhpStorm+Advanced+Metadata
 */

namespace PHPSTORM_META {

    $STATIC_METHOD_TYPES = [
        new \Pimple\Container => [
            '' == '@',
            'somekey' instanceof SomeClass,
        ],
    ];

}

```

### Result
![Result of JBZoo/PimpleDumper](http://llfl.ru/images/f7/5ks5.png)

### Unit tests and check code style
```sh
composer update-all
composer test
```


### License

MIT
