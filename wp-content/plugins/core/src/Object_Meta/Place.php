<?php
/**
 * Example Meta
 *
 * An example of how to register object meta.
 */

namespace Tribe\Project\Object_Meta;

use Tribe\Libs\ACF;

class Place extends ACF\ACF_Meta_Group {

	const NAME = 'place_meta';

	const PLACE   = 'name';
	const ADDRESS = 'address';

	public function get_keys() {
		return [
			static::PLACE,
			static::ADDRESS,
		];
	}

	public function get_group_config() {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', __( 'Place Info', 'tribe' ) );

		$group->add_field( $this->get_field_one() );
		$group->add_field( $this->get_field_two() );

		return $group->get_attributes();
	}

	private function get_field_one() {
		$field = new ACF\Field( self::NAME . '_' . self::PLACE );
		$field->set_attributes( [
			'label' => __( 'Name', 'tribe' ),
			'name'  => self::PLACE,
			'type'  => 'text',
		] );

		return $field;
	}

	private function get_field_two() {
		$field = new ACF\Field( self::NAME . '_' . self::ADDRESS );
		$field->set_attributes( [
			'label' => __( 'Address', 'tribe' ),
			'name'  => self::ADDRESS,
			'type'  => 'text',
		] );

		return $field;
	}

}