<?php declare(strict_types=1);

namespace Tribe\Project\Theme_Customizer;

use Tribe\Project\Theme_Customizer\Customizer_Sections\Analytics_Settings;
use Tribe\Project\Theme_Customizer\Customizer_Sections\Footer_Settings;
use Tribe\Project\Theme_Customizer\Customizer_Sections\Social_Link_Settings;

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
		$this->analytics_customizer_settings( $wp_customize );
		$this->footer_settings( $wp_customize );
		$this->social_links_settings( $wp_customize );
	}

	protected function analytics_customizer_settings( \WP_Customize_Manager $wp_customize ): void {
		$analytics = new Analytics_Settings( $wp_customize );
		$analytics->section_title()
				  ->field_gtm_id();
	}

	protected function footer_settings( \WP_Customize_Manager $wp_customize ): void {
		$footer = new Footer_Settings( $wp_customize );
		$footer->section_title();
	}

	protected function social_links_settings( \WP_Customize_Manager $wp_customize ): void {
		$social = new Social_Link_Settings( $wp_customize );
		$social->section_title()
			   ->all_links();
	}

}
