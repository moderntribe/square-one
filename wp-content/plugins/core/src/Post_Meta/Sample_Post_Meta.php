<?php

namespace Tribe\Project\Post_Meta;

use Tribe\Libs\ACF;

/**
 * Class Sample_Post_Meta
 * @package Tribe\Project\Post_Meta
 *
 * A sample class for creating post meta fields in square-one with Advanced Custom Fields
 */
class Sample_Post_Meta extends ACF\ACF_Meta_Group {

	/**
	 * Name your post meta group and setup field attributes
	 *
	 * All fields require key and meta_key.  Key is used for cleaner code when using the get_value method.
	 * Meta key is to pseudo namespace the actual meta keys.
	 */
	const NAME = 'sample_post_meta';
	const SAMPLE_TEXT = [
		'key'      => 'sample_text_field',
		'meta_key' => self::NAME . '_sample_text_field',
		'label'    => 'A Sample Text Field',
	];
	const SAMPLE_RADIO = [
		'key'      => 'sample_radio_field',
		'meta_key' => self::NAME . '_sample_radio_field',
		'label'    => 'A Sample Radio Field',
		'type'     => 'radio',
		'choices'  => [ 0, 1, 2, 3, 4, 5 ],
		'layout'   => 'horizontal',
	];
	const SAMPLE_TRUE_FALSE = [
		'key'      => 'sample_true_false',
		'meta_key' => self::NAME . '_sample_true_false',
		'label'    => 'A Sample True/False Field',
		'type'     => 'true_false',
	];
	const SAMPLE_CUSTOM_FIELD = [
		'key'      => 'sample_custom_field',
		'meta_key' => self::NAME . '_sample_custom_field',
	];

	/**
	 * @return array
	 *
	 * Return all meta keys in this group of post meta
	 */
	public function get_keys(): array {
		return [
			self::SAMPLE_TEXT['key'],
			self::SAMPLE_RADIO['key'],
			self::SAMPLE_TRUE_FALSE['key'],
			self::SAMPLE_CUSTOM_FIELD['key'],
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

		if ( isset( $this->get_keys()[ $key ] ) ) {
			return get_field( $this->get_keys()[ $key ]['meta_key'], $post_id );
		}

		return '';

	}

	/**
	 * @return array
	 *
	 * Create a new ACF Group to contain post meta fields
	 */
	public function get_group_config() {

		$group = new ACF\Group( self::NAME );

		$group->set( 'title', __( 'Sample Post Meta', 'tribe' ) );
		$group->set( 'position', 'normal' );
		$group->set_post_types( $this->post_types );

		/**
		 * Field parameter is the index of meta key for field in const META_KEYS
		 */
		$group->add_field( Post_Meta_Fields::get_simple_field( self::SAMPLE_TEXT ) );
		$group->add_field( Post_Meta_Fields::get_simple_field( self::SAMPLE_TRUE_FALSE ) );
		$group->add_field( Post_Meta_Fields::get_select_field( self::SAMPLE_RADIO ) );
		$group->add_field( self::get_custom_field() );

		return $group->get_attributes();

	}

	/**
	 * @return ACF\Field
	 *
	 * If no predefined field works you can create methods for generating custom fields
	 */
	public function get_custom_field() {
		$field = new ACF\Field( self::SAMPLE_CUSTOM_FIELD['meta_key'] );
		$field->set_attributes( [
			'label'          => __( 'Link URL', 'tribe' ),
			'name'           => self::SAMPLE_CUSTOM_FIELD['meta_key'],
			'type'           => 'page_link',
			'post_type'      => [
				0 => 'page',
			],
			'allow_null'     => 1,
			'allow_archives' => 1,
			'multiple'       => 0,
		] );

		return $field;
	}

	public static function instance() {
		return tribe_project()->container()[ 'post_meta.' . static::NAME ];
	}
}