<?php
// This is global bootstrap for autoloading

// This way any vendor library and any project library autoloaded using
// Composer will be available for autoloading in tests
include dirname(dirname(__FILE__)) . '/vendor/autoload.php';
