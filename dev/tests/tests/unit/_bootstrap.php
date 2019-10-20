<?php
use tad\FunctionMocker\FunctionMocker;

\Codeception\Util\Autoload::addNamespace('Tribe\\Project', __DIR__ . '/../../../../wp-content/plugins/core/src');

FunctionMocker::init( [
	'cache-path' => realpath( sys_get_temp_dir() ) . DIRECTORY_SEPARATOR . 'function-mocker',
	'blacklist'  => dirname( __DIR__ ),
] );
