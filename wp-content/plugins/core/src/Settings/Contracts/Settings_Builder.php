<?php
namespace Tribe\Project\Settings\Contracts;

/**
 * Interface Settings_Builder
 *
 * @ToDo: Candidate for tribe-libs
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
	 * Register the fields for the settings page
	 */
	public function register_fields();

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