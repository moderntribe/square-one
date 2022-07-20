<?php declare(strict_types=1);

namespace Tribe\Project\Theme\Config;

/**
 * @TODO rename to a proper class
 */
class Web_Fonts {

	public const  PROVIDER_CUSTOM  = 'custom';
	public const  PROVIDER_GOOGLE  = 'google';
	public const  PROVIDER_TYPEKIT = 'typekit';
	private const TYPEKIT_API      = 'https://use.typekit.net';

	/**
	 * @var array<string, string>
	 */
	private array $fonts;

	public function __construct( array $fonts = [] ) {
		$this->fonts = $fonts;
	}

	/**
	 * Enqueue any required web fonts
	 *
	 * @action wp_enqueue_scripts
	 * @action login_enqueue_scripts
	 * @action enqueue_block_editor_assets
	 */
	public function enqueue_fonts(): void {
		foreach ( $this->get_font_urls() as $provider => $url ) {
			wp_enqueue_style( 'tribe-' . $provider, $url, [], null, 'all' );
		}
	}

	/**
	 * TinyMCE Editor Fonts
	 *
	 * @action after_setup_theme
	 */
	public function add_tinymce_editor_fonts(): void {
		foreach ( $this->get_font_urls() as $url ) {
			add_editor_style( $url );
		}
	}

	/**
	 * Set up the font URLs array for use throughout.
	 */
	private function get_font_urls(): array {
		$urls = [];

		// Typekit
		if ( ! empty( $this->fonts[ self::PROVIDER_TYPEKIT ] ) ) {
			$urls[ self::PROVIDER_TYPEKIT ] = $this->get_typekit_url();
		}

		// Google
		if ( ! empty( $this->fonts[ self::PROVIDER_GOOGLE ] ) ) {
			$urls[ self::PROVIDER_GOOGLE ] = $this->fonts[ self::PROVIDER_GOOGLE ];
		}

		// Custom
		foreach ( $this->fonts[ self::PROVIDER_CUSTOM ] ?? [] as $index => $custom_url ) {
			$urls[ self::PROVIDER_CUSTOM . '-' . $index ] = $custom_url;
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

}
