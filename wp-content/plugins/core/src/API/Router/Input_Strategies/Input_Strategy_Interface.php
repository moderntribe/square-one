<?php
/**
 * Interface for HTTP argument passing.
 *
 * @package Sqaure1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\API\Router\Input_Strategies;

/**
 * Interface Input_Strategy_Interface.
 */
interface Input_Strategy_Interface {
	/**
	 * Get a value for the passed argument.
	 *
	 * @param string $key          The argument key.
	 * @param mixed  $default      A default value.
	 * @param int    $sanitization Sanitization strategy.
	 *
	 * @return mixed
	 */
	public function get_config_value( string $key, $default = null, int $sanitization = FILTER_SANITIZE_STRING );
}
