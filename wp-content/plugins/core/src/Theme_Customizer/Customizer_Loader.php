<?php


namespace Tribe\Project\Theme_Customizer;

class Customizer_Loader {
	private $args = [];

	public function __construct( $args = [] ) {
		$this->args = wp_parse_args( $args, $this->args );
	}

	/**
	 * Load all theme customizer controls that are relevant to the current theme
	 *
	 * @param \WP_Customize_Manager $wp_customize
	 * @return void
	 * @action customize_register
	 */
	public function register_customizer_controls( \WP_Customize_Manager $wp_customize ) {
		// TODO
	}
}
