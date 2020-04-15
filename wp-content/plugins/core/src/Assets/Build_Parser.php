<?php
declare( strict_types=1 );

namespace Tribe\Project\Assets;

abstract class Build_Parser {
	protected $css = '';
	protected $js  = '';

	/**
	 * @var bool
	 */
	private $debug;

	private $localize;

	private $styles;

	private $scripts;

	public function __construct( bool $debug = null ) {
		$this->debug = $debug ?? $this->doing_script_debug();
	}

	private function doing_script_debug(): bool {
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true;
	}

	private function init(): void {
		if ( ! isset( $this->localize ) ) {
			$this->parse_build_files();
		}
	}


	private function parse_build_files(): void {
		$this->localize = [ 'css' => [], 'js' => [] ];
		$this->scripts  = [];
		$this->styles   = [];

		$theme_directory = trailingslashit( get_template_directory() );
		$theme_uri       = trailingslashit( get_template_directory_uri() );

		$environment = $this->debug ? 'development' : 'production';
		$css         = file_exists( $theme_directory . $this->css ) ? include( $theme_directory . $this->css ) : [];
		$js          = file_exists( $theme_directory . $this->js ) ? include( $theme_directory . $this->js ) : [];

		if ( isset( $css['localize'] ) ) {
			$this->localize['css'] = $css['localize'];
		}
		if ( isset( $js['localize'] ) ) {
			$this->localize['js'] = $js['localize'];
		}

		if ( isset( $css['enqueue'][ $environment ] ) ) {
			$this->styles = array_map( function ( $asset ) use ( $theme_uri ) {
				$asset['uri'] = $theme_uri . $asset['file'];

				return $asset;
			}, $css['enqueue'][ $environment ] );
		}

		if ( isset( $js['enqueue'][ $environment ] ) ) {
			$this->scripts = array_map( function ( $asset ) use ( $theme_uri ) {
				$asset['uri'] = $theme_uri . $asset['file'];

				return $asset;
			}, $js['enqueue'][ $environment ] );
		}
	}

	public function get_localization(): array {
		$this->init();

		return $this->localize;
	}

	public function get_styles(): array {
		$this->init();

		return $this->styles;
	}

	public function get_scripts(): array {
		$this->init();

		return $this->scripts;
	}

	public function get_style_handles(): array {
		$this->init();

		return array_keys( $this->styles );
	}

	public function get_script_handles(): array {
		$this->init();

		return array_keys( $this->scripts );
	}

	public function get_legacy_style_handles(): array {
		$this->init();

		return array_filter( $this->get_style_handles(), function ( $handle ) {
			return strpos( $handle, 'legacy' ) !== false;
		} );
	}

	public function get_non_legacy_style_handles(): array {
		$this->init();

		return array_filter( $this->get_style_handles(), function ( $handle ) {
			return strpos( $handle, 'legacy' ) === false;
		} );
	}
}
