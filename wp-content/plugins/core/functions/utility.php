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
	return tribe_project()->container()['theme.resources.template_tags']->get_assets_url( $path );
}
