<?php

namespace Tribe\Project\Components_Docs;

abstract class Item {
	abstract public function get_slug(): string;

	abstract public function get_label(): string;

	abstract public function get_constants(): array;

	abstract public function get_sales_docs(): string;

	abstract public function get_dev_docs(): string;

	abstract public function get_twig_src(): string;

	abstract public function get_rendered_template( $options = [] ): string;

	protected function get_home_path() {
		$home    = set_url_scheme( get_option( 'home' ), 'http' );
		$siteurl = set_url_scheme( get_option( 'siteurl' ), 'http' );

		if ( ! empty( $home ) && 0 !== strcasecmp( $home, $siteurl ) ) {
			$wp_path_rel_to_home = str_ireplace( $home, '', $siteurl ); /* $siteurl - $home */
			$pos                 = strripos( str_replace( '\\', '/', $_SERVER['SCRIPT_FILENAME'] ), trailingslashit( $wp_path_rel_to_home ) );
			$home_path           = substr( $_SERVER['SCRIPT_FILENAME'], 0, $pos );
			$home_path           = trailingslashit( $home_path );
		} else {
			$home_path = ABSPATH;
		}

		$home_path = str_replace( '/wp/', '/', $home_path );

		return str_replace( '\\', '/', $home_path );
	}

	protected function cleanup_html( string $html ) {
		$config = [ 'tidy' => '1t0n' ];
		return \Htmlawed::filter( $html, $config );
	}
}