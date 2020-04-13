<?php


namespace Tribe\Project\Theme\Resources;


class Styles {
	/**
	 * Enqueue styles
	 * @action wp_enqueue_scripts
	 */
	public function enqueue_styles() {

		$site_url       = trailingslashit( get_site_url() );
		$script_debug   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true;
		$css_path        = trailingslashit( get_template_directory() ) . 'assets/css/dist/theme/';
		$css_assets_file = $script_debug ? $css_path . 'assets.dev.php' : $css_path . 'assets.prod.php';
		$css_assets  = file_exists( $css_assets_file ) ? require( $css_assets_file ) : [];

		unset( $css_assets['chunks'] );

		foreach ( $css_assets as $handle => $asset ) {
			// todo: oh boy how to handle the legacy page better
			if ( strpos( $asset['file'][0], 'legacy' ) !== false ) {
				continue;
			}
			// todo: oh boy this one is weird to solve, very hard to define at webpack side
			$media = strpos( $asset['file'][0], 'print' ) !== false ? 'print' : 'all';
			wp_enqueue_style( $handle, $site_url . $asset['file'][0], $asset['dependencies'], $asset['version'], $media );
		}

	}
}
