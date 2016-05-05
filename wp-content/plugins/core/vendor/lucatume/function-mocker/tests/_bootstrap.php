<?php
require_once __DIR__ . '/../vendor/antecedent/patchwork/Patchwork.php';
require_once __DIR__ . '/../vendor/autoload.php';
foreach ( glob( dirname( __FILE__ ) . '/test_supports/*.php' ) as $file ) {
	include $file;
}
require_once 'classes.php';
