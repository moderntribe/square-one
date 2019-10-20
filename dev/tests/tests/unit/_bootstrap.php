<?php
use tad\FunctionMocker\FunctionMocker;

\Codeception\Util\Autoload::addNamespace('Tribe\\Project', __DIR__ . '/../../../../wp-content/plugins/core/src');

//$cache_path = realpath( sys_get_temp_dir() ) . DIRECTORY_SEPARATOR . 'function-mocker';
//if ( ! file_exists( $cache_path ) ) {
//	mkdir( $cache_path );
//}
//FunctionMocker::init( [
//	'cache-path' => $cache_path,
//	'blacklist'  => dirname( __DIR__ ),
//] );
