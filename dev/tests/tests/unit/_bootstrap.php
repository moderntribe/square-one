<?php

use tad\FunctionMocker\FunctionMocker;

\Codeception\Util\Autoload::addNamespace( 'Tribe\\Project', __DIR__ . '/../../../../wp-content/plugins/core/src' );

$function_mocker_cache_dir = realpath( sys_get_temp_dir() ) . DIRECTORY_SEPARATOR . 'function-mocker';
is_dir( $function_mocker_cache_dir ) || mkdir( $function_mocker_cache_dir );
FunctionMocker::init( [
	'cache-path' => $function_mocker_cache_dir,
] );
