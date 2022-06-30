<?php declare(strict_types=1);

namespace Tribe\Project\Theme_Customizer;

use Tribe\Project\Theme_Customizer\Customizer_Sections\Analytics_Settings;
use Tribe\Project\Theme_Customizer\Customizer_Sections\Social_Follow_Settings;

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
		$this->analytics_section( $wp_customize );
		$this->social_follow_section( $wp_customize );
	}

	protected function analytics_section( \WP_Customize_Manager $wp_customize ): void {
		$analytics = new Analytics_Settings( $wp_customize );
		$analytics->section_title()
				  ->field_gtm_id();
	}

	protected function social_follow_section( \WP_Customize_Manager $wp_customize ): void {
		$social = new Social_Follow_Settings( $wp_customize );
		$social->section_title()
			   ->field_all_links();
	}

}
