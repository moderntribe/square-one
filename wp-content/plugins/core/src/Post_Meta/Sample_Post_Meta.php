<?php

namespace Tribe\Project\Post_Meta;

use Tribe\Libs\ACF;

/**
 * Class Sample_Post_Meta
 * @package Tribe\Project\Post_Meta
 *
 * A sample class for creating post meta fields in square-one with Advanced Custom Fields
 *
 * Be sure to register your post meta in Service_Providers/Post_Meta_Service_Provider.php
 */
class Sample_Post_Meta extends ACF\ACF_Meta_Group {

	/**
	 * Name your post meta group and setup field keys
	 */
	const NAME = 'sample_post_meta';
	const SAMPLE_TEXT = 'sample_text';
	const SAMPLE_RADIO = 'sample_radio';
	const SAMPLE_BOOL = 'sample_true_false';

	/**
	 * @return array
	 *
	 * Return all meta keys in this group of post meta
	 */
	public function get_keys(): array {
		return [
			self::SAMPLE_TEXT,
			self::SAMPLE_RADIO,
			self::SAMPLE_BOOL,
		];
	}

	/**
	 * @param int $post_id
	 * @param string $key
	 *
	 * @return mixed
	 *
	 * Get the value of a post meta field.  This functions allows room for custom returns based on parameters.
	 */
	public function get_value( $post_id, $key ) {

		if ( in_array( $key, $this->get_keys() ) ) {
			return get_field( $key , $post_id );
		}

		return '';

		/**
		 * Example conditional return, if the text field is a person's name
		 * You could return something like "Hi Roy!" where the user had just submitted "Roy"
		 *
		 * switch ( $key ) {
		 *     case self::SAMPLE_TEXT
		 *         return 'Hi ' . get_field( $key, $post_id ) . '!';
		 *     case default:
		 *         return get_field( $key, $post_id );
		 */

	}

	/**
	 * @return array
	 *
	 * Create a new ACF Group to contain post meta fields
	 */
	protected function get_group_config() {

		$group = new ACF\Group( self::NAME );

		$group->set( 'title', __( 'Sample Post Meta', 'tribe' ) );
		$group->set( 'position', 'normal' );
		$group->set_post_types( $this->post_types );

		/**
		 * Field parameter is the index of meta key for field in const META_KEYS
		 */
		$group->add_field( $this->get_sample_text_field() );
		$group->add_field( $this->get_sample_bool_field() );
		$group->add_field( $this->get_sample_radio_field() );

		return $group->get_attributes();

	}


	/**
	 * @return ACF\Field
	 *
	 * Generate a text input post meta field
	 */
	private function get_sample_text_field() {

		$field = new ACF\Field( self::NAME . '_' . self::SAMPLE_TEXT );

		$field->set_attributes( [
			'label' => __( 'Input Your Text', 'tribe' ),
			'name'  => self::SAMPLE_TEXT,
			'type'  => 'text',
		] );

		return $field;

	}


	/**
	 * @return ACF\Field
	 *
	 * Generate a text input post meta field
	 */
	private function get_sample_bool_field() {

		$field = new ACF\Field( self::NAME . '_' . self::SAMPLE_BOOL );

		$field->set_attributes( [
			'label' => __( 'Make This True?', 'tribe' ),
			'name'  => self::SAMPLE_BOOL,
			'type'  => 'true_false',
		] );

		return $field;

	}

	/**
	 * @return ACF\Field
	 *
	 * Create a selection post meta field
	 */
	public static function get_sample_radio_field() {

		$field = new ACF\Field( self::NAME . '_' . self::SAMPLE_RADIO );

		$field->set_attributes( [
			'label'   => __( 'Select An Option', 'tribe' ),
			'name'    => self::SAMPLE_RADIO,
			'type'    => 'radio',
			'layout'  => 'horizontal',
			'choices' => [
				'yes' => __( 'Yes', 'tribe' ),
				'no'  => __( 'No', 'tribe' ),
				'maybe' => __( 'Maybe', 'tribe' ),
			],
		] );

		return $field;

	}

	/**
	 * @return mixed
	 *
	 * Return an instance of the post meta class store in the container
	 */
	public static function instance() {
		return tribe_project()->container()[ 'post_meta.' . static::NAME ];
	}
}