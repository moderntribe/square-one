<?php

/**
 * Retrieve and render the template controller associated with
 * the given path
 *
 * @param string $path
 *
 * @return string
 */
function tribe_template( $path ) {
	$template = apply_filters( 'tribe/template/controller', null, $path );
	$renderer = apply_filters( 'tribe/template/renderer', null );

	return $renderer ? $renderer->render( $path, $template ) : '';
}
