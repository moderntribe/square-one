<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\routes\unsupported_browser;

use Tribe\Project\Components\Component;
use Tribe\Project\Templates\Factory_Method;
use Tribe\Project\Assets\Theme\Theme_Build_Parser;

class Controller{

	use Factory_Method;

	public $home_url;
	public $name;
	public $styles;
	public $favicon;
	public $legacy_browser_title;
	public $legacy_browser_content;
	public $legacy_logo_header;
	public $legacy_logo_footer;
	public $legacy_browser_icon_chrome;
	public $legacy_browser_icon_firefox;
	public $legacy_browser_icon_safari;
	public $legacy_browser_icon_ie;

	private $build_parser;

	public function __construct()  {
		$this->home_url = home_url( '/' );
		$this->name    = get_bloginfo( 'name' );
		$this->styles       = $this->get_styles();
		$this->favicon      = $this->get_favicon();
		$this->legacy_browser_title        = $this->get_legacy_page_title();
		$this->legacy_browser_content      = $this->get_legacy_page_content();
		$this->legacy_logo_header  = $this->get_legacy_image_url( 'logo-header.png' );
		$this->legacy_logo_footer  = $this->get_legacy_image_url( 'logo-footer.png' );
		$this->legacy_browser_icon_chrome  = $this->get_legacy_image_url( 'chrome.png' );
		$this->legacy_browser_icon_firefox = $this->get_legacy_image_url( 'firefox.png' );
		$this->legacy_browser_icon_safari  = $this->get_legacy_image_url( 'safari.png' );
		$this->legacy_browser_icon_ie      = $this->get_legacy_image_url( 'ie.png' );

		$this->build_parser = new Theme_Build_Parser();

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
		return sprintf( '%s <a href="http://browsehappy.com/" rel="external">%s</a>.', __( 'You are viewing this site in a browser that is no longer supported or secure. For the best possible experience, we recommend that you', 'tribe' ), __( 'update or use a modern browser', 'tribe' ) );
	}

	protected function get_legacy_image_url( $filename ): string {
		if ( empty( $filename ) ) {
			return '';
		}

		return esc_url( trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/theme/legacy-browser/' . $filename );
	}

}
