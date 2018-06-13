<?php
namespace Tribe\Project\Settings;

/**
 * Interface Settings_Builder
 *
 * @package Tribe\Project\Settings
 */
interface Settings_Builder {

	/**
	 * Return the title of the settings screen
	 *
	 * @return string
	 */
	public function get_title();

	/**
	 * Return the cap the current user needs to have to be able to see this settings screen
	 *
	 * @return string
	 */
	public function get_capability();

	/**
	 * Return slug of the parent menu where you want the settings page
	 *
	 * @return string
	 */
	public function get_parent_slug();

	/**
	 * Register the settings screen in WordPress
	 */
	public function register_settings();

	/**
	 * Return the setting value for a given Key.
	 * Return $default if the value is empty.
	 *
	 * @param string     $key
	 * @param mixed|null $default
	 *
	 * @return mixed
	 */
	public function get_setting( $key, $default = null );

}