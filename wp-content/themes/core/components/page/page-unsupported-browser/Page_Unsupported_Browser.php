<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Page;

use Tribe\Project\Templates\Components\Component;
use Tribe\Project\Templates\Controllers\Traits\Copyright;

class Page_Unsupported_Browser extends Component {

	use Copyright;

	public const COPYRIGHT    = 'copyright';
	public const HOME_URL     = 'home_url';
	public const BLOG_NAME    = 'name';
	public const STYLES       = 'styles';
	public const FAVICON      = 'favicon';
	public const TITLE        = 'legacy_browser_title';
	public const CONTENT      = 'legacy_browser_content';
	public const LOGO_HEADER  = 'legacy_logo_header';
	public const LOGO_FOOTER  = 'legacy_logo_footer';
	public const ICON_CHROME  = 'legacy_browser_icon_chrome';
	public const ICON_FIREFOX = 'legacy_browser_icon_firefox';
	public const ICON_SAFARI  = 'legacy_browser_icon_safari';
	public const ICON_IE      = 'legacy_browser_icon_ie';

	public function init() {
		$this->data[ self::COPYRIGHT ]    = $this->get_copyright();
		$this->data[ self::HOME_URL ]     = home_url( '/' );
		$this->data[ self::BLOG_NAME ]    = get_bloginfo( 'name' );
		$this->data[ self::STYLES ]       = $this->get_styles();
		$this->data[ self::FAVICON ]      = $this->get_favicon();
		$this->data[ self::TITLE ]        = $this->get_legacy_page_title();
		$this->data[ self::CONTENT ]      = $this->get_legacy_page_content();
		$this->data[ self::LOGO_HEADER ]  = $this->get_legacy_image_url( 'logo-header.png' );
		$this->data[ self::LOGO_FOOTER ]  = $this->get_legacy_image_url( 'logo-footer.png' );
		$this->data[ self::ICON_CHROME ]  = $this->get_legacy_image_url( 'chrome.png' );
		$this->data[ self::ICON_FIREFOX ] = $this->get_legacy_image_url( 'firefox.png' );
		$this->data[ self::ICON_SAFARI ]  = $this->get_legacy_image_url( 'safari.png' );
		$this->data[ self::ICON_IE ]      = $this->get_legacy_image_url( 'ie.png' );
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
