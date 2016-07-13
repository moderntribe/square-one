<?php

namespace Tribe\Project\Settings\Contracts;

/**
 * Class Abstract_Settings
 *
 * @ToDo: Candidate for tribe-libs
 *
 * @package Tribe\Project\Settings
 */
abstract class ACF_Settings extends Base_Settings {


	/**
	 * Return array of ACF fields
	 *
	 * @return array
	 */
	abstract public function get_fields();

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

	/**
	 * Adds the settings group
	 */
	public function register_fields() {
		acf_add_local_field_group( apply_filters( 'core_settings_acf_field_group', [
			'key'                   => 'group_' . md5( $this->slug ),
			'title'                 => $this->get_title(),
			'fields'                => $this->get_fields(),
			'location'              => [
				[
					[
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => $this->slug,
					],
				],
			],
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => 1,
			'description'           => '',
		] ) );
	}

}