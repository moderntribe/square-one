<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Pages;

use Tribe\Project\Templates\Abstract_Controller;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Pages\Page_Unsupported_Browser as Page_Context;
use Tribe\Project\Templates\Controllers\Traits\Copyright;
use Twig\Environment;

class Page_Unsupported_Browser extends Abstract_Controller {
	use Copyright;

	public function __construct(
		Environment $twig,
		Component_Factory $factory
	) {
		parent::__construct( $twig, $factory );
	}

	public function render( string $path = '' ): string {
		return $this->factory->get( Page_Context::class, [
			Page_Context::COPYRIGHT    => $this->get_copyright(),
			Page_Context::HOME_URL     => home_url( '/' ),
			Page_Context::BLOG_NAME    => get_bloginfo( 'name' ),
			Page_Context::CSS          => $this->get_css(),
			Page_Context::FAVICON      => $this->get_favicon(),
			Page_Context::TITLE        => $this->get_legacy_page_title(),
			Page_Context::CONTENT      => $this->get_legacy_page_content(),
			Page_Context::LOGO_HEADER  => $this->get_legacy_image_url( 'logo-header.png' ),
			Page_Context::LOGO_FOOTER  => $this->get_legacy_image_url( 'logo-footer.png' ),
			Page_Context::ICON_CHROME  => $this->get_legacy_image_url( 'chrome.png' ),
			Page_Context::ICON_FIREFOX => $this->get_legacy_image_url( 'firefox.png' ),
			Page_Context::ICON_SAFARI  => $this->get_legacy_image_url( 'safari.png' ),
			Page_Context::ICON_IE      => $this->get_legacy_image_url( 'ie.png' ),
		] )->render( $path );
	}

	protected function get_favicon() {
		return trailingslashit( get_stylesheet_directory_uri() ) . 'img/branding/favicon.ico';
	}

	protected function get_css() {
		$css_dir    = trailingslashit( get_stylesheet_directory_uri() ) . 'css/';
		$css_legacy = ( ! defined( 'SCRIPT_DEBUG' ) || SCRIPT_DEBUG === false ) ? 'dist/legacy.min.css' : 'legacy.css';

		return $css_dir . $css_legacy;
	}

	protected function get_legacy_page_title() {
		return sprintf( '%s %s', __( 'Welcome to', 'tribe' ), get_bloginfo( 'name' ) );
	}

	protected function get_legacy_page_content() {
		return sprintf(
			'%s <a href="http://browsehappy.com/" rel="external">%s</a>.',
			__( 'You are viewing this site in a browser that is no longer supported or secure. For the best possible experience, we recommend that you', 'tribe' ),
			__( 'update or use a modern browser', 'tribe' )
		);
	}

	protected function get_legacy_image_url( $filename ) {
		if ( empty( $filename ) ) {
			return false;
		}

		return esc_url( trailingslashit( get_stylesheet_directory_uri() ) . 'img/theme/legacy-browser/' . $filename );
	}
}
