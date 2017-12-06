<?php


namespace Tribe\Project\Templates;

class Unsupported_Browser extends Base {

	public function get_data(): array {
		$data                                = parent::get_data();
		$data['post']                        = $this->get_post();
		$data['css']                         = $this->get_css();
		$data['favicon']                     = $this->get_favicon();
		$data['legacy_browser_title']        = $this->get_legacy_page_title();
		$data['legacy_browser_content']      = $this->get_legacy_page_content();
		$data['legacy_logo_header']          = $this->get_legacy_image_url( 'logo-header.png' );
		$data['legacy_logo_footer']          = $this->get_legacy_image_url( 'logo-footer.png' );
		$data['legacy_browser_icon_chrome']  = $this->get_legacy_image_url( 'chrome.png' );
		$data['legacy_browser_icon_firefox'] = $this->get_legacy_image_url( 'firefox.png' );
		$data['legacy_browser_icon_safari']  = $this->get_legacy_image_url( 'safari.png' );
		$data['legacy_browser_icon_ie']      = $this->get_legacy_image_url( 'ie.png' );

		return $data;
	}

	protected function get_post() {
		return [];
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
		return sprintf( '%s %s', __(  'Welcome to', 'tribe' ), get_bloginfo( 'name' ) );
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
