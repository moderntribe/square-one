<?php declare(strict_types=1);

namespace Tribe\Project\Theme_Customizer;

use Tribe\Project\Theme_Customizer\Customizer_Sections\Footer_Settings;

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
	public function register_customizer_controls( \WP_Customize_Manager $wp_customize ): void {
		$this->footer_section( $wp_customize );
	}

	protected function footer_section( \WP_Customize_Manager $wp_customize ): void {
		$footer = new Footer_Settings( $wp_customize );
		$footer->section_title()
			->field_footer_logo()
			->field_footer_description();
	}

}
