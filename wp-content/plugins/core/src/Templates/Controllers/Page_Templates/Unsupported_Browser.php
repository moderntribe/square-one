<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Page_Templates;

use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Pages\Page_Unsupported_Browser;
use Tribe\Project\Templates\Controllers\Content\Header\Subheader;
use Tribe\Project\Templates\Controllers\Footer;
use Tribe\Project\Templates\Controllers\Header;
use Tribe\Project\Templates\Controllers\Sidebar\Main_Sidebar;
use Tribe\Project\Templates\Controllers\Traits\Copyright;
use Twig\Environment;

class Unsupported_Browser extends Abstract_Template {
	use Copyright;

	public function __construct(
		Environment $twig,
		Component_Factory $factory
	) {
		parent::__construct( $twig, $factory );
	}

	public function render( string $path = '' ): string {
		return $this->factory->get( Page_Unsupported_Browser::class, [
			Page_Unsupported_Browser::COPYRIGHT    => $this->get_copyright(),
			Page_Unsupported_Browser::HOME_URL     => home_url( '/' ),
			Page_Unsupported_Browser::BLOG_NAME    => get_bloginfo( 'name' ),
			Page_Unsupported_Browser::CSS          => $this->get_css(),
			Page_Unsupported_Browser::FAVICON      => $this->get_favicon(),
			Page_Unsupported_Browser::TITLE        => $this->get_legacy_page_title(),
			Page_Unsupported_Browser::CONTENT      => $this->get_legacy_page_content(),
			Page_Unsupported_Browser::LOGO_HEADER  => $this->get_legacy_image_url( 'logo-header.png' ),
			Page_Unsupported_Browser::LOGO_FOOTER  => $this->get_legacy_image_url( 'logo-footer.png' ),
			Page_Unsupported_Browser::ICON_CHROME  => $this->get_legacy_image_url( 'chrome.png' ),
			Page_Unsupported_Browser::ICON_FIREFOX => $this->get_legacy_image_url( 'firefox.png' ),
			Page_Unsupported_Browser::ICON_SAFARI  => $this->get_legacy_image_url( 'safari.png' ),
			Page_Unsupported_Browser::ICON_IE      => $this->get_legacy_image_url( 'ie.png' ),
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
