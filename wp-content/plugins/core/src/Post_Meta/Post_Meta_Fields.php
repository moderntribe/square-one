<?php

namespace Tribe\Project\Post_Meta;

use Tribe\Libs\ACF\Field;

class Post_Meta_Fields {

	/**
	 * @param array $text_field
	 *
	 * @throws \Exception
	 *
	 * @return Field
	 *
	 * Generate a text input post meta field
	 */
	public static function get_simple_field( array $text_field ) {

		if ( ! isset( $text_field['meta_key'] ) ) {
			throw new \Exception( 'A meta key is required.' );
		}

		$defaults   = [
			'label' => '',
			'type' => 'text',
		];
		$text_field = wp_parse_args( $text_field, $defaults );

		$field = new Field( $text_field['meta_key'] );

		$field->set_attributes( [
			'label' => __( $text_field['label'], 'tribe' ),
			'name'  => $text_field['meta_key'],
			'type'  => $text_field['type'],
		] );

		return $field;

	}

	/**
	 * @param array $select_field
	 *
	 * @throws \Exception
	 *
	 * @return Field
	 *
	 * Create a selection post meta field
	 */
	public static function get_select_field( array $select_field ) {

		if ( ! isset( $select_field['meta_key'] ) ) {
			throw new \Exception( 'A meta key is required.' );
		}

		$defaults     = [
			'label'   => '',
			'type'    => 'select',
			'layout'  => '',
			'choices' => '',
		];
		$select_field = wp_parse_args( $select_field, $defaults );

		$field = new Field( $select_field['meta_key'] );

		$field->set_attributes( [
			'label'   => __( $select_field['label'], 'tribe' ),
			'name'    => $select_field['meta_key'],
			'type'    => $select_field['type'],
			'layout'  => $select_field['layout'],
			'choices' => $select_field['choices'],
		] );

		return $field;

	}

}