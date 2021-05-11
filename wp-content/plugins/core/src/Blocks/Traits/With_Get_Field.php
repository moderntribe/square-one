<?php declare( strict_types=1 );

namespace Tribe\Project\Blocks\Traits;

trait With_Get_Field {

	/**
	 * Retrieve data from an ACF field.
	 *
	 * @param int|string $key     ACF key identifier.
	 * @param mixed      $default The default value if the field doesn't exist.
	 *
	 * @return mixed
	 */
	public function get( $key, $default = false ) {
		$value = get_field( $key );
		//check to support nullable type properties in components.
		// ACF will in some cases return and empty string when we may want it to be null.
		// This allows us to always determine the default.
		return ! empty( $value )
			? $value
			: $default;
	}
}
