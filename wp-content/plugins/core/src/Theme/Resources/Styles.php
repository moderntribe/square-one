<?php


namespace Tribe\Project\Theme\Resources;


class Styles {
	/**
	 * Enqueue styles
	 * @action wp_enqueue_scripts
	 */
	public function enqueue_styles() {

		$theme_uri       = trailingslashit( get_stylesheet_directory_uri() );
		$script_debug    = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true;
		$css_assets_file = trailingslashit( get_template_directory() ) . 'assets/css/dist/theme/assets.php';
		$css_assets      = file_exists( $css_assets_file ) ? require( $css_assets_file ) : [];
		$css_assets      = $script_debug ? $css_assets['enqueue']['development'] : $css_assets['enqueue']['production'];

		foreach ( $css_assets as $handle => $asset ) {
			// todo: handle the legacy page
			if ( strpos( $handle, 'legacy' ) !== false ) {
				continue;
			}
			wp_enqueue_style( $handle, $theme_uri . $asset['file'], $asset['dependencies'], $asset['version'], $asset['media'] );
		}

	}
}
