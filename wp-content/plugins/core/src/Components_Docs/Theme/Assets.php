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
	}

	public function enqueue_styles() {
		if ( ! $this->router->is_docs_page() ) {
			return;
		}

		wp_enqueue_style( 'gf-poppins', 'https://fonts.googleapis.com/css?family=Poppins:100,400,400i,800' );
	}

}