<?php
declare( strict_types=1 );

namespace Tribe\Project\Assets;

abstract class Build_Parser {
	/**
	 * @var string Path to the CSS build data file, relative to the theme directory
	 */
	protected $css = '';
	/**
	 * @var string Path to the JS build data file, relative to the theme directory
	 */
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

	protected function init(): void {
		if ( ! isset( $this->localize ) ) {
			$this->parse_build_files();
		}
	}

	protected function css_build_file(): string {
		$theme_directory = trailingslashit( get_template_directory() );

		return $theme_directory . $this->css;
	}

	protected function js_build_file(): string {
		$theme_directory = trailingslashit( get_template_directory() );

		return $theme_directory . $this->js;
	}


	private function parse_build_files(): void {
		$this->localize = [ 'css' => [], 'js' => [] ];
		$this->scripts  = [];
		$this->styles   = [];

		$theme_uri = trailingslashit( get_template_directory_uri() );

		$environment = $this->debug ? 'development' : 'production';
		$css         = file_exists( $this->css_build_file() ) ? include( $this->css_build_file() ) : [];
		$js          = file_exists( $this->js_build_file() ) ? include( $this->js_build_file() ) : [];

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
}
