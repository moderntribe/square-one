<?php
/**
 * For retrieving arguments passed with GET.
 *
 * @package Sqaure1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\API\Router\Input_Strategies;

/**
 * Class Input_Get.
 */
class Input_Get implements Input_Strategy_Interface {
	/**
	 * @inheritDoc
	 */
	public function get_config_value( $key, $default = null, $sanitization = FILTER_SANITIZE_STRING ) {
		$value = $_GET[ $key ] ?? null;
		$value = filter_var( $value, $sanitization );

		return $value ?? $default;
	}
}
