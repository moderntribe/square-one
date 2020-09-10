<?php
declare( strict_types=1 );

namespace Tribe\Project\Theme\Branding;

class Brand_Meta {
	/**
	 * Android Theme Color
	 *
	 * @action wp_head
	 */
	public function inject_android_theme_color_meta(): void {
		$theme_color = get_theme_mod( Customizer_Settings::SITE_BRANDING_ANDROID_THEME_COLOR, '#ffffff' );
		printf( '<meta name="theme-color" content="%s">', esc_attr( $theme_color ) );
	}
}
