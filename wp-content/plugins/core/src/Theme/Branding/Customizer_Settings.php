<?php
declare( strict_types=1 );

namespace Tribe\Project\Theme\Branding;

use WP_Customize_Color_Control;
use WP_Customize_Media_Control;

class Customizer_Settings {
	public const SITE_BRANDING_LOGIN_LOGO          = 'site_branding_login_logo';
	public const SITE_BRANDING_ANDROID_THEME_COLOR = 'site_branding_android_theme_color';

	/**
	 * Customize Customizer Settings
	 * @param $wp_customize
	 *
	 * @filter customize_register
	 */
	public function customize_customizer( $wp_customize ) {
		// Login Logo
		$wp_customize->add_setting( self::SITE_BRANDING_LOGIN_LOGO );
		$wp_customize->add_control( new WP_Customize_Media_Control(
			$wp_customize,
			self::SITE_BRANDING_LOGIN_LOGO,
			[
				'label'       => __( 'Site Login Logo', 'tribe' ),
				'description' => __( 'Recommended minimum width: 700px. Recommended file type: .png.', 'tribe' ),
				'section'     => 'title_tagline',
				'settings'    => self::SITE_BRANDING_LOGIN_LOGO,
				'mime_type'   => 'image',
				'priority'    => 70,
			]
		) );

		// Android Theme Color
		$wp_customize->add_setting( self::SITE_BRANDING_ANDROID_THEME_COLOR );
		$wp_customize->add_control( new WP_Customize_Color_Control(
			$wp_customize,
			self::SITE_BRANDING_ANDROID_THEME_COLOR,
			[
				'label'       => __( 'Android Theme Color', 'tribe' ),
				'description' => __( 'Select the theme color for android os while website is active in chrome.', 'tribe' ),
				'section'     => 'title_tagline',
				'settings'    => self::SITE_BRANDING_ANDROID_THEME_COLOR,
				'priority'    => 80,
			]
		) );
	}
}
