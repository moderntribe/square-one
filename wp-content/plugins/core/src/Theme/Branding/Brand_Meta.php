<?php
declare( strict_types=1 );

namespace Tribe\Project\Theme\Branding;

class Brand_Meta {
	/**
	 * Android Theme Color
	 *
	 * @filter wp_head
	 */
	public function inject_android_theme_color_meta() {
		$theme_color = get_theme_mod( Customizer_Settings::SITE_BRANDING_ANDROID_THEME_COLOR, '#ffffff' );
		echo '<meta name="theme-color" content="' . $theme_color . '">';
	}
}
