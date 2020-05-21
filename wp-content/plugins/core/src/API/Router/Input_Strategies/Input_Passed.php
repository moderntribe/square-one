<?php
/**
 * For config values passed by reference.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\API\Router\Input_Strategies;

/**
 * Class Input_Passed_Config.
 */
class Input_Passed implements Input_Strategy_Interface {

	/**
	 * @var null The passed arguments.
	 */
	protected $args;

	/**
	 * Input_Passed_Config constructor.
	 *
	 * @param array $args Passed arguments.
	 */
	public function __construct( array $args = [] ) {
		$this->args = $args;
	}

	/**
	 * @inheritDoc
	 */
	public function get_config_value( $key, $default = null, $sanitization = FILTER_SANITIZE_STRING ) {
		return filter_var( $this->args[ $key ], $sanitization ) ?? $default;
	}
}
