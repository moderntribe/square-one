<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Page;

use Tribe\Project\Assets\Theme\Theme_Build_Parser;
use Tribe\Project\Templates\Abstract_Controller;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Page\Page_Unsupported_Browser as Page_Context;
use Tribe\Project\Templates\Controllers\Traits\Copyright;

class Page_Unsupported_Browser extends Abstract_Controller {
	use Copyright;

	/**
	 * @var Theme_Build_Parser
	 */
	private $build_parser;

	public function __construct(
		Component_Factory $factory,
		Theme_Build_Parser $build_parser
	) {
		parent::__construct( $factory );
		$this->build_parser = $build_parser;
	}


	public function render( string $path = '' ): string {
		return $this->factory->get( Page_Context::class, [
			Page_Context::COPYRIGHT    => $this->get_copyright(),
			Page_Context::HOME_URL     => home_url( '/' ),
			Page_Context::BLOG_NAME    => get_bloginfo( 'name' ),
			Page_Context::STYLES       => $this->get_styles(),
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

	protected function get_favicon(): string {
		return trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/theme/branding-assets/favicon.ico';
	}

	protected function get_styles(): string {
		$legacy_css = $this->build_parser->get_legacy_style_handles();
		ob_start();
		$GLOBALS['wp_styles']->do_items( $legacy_css );

		return ob_get_clean();
	}

	protected function get_legacy_page_title(): string {
		return sprintf( '%s %s', __( 'Welcome to', 'tribe' ), get_bloginfo( 'name' ) );
	}

	protected function get_legacy_page_content(): string {
		return sprintf(
			'%s <a href="http://browsehappy.com/" rel="external">%s</a>.',
			__( 'You are viewing this site in a browser that is no longer supported or secure. For the best possible experience, we recommend that you', 'tribe' ),
			__( 'update or use a modern browser', 'tribe' )
		);
	}

	protected function get_legacy_image_url( $filename ): string {
		if ( empty( $filename ) ) {
			return '';
		}

		return esc_url( trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/theme/legacy-browser/' . $filename );
	}
}
