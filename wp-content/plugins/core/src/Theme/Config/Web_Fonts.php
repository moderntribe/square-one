<?php


namespace Tribe\Project\Theme\Config;


class Web_Fonts {
	public const  PROVIDER_TYPEKIT = 'typekit';
	public const  PROVIDER_GOOGLE  = 'google';
	public const  PROVIDER_CUSTOM  = 'custom';
	private const TYPEKIT_API      = 'https://use.typekit.net';
	private const GOOGLE_API       = 'https://fonts.googleapis.com';

	/**
	 * @var string
	 */
	private $plugin_file;

	/**
	 * @var array
	 */
	private $fonts;

	/**
	 * @var array
	 */
	private $font_urls = [];

	public function __construct( string $plugin_file, array $fonts = [] ) {
		$this->plugin_file = $plugin_file;
		$this->fonts       = $fonts;
		$this->set_font_urls();
	}

	/**
	 * Enqueue any required web fonts
	 *
	 * @action wp_enqueue_scripts
	 * @action login_enqueue_scripts
	 */
	public function enqueue_fonts() {
		foreach ( $this->font_urls as $provider => $url ) {
			wp_enqueue_style( $provider, $url, [], null, 'all' );
		}
	}

	/**
	 * Unsupported Browser Page Fonts.
	 *
	 * @action tribe/unsupported_browser/head
	 */
	public function inject_unsupported_browser_fonts() {
		foreach ( $this->font_urls as $url ) {
			printf( "<link rel='stylesheet' href='%s' type='text/css' media='all'>\n\t", esc_url( $url ) );
		}
	}

	/**
	 * Visual Editor Fonts
	 *
	 * @action after_setup_theme
	 */
	public function add_visual_editor_fonts() {
		foreach( $this->font_urls as $url ) {
			add_editor_style( $url );
		}
	}

	/**
	 * Setup the font URLs array for use throughout.
	 */
	private function set_font_urls() {
		// Typekit
		if ( ! empty( $this->fonts[ self::PROVIDER_TYPEKIT ] ) ) {
			$this->font_urls[ self::PROVIDER_TYPEKIT ] = $this->get_typekit_url();
		}

		// Google
		if ( ! empty( $this->fonts[ self::PROVIDER_GOOGLE ] ) ) {
			$this->font_urls[ self::PROVIDER_GOOGLE ] = $this->get_google_url();
		}

		// Custom
		if ( ! empty( $this->fonts[ self::PROVIDER_CUSTOM ] ) ) {
			$this->font_urls[ self::PROVIDER_CUSTOM ] = $this->fonts[ self::PROVIDER_CUSTOM ];
		}
	}

	/**
	 * Returns a fully-qualified Typekit font kit URL from the Project ID value.
	 *
	 * @return string
	 */
	private function get_typekit_url(): string {
		if ( empty( $this->fonts[ self::PROVIDER_TYPEKIT ] ) ) {
			return '';
		}

		return sprintf( '%s%s.css', trailingslashit( self::TYPEKIT_API ), $this->fonts[ self::PROVIDER_TYPEKIT ] );
	}

	/**
	 * Returns a fully-qualified Typekit font kit URL from the Project ID value.
	 *
	 * @return string
	 */
	private function get_google_url() {
		if ( empty( $this->fonts[ self::PROVIDER_GOOGLE ] ) ) {
			return '';
		}

		return sprintf( '%scss?family=%s&display=swap', trailingslashit( self::GOOGLE_API ), implode( '|', $this->fonts[ self::PROVIDER_GOOGLE ] ) );
	}
}
