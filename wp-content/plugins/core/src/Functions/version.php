<?php


function tribe_get_version() {
	if ( defined( 'BUILD_THEME_ASSETS_TIMESTAMP' ) ) {
		$version = BUILD_THEME_ASSETS_TIMESTAMP;
	} elseif ( isset( $GLOBALS[ 'wp_version' ] ) ) {
		$version = $GLOBALS[ 'wp_version' ];
	} else {
		$version = date( 'Y.m.d' );
	}
	return apply_filters( 'tribe_get_version', $version );
}