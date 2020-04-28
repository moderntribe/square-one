<?php


namespace Tribe\Project\Theme\Config;


class Web_Fonts {
	public const  PROVIDER_TYPEKIT = 'typekit';
	public const  PROVIDER_GOOGLE  = 'google';
	public const  PROVIDER_CUSTOM  = 'custom';
	private const TYPEKIT_API      = 'https://use.typekit.net';
	private const GOOGLE_API       = 'https://fonts.googleapis.com';

	/**
	 * @var array
	 */
	private $fonts;

	public function __construct( array $fonts = [] ) {
		$this->fonts       = $fonts;
	}

	/**
	 * Enqueue any required web fonts
	 *
	 * @action wp_enqueue_scripts
	 * @action login_enqueue_scripts
	 */
	public function enqueue_fonts(): void {
		foreach ( $this->get_font_urls() as $provider => $url ) {
			wp_enqueue_style( $provider, $url, [], null, 'all' );
		}
	}

	/**
	 * Unsupported Browser Page Fonts.
	 *
	 * @action tribe/unsupported_browser/head
	 */
	public function inject_unsupported_browser_fonts(): void {
		foreach ( $this->get_font_urls() as $url ) {
			printf( "<link rel='stylesheet' href='%s' type='text/css' media='all'>\n\t", esc_url( $url ) );
		}
	}

	/**
	 * Visual Editor Fonts
	 *
	 * @action after_setup_theme
	 */
	public function add_visual_editor_fonts(): void {
		foreach( $this->get_font_urls() as $url ) {
			add_editor_style( $url );
		}
	}

	/**
	 * Setup the font URLs array for use throughout.
	 */
	private function get_font_urls(): array {
		$urls = [];
		// Typekit
		if ( ! empty( $this->fonts[ self::PROVIDER_TYPEKIT ] ) ) {
			$urls[ self::PROVIDER_TYPEKIT ] = $this->get_typekit_url();
		}

		// Google
		if ( ! empty( $this->fonts[ self::PROVIDER_GOOGLE ] ) ) {
			$urls[ self::PROVIDER_GOOGLE ] = $this->get_google_url();
		}

		// Custom
		if ( ! empty( $this->fonts[ self::PROVIDER_CUSTOM ] ) ) {
			$urls[ self::PROVIDER_CUSTOM ] = $this->fonts[ self::PROVIDER_CUSTOM ];
		}

		return $urls;
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
	 * Returns a fully-qualified Google Fonts URL from an array of font keys.
	 *
	 * @return string
	 */
	private function get_google_url(): string {
		if ( empty( $this->fonts[ self::PROVIDER_GOOGLE ] ) ) {
			return '';
		}

		return sprintf( '%scss?family=%s&display=swap', trailingslashit( self::GOOGLE_API ), implode( '|', $this->fonts[ self::PROVIDER_GOOGLE ] ) );
	}
}
