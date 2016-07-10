<?php

namespace Tribe\Project\Settings;

/**
 * Class Abstract_Settings
 *
 * @package Tribe\Project\Settings
 */
abstract class Abstract_Settings {

	/**
	 * Slug of the setting screen. Auto-generated, no need to set manually.
	 * @var string
	 */
	private $slug = '';


	/**
	 * Return the title of the settings screen
	 * @return string
	 */
	abstract protected function get_title();

	/**
	 * Return the cap the current user needs to have to be able to see this settings screen
	 * @return string
	 */
	abstract protected function get_capability();

	/**
	 * Return array of ACF fields
	 * @return array
	 */
	abstract protected function get_fields();

	/**
	 * Return slug of the parent menu where you want the settings page
	 * @return string
	 */
	abstract protected function get_parent_slug();

	public function __construct() {
		$this->slug = sanitize_title( $this->get_parent_slug() . '-' . $this->get_title() );
	}

	/**
	 * @param int $priority
	 */
	public function hook( $priority = 10 ) {
		add_action( 'init', [ $this, 'register_settings' ], $priority, 0 );
		add_action( 'init', [ $this, 'add_fields' ], $priority + 1, 0 );
	}

	/**
	 * Registers the settings page with ACF
	 */
	public function register_settings() {
		acf_add_options_sub_page( apply_filters( 'core_settings_sub_page', [
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
	public function add_fields() {
		acf_add_local_field_group( apply_filters( 'core_settings_field_group', [
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

	/**
	 * Get setting value
	 * @param $key
	 *
	 * @return mixed
	 */
	public function get_setting( $key ) {
		return get_field( $key, 'option' );
	}

}