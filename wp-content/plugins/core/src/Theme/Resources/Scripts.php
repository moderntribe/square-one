<?php


namespace Tribe\Project\Theme\Resources;


class Scripts {
	public function add_early_polyfills() {
		$js_dir  = trailingslashit( get_stylesheet_directory_uri() ) . 'assets/js/';
		?>
		<script>window.Promise ||
			document.write('<script src="<?php echo esc_url( $js_dir ); ?>vendor/es6-promise.auto.js"><\/script>');
		</script>
		<?php
	}

	/**
	 * Output bugsnag code
	 */

	public function maybe_inject_bugsnag() {
		if ( ! defined( 'BUGSNAG_API_KEY' ) ) {
			return;
		}
		?>
		<script src="//d2wy8f7a9ursnm.cloudfront.net/v5/bugsnag.min.js"></script>
		<script>window.bugsnagClient = bugsnag( '<?php echo esc_html( BUGSNAG_API_KEY ); ?>' );</script>
		<?php
	}

	/**
	 * Output preload directives in head for scripts in footer
	 */

	public function set_preloading_tags() {
		global $wp_scripts;

		foreach ( $wp_scripts->queue as $handle ) {
			$script = $wp_scripts->registered[ $handle ];

			//-- Weird way to check if script is being enqueued in the footer.
			if ( isset( $script->extra[ 'group' ] ) && $script->extra[ 'group' ] === 1 ) {

				//-- If version is set, append to end of source.
				$source = $script->src . ( $script->ver ? "?ver={$script->ver}" : "" );

				//-- Spit out the tag.
				echo "<link rel='preload' href='{$source}' as='script'/>\n";
			}
		}
	}
	/**
	 * Enqueue scripts
	 * @action wp_enqueue_scripts
	 */
	public function enqueue_scripts() {

		// todo: jonathan, please patch this area, just rough sketch
		$theme_uri    = trailingslashit( get_stylesheet_directory_uri() );
		$js_uri       = $theme_uri . 'assets/js/';
		$script_debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true;
		// todo: what do we do about versions for non webpack files, should usually just be jquery which is very static
		$version        = tribe_get_version();
		$js_assets_file = trailingslashit( get_template_directory() ) . 'assets/js/dist/theme/assets.php';
		$script_assets  = file_exists( $js_assets_file ) ? require( $js_assets_file ) : [];
		// todo: just temp unsafe for testing
		$script_assets  = $script_debug ? $script_assets[ 'enqueue' ][ 'development' ] : $script_assets[ 'enqueue' ][ 'production' ];
		$jquery         = $script_debug ? 'vendor/jquery.js' : 'vendor/jquery.min.js';

		// Custom jQuery (todo: strange game to get localize script attached to it, please patch/change as needed)
		wp_deregister_script( 'jquery' );
		wp_deregister_script( 'jquery-core' );

		wp_enqueue_script( 'jquery-core', $js_uri . $jquery, [], $version, true );

		$js_config = new JS_Config();
		$js_l10n = new JS_Localization();
		// weird issue with wp and needing the jquery-core handle
		wp_localize_script( 'jquery-core', 'modern_tribe_i18n', $js_l10n->get_data() );
		wp_localize_script( 'jquery-core', 'modern_tribe_config', $js_config->get_data() );

		wp_register_script( 'jquery', false, [ 'jquery-core' ], $version, true );

		foreach ( $script_assets as $handle => $asset ) {
			wp_enqueue_script( $handle, $theme_uri . $asset['file'], $asset['dependencies'], $asset['version'], true );
		}

		if ( defined( 'HMR_DEV' ) && HMR_DEV === true ) {
			wp_enqueue_script( 'core-theme-hmr-bundle', 'https://localhost:3000/app.js', [ 'core-theme-scripts' ], $version, true );
		}

		// JS: Comments
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

	}
}
