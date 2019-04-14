<?php


namespace Tribe\Project\Theme\Resources;


class Scripts {
	public function add_early_polyfills() {
		$js_dir  = trailingslashit( get_stylesheet_directory_uri() ) . 'js/';
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

		$js_dir  = trailingslashit( get_stylesheet_directory_uri() ) . 'js/';
		$version = tribe_get_version();

		// Custom jQuery (version 2.2.4, IE9+)
		wp_deregister_script( 'jquery' );

		if ( ! defined( 'SCRIPT_DEBUG' ) || SCRIPT_DEBUG === false ) { // Production
			$jquery          = 'vendor/jquery.min.js';
			$scripts         = 'dist/scripts.min.js';
			$localize_target = 'core-theme-scripts';
			$script_deps     = [ 'core-webpack-vendors' ];
			wp_enqueue_script( 'core-webpack-vendors', $js_dir . 'dist/vendor.min.js', ['jquery'], $version, true );
		} else { // Dev
			// Dev
			$scripts         = 'dist/scripts.js';
			$jquery          = 'vendor/jquery.js';
			$localize_target = 'core-globals';
			$script_deps     = [ 'jquery', 'core-webpack-vendors' ];

			wp_enqueue_script( 'core-globals', $js_dir . 'vendor/globals.js', [], $version, true );
			wp_enqueue_script( 'core-swiper', $js_dir . 'vendor/swiper.js', ['core-globals'], $version, true );
			wp_enqueue_script( 'core-lazysizes-object-fit', $js_dir . 'vendor/ls.object-fit.js', ['core-swiper'], $version, true );
			wp_enqueue_script( 'core-lazysizes-parent-fit', $js_dir . 'vendor/ls.parent-fit.js', ['core-lazysizes-object-fit'], $version, true );
			wp_enqueue_script( 'core-lazysizes-polyfill', $js_dir . 'vendor/ls.respimg.js', ['core-lazysizes-parent-fit'], $version, true );
			wp_enqueue_script( 'core-lazysizes-bgset', $js_dir . 'vendor/ls.bgset.js', ['core-lazysizes-polyfill'], $version, true );
			wp_enqueue_script( 'core-lazysizes', $js_dir . 'vendor/lazysizes.js', ['core-lazysizes-bgset'], $version, true );
			wp_enqueue_script( 'core-webpack-vendors', $js_dir . 'dist/vendor.js', ['core-lazysizes'], $version, true );
		}

		wp_register_script( 'jquery', $js_dir . $jquery, [], $version, false );

		wp_enqueue_script( 'core-theme-scripts', $js_dir . $scripts, $script_deps, $version, true );

		$js_config = new JS_Config();
		$js_l10n = new JS_Localization();
		wp_localize_script( $localize_target, 'modern_tribe_i18n', $js_l10n->get_data() );
		wp_localize_script( $localize_target, 'modern_tribe_config', $js_config->get_data() );

		wp_enqueue_script( 'core-theme-scripts' );

		if ( defined( 'HMR_DEV' ) && HMR_DEV === true ) {
			wp_enqueue_script( 'core-theme-hmr-bundle', 'https://localhost:3000/app.js', [ 'core-theme-scripts' ], $version, true );
		}

		// Accessibility Testing
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true ) {
			wp_enqueue_script( 'core-theme-totally', $js_dir . 'vendor/tota11y.min.js', [ 'core-theme-scripts' ], $version, true );
		}

		// JS: Comments
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

	}
}
