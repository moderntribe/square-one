<?php declare(strict_types=1);

namespace Tribe\Project\Theme_Customizer;

use WP_Customize_Manager;

/**
 * @package Tribe\Project\Theme_Customizer
 */
class Customizer_Loader {

	/**
	 * Load all theme customizer controls
	 *
	 * @param \WP_Customize_Manager $wp_customize
	 *
	 * @return void
	 *
	 * @action customize_register
	 */
	public function register_customizer_controls( WP_Customize_Manager $wp_customize ): void {
	}

}
