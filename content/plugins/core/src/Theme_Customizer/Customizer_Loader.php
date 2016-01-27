<?php


namespace Tribe\Project\Theme_Customizer;


class Customizer_Loader {
	private $args = [];

	public function __construct( $args = [] ) {
		$this->args = wp_parse_args( $args, $this->args );
	}

	public function hook() {
		add_action( 'customize_register', [ $this, 'register_customizer_controls' ] );
	}

	/**
	 * Load all theme customizer controls that are relevant to the current theme
	 *
	 * @param \WP_Customize_Manager $wp_customize
	 * @return void
	 */
	public function register_customizer_controls( \WP_Customize_Manager $wp_customize ) {
		// TODO
	}
}