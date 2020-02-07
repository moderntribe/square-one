<?php

/**
 * Retrieve and render the template controller associated with
 * the given path
 *
 * @param string $controller Class name/container key of the template controller
 *
 * @return string
 */
function tribe_template( string $controller ) {
	$container = tribe_project()->template_container();
	try {
		return $container->get( $controller )->render();
	} catch ( \Exception $e ) {
		return '<pre>' . print_r( $e, true ) . '</pre>';
	}
}
