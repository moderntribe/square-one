<?php

namespace Tribe\Project\Components_Docs\Theme;

use Tribe\Project\Components_Docs\Router;

class Assets {

	/**
	 * @var Router $router
	 */
	protected $router;

	public function __construct( Router $router ) {
		$this->router = $router;
	}

	public function enqueue_scripts() {
		if ( ! $this->router->is_docs_page() ) {
			return;
		}

		$js_dir = trailingslashit( plugin_dir_url( __FILE__ ) ) . 'assets/js/dist/';
		$file    = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'scripts.js' : 'scripts.min.js';
		$version = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? uniqid() : tribe_get_version();

		wp_enqueue_script( 'components-docs-scripts', $js_dir . $file, false, $version, true );
		wp_enqueue_script( 'pretty-print', 'https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js' );
	}

	public function enqueue_styles() {
		if ( ! $this->router->is_docs_page() ) {
			return;
		}

		$css_dir = trailingslashit( plugin_dir_url( __FILE__ ) ) . 'assets/css/';
		$file    = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'master.css' : 'master.min.css';
		$version = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? uniqid() : tribe_get_version();

		wp_enqueue_style( 'gf-poppins', 'https://fonts.googleapis.com/css?family=Poppins:100,400,400i,700' );
		wp_enqueue_style( 'components-docs-style', $css_dir . $file, false, $version );
	}

}