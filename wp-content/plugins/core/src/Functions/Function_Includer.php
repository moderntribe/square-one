<?php


namespace Tribe\Project\Functions;


abstract class Function_Includer {
	public static function cache() {
		require_once( __DIR__ . '/cache.php' );
	}

	public static function version() {
		require_once( __DIR__ . '/version.php' );
	}
}