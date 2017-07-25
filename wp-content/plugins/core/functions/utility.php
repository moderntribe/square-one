<?php
/**
 * Check if a multidimensional array is empty
 * If passed keys it will check for value on each key
 * and will return true if any of them are empty
 *
 * @param array|object      $data
 * @param array|bool|string $keys_to_check
 *
 * @return bool
 */
function is_array_empty( $data, $keys_to_check = false ) {

	$data = array_filter( (array)$data );
	if ( empty( $data ) ) {
		return true;
	}

	if ( ! empty( $keys_to_check ) ) {
		$keys_to_check = (array)$keys_to_check;
	}

	foreach ( $data as $key => $_item ) {
		if ( is_array( $_item ) || is_object( $_item ) ) {
			if ( ! is_array_empty( $_item, $keys_to_check ) ) {
				return false;
			}
		} else {
			if ( $keys_to_check ) {
				if ( in_array( $key, $keys_to_check ) ) {
					if ( empty( $_item ) ) {
						return true;
					}
					unset( $keys_to_check[ array_search( $key, $keys_to_check ) ] );
					if ( empty( $keys_to_check ) ) {
						return false;
					}
				}
			} else {
				if ( ! empty( $_item ) ) {
					return false;
				}
			}
		}
	}

	return true;
}

/**
 * Returns the URL http://example.com/wp-content/plugins/core/assets/. Optionally, $path can be passed and will be appended.
 * For example, if `get_assets_url( 'theme/img/shims' )` is used, the URL http://example.com/wp-content/plugins/core/assets/theme/img/shims/
 * will be returned.
 *
 * @param string $path
 *
 * @return string
 */
function get_assets_url( $path = '' ) {
	return tribe_project()->container()['theme.resources.template_tags']->get_assets_url( $path );
}
