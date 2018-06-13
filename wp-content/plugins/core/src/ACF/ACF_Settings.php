<?php

namespace Tribe\Project\ACF;

use Tribe\Project\Settings\Base_Settings;

/**
 * Class ACF_Settings
 *
 * @package Tribe\Project\ACF
 */
abstract class ACF_Settings extends Base_Settings {

	/**
	 * @param int $priority
	 */
	public function hook( $priority = 10 ) {
		// Don't load anything if ACF is not installed
		if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
			return;
		}

		parent::hook( $priority );
	}

	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Get setting value
	 *
	 * @param string $key
	 * @param null   $default
	 *
	 * @return mixed
	 */
	public function get_setting( $key, $default = null ) {
		$value = get_field( $key, 'option' );

		if ( empty( $value ) ) {
			$value = $default;
		}

		return $value;
	}

	/**
	 * Registers the settings page with ACF
	 */
	public function register_settings() {
		acf_add_options_sub_page( apply_filters( 'core_settings_acf_sub_page', [
			'page_title'  => $this->get_title(),
			'menu_title'  => $this->get_title(),
			'menu_slug'   => $this->slug,
			'redirect'    => true,
			'capability'  => $this->get_capability(),
			'parent_slug' => $this->get_parent_slug(),
		] ) );
	}
}