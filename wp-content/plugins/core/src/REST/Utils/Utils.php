<?php
/**
 * Utilities for use with the REST module.
 *
 * @package Square1-REST
 */
declare( strict_types=1 );

namespace Tribe\Project\REST\Utils;

class Utils {

	/**
	 * Helper to recursively modify keys.
	 *s
	 *
	 * @param iterable $iterable    The iterable to filter.
	 * @param callable $user_filter The filter function.
	 * @param callable $callback    The callback.
	 *
	 * @return iterable The filtered iterable.
	 */
	public static function deep_iterable_walk( $iterable, callable $user_filter, callable $callback ): iterable {
		foreach ( $iterable as $key => $value ) {
			if ( $user_filter( (string) $key ) ) {
				$iterable = $callback( $iterable, $key );
			} elseif ( is_array( $value ) ) {
				$iterable[ $key ] = self::deep_iterable_walk( $value, $user_filter, $callback );
			} elseif ( is_object( $value ) ) {
				$object_vars = get_object_vars( $value );

				foreach ( $object_vars as $property_name => $property_value ) {
					$value->$property_name = self::deep_iterable_walk( $property_value, $user_filter, $callback );
				}
			}
		}

		return $iterable;
	}
}
