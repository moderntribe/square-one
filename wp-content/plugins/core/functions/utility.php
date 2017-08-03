<?php

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
	/** @var \Tribe\Libs\Assets\Asset_Loader $assets */
	$assets = tribe_project()->container()['assets'];
	return $assets->get_url( $path );
}
