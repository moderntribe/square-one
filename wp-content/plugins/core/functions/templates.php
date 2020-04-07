<?php

/**
 * Retrieve and render the template controller associated with
 * the given path
 *
 * @param string $controller Class name/container key of the template controller
 * @param string $path       Path override for rendering the template
 *
 * @return string
 */
function tribe_template( string $controller, string $path = '' ) {
	$container = tribe_project()->container();
	try {
		return $container->get( $controller )->render( $path );
	} catch ( \Exception $e ) {
		return '<pre>' . print_r( $e, true ) . '</pre>';
	}
}
