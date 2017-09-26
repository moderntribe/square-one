<?php
require_once __DIR__ . '/../vendor/autoload.php';

\tad\FunctionMocker\FunctionMocker::init([
    'whitelist' => [dirname(__FILE__)],
    'redefinable-internals' => ['array_reduce']
]);

foreach (glob(dirname(__FILE__) . '/test_supports/*.php') as $file) {
    include $file;
}
require_once 'classes.php';

