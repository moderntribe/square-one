<?php
declare( strict_types=1 );

namespace Tribe\Project\Integrations\Site_Brand;

use Tribe\Libs\Container\Abstract_Subscriber;

class Site_Brand_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		add_filter( 'customize_register', function ( $wp_customize ) {
			return $this->container->get( Customizer_Settings::class )->customize_customizer( $wp_customize );
		}, 20, 1 );

		add_filter( 'login_enqueue_scripts', function () {
			return $this->container->get( Login_Screen::class )->inject_login_logo();
		} );

		add_filter( 'login_headerurl', function ( $url ) {
			return $this->container->get( Login_Screen::class )->customize_login_header_url( $url );
		} );

		add_filter( 'login_headertext', function ( $name ) {
			return $this->container->get( Login_Screen::class )->customize_login_header_title( $name );
		} );

		add_filter( 'wp_head', function () {
			return $this->container->get( Brand_Meta::class )->inject_ie_metro_icon_bgd_color_meta();
		}, 10, 0 );

		add_filter( 'wp_head', function () {
			return $this->container->get( Brand_Meta::class )->inject_android_theme_color_meta();
		}, 10, 0 );
	}
}
