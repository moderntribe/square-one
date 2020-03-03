<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Page_Templates;

use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Controllers\Content\Header\Subheader;
use Tribe\Project\Templates\Controllers\Footer;
use Tribe\Project\Templates\Controllers\Header;
use Tribe\Project\Templates\Controllers\Sidebar\Main_Sidebar;
use Tribe\Project\Templates\Controllers\Traits\Copyright;
use Twig\Environment;

class Unsupported_Browser extends Abstract_Template {
	use Copyright;

	protected $path = 'page-templates/page-unsupported-browser.twig';

	/**
	 * @var Header
	 */
	private $header;
	/**
	 * @var Subheader
	 */
	private $subheader;
	/**
	 * @var Main_Sidebar
	 */
	private $sidebar;
	/**
	 * @var Footer
	 */
	private $footer;

	public function __construct(
		Environment $twig,
		Component_Factory $factory
	) {
		parent::__construct( $twig, $factory );
	}


	public function get_data(): array {
		the_post();
		$data = [
			'copyright'                   => $this->get_copyright(),
			'home_url'                    => home_url( '/' ),
			'name'                        => get_bloginfo( 'name' ),
			'css'                         => $this->get_css(),
			'favicon'                     => $this->get_favicon(),
			'legacy_browser_title'        => $this->get_legacy_page_title(),
			'legacy_browser_content'      => $this->get_legacy_page_content(),
			'legacy_logo_header'          => $this->get_legacy_image_url( 'logo-header.png' ),
			'legacy_logo_footer'          => $this->get_legacy_image_url( 'logo-footer.png' ),
			'legacy_browser_icon_chrome'  => $this->get_legacy_image_url( 'chrome.png' ),
			'legacy_browser_icon_firefox' => $this->get_legacy_image_url( 'firefox.png' ),
			'legacy_browser_icon_safari'  => $this->get_legacy_image_url( 'safari.png' ),
			'legacy_browser_icon_ie'      => $this->get_legacy_image_url( 'ie.png' ),
		];


		return $data;
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
