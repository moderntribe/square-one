<?php declare(strict_types=1);

namespace Tribe\Project\Assets\Theme;

use _WP_Dependency;

class Scripts {

	private JS_Config $config;
	private JS_Localization $localization;
	private Theme_Build_Parser $build_parser;

	public function __construct( Theme_Build_Parser $build_parser, JS_Config $config, JS_Localization $localization ) {
		$this->build_parser = $build_parser;
		$this->config       = $config;
		$this->localization = $localization;
	}

	/**
	 * @return void
	 *
	 * @action wp_footer
	 */
	public function add_early_polyfills(): void {
		$js_dir = trailingslashit( get_stylesheet_directory_uri() ) . 'assets/js/';
		?>
		<script>window.Promise ||
			document.write('<script src="<?php echo esc_url( $js_dir ); ?>vendor/es6-promise.auto.js"><\/script>');
		</script>
		<?php
	}

	/**
	 * Output bugsnag code
	 *
	 * @action wp_head
	 */
	public function maybe_inject_bugsnag(): void {
		if ( ! defined( 'BUGSNAG_API_KEY' ) ) {
			return;
		}
		?>
		<script src="https://d2wy8f7a9ursnm.cloudfront.net/v5/bugsnag.min.js"></script>
		<script>window.bugsnagClient = bugsnag(<?php echo json_encode( BUGSNAG_API_KEY ); ?>);</script>
		<?php
	}

	/**
	 * Output preload directives in head for scripts in footer
	 *
	 * Supports preloading dependencies of aliases (scripts with dependencies and no source URL).
	 *
	 * @action wp_head
	 */
	public function set_preloading_tags(): void {
		global $wp_scripts;

		foreach ( $wp_scripts->queue as $handle ) {
			$script = $wp_scripts->registered[ $handle ];

			//-- Weird way to check if script is being enqueued in the footer.
			if ( ! isset( $script->extra['group'] ) || $script->extra['group'] !== 1 ) {
				continue;
			}

			// If not an alias, print preload tag.
			if ( ! empty( $script->src ) ) {
				$this->print_preload_tag( $script );

				continue;
			}

			// If source is empty and no dependencies, then not an alias: skip (nothing to preload).
			if ( empty( $script->deps ) ) {
				continue;
			}

			// If an alias, preload set dependencies.
			foreach ( $script->deps as $dep_handle ) {
				if ( ! isset( $wp_scripts->registered[ $dep_handle ] ) ) {
					continue;
				}

				$this->print_preload_tag( $wp_scripts->registered[ $dep_handle ] );
			}
		}
	}

	/**
	 * @return void
	 *
	 * @action template_redirect
	 */
	public function register_scripts(): void {
		$script_debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true;

		$this->replace_jquery( $script_debug );

		// If constant is true, set version to current timestamp, forcing cache invalidation on every page load
		$timestamp = defined( 'ASSET_VERSION_TIMESTAMP' ) && ASSET_VERSION_TIMESTAMP === true ? time() : null;
		foreach ( $this->build_parser->get_scripts() as $handle => $asset ) {
			wp_register_script( $handle, $asset['uri'], $asset['dependencies'], $timestamp ?? $asset['version'], true );
		}
	}

	/**
	 * Enqueue scripts
	 *
	 * @action wp_enqueue_scripts
	 */
	public function enqueue_scripts(): void {
		$handles = $this->build_parser->get_script_handles();

		if ( ! empty( $handles ) ) {
			foreach ( $handles as $handle ) {
				wp_enqueue_script( $handle );
			}

			$this->localize_scripts( (string) reset( $handles ) );

			if ( defined( 'HMR_DEV' ) && HMR_DEV === true ) {
				wp_enqueue_script( 'tribe-scripts-hmr-bundle', home_url() . ':9000/app.js', $handles, time(), true );
			}
		}

		// JS: Comments
		if ( ! is_singular() || ! comments_open() || ! get_option( 'thread_comments' ) ) {
			return;
		}

		wp_enqueue_script( 'comment-reply' );
	}

	protected function print_preload_tag( _WP_Dependency $script ): void {
		//-- If version is set, append to end of source.
		$source = $script->src . ( $script->ver ? "?ver={$script->ver}" : "" );

		//-- Spit out the tag.
		echo "<link rel='preload' href='{$source}' as='script'/>\n";
	}

	/**
	 * Replaces jQuery from WP core with our updated version
	 *
	 * @param bool $script_debug
	 *
	 * @return void
	 */
	private function replace_jquery( bool $script_debug ): void {
		$version = '3.4.1';
		$suffix  = $script_debug ? '.js' : '.min.js';
		$url     = trailingslashit( get_template_directory_uri() ) . 'assets/js/vendor/jquery' . $suffix;

		wp_deregister_script( 'jquery' );
		wp_deregister_script( 'jquery-core' );

		wp_register_script( 'jquery-core', $url, [], $version, true );
		wp_register_script( 'jquery', false, [ 'jquery-core' ], $version, true ); // alias to jquery-core
	}

	private function localize_scripts( string $handle ): void {
		wp_localize_script( $handle, 'modern_tribe_i18n', $this->localization->get_data() );
		wp_localize_script( $handle, 'modern_tribe_config', $this->config->get_data() );
	}

}
