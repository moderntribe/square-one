<?php
/**
 * JBZoo PimpleDumper
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   PimpleDumper
 * @license   MIT
 * @copyright Copyright (C) JBZoo.com,  All rights reserved.
 * @link      https://github.com/JBZoo/PimpleDumper
 * @author    Denis Smetannikov <denis@jbzoo.com>
 */

use Pimple\Container;
use JBZoo\PimpleDumper\PimpleDumper;

require_once './../vendor/autoload.php'; // composer autoload.php

/**
 * Class SomeClass
 */
class SomeClass
{
    /**
     * Example docs for method
     *
     * @param string $arg1 Description of argument
     * @param int    $arg2 Another description
     *
     * @return array
     */
    public function myMethod($arg1, $arg2)
    {
        return [$arg1, $arg2];
    }
}

// Init container
$container            = new Container();
$container['somekey'] = function () {
    return new SomeClass();
};

$container->register(new PimpleDumper());

$container['somekey']->myMethod('qwerty', 42);
