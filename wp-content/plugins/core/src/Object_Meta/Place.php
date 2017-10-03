<?php
/**
 * Place Meta.
 */

namespace Tribe\Project\Object_Meta;

use Tribe\Libs\ACF;

class Place extends ACF\ACF_Meta_Group {

	const NAME = 'place_meta';

	const PLACE   = 'name';
	const ADDRESS = 'address';
	const PLACE_ID = 'place_id';
	const HASHED_NAME_AND_ID = 'hashed_name_and_id';

	public function get_keys() {
		return [
			static::PLACE,
			static::ADDRESS,
			static::PLACE_ID,
			static::HASHED_NAME_AND_ID,
		];
	}

	public function get_group_config() {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', __( 'Place Info', 'tribe' ) );

		$group->add_field( $this->get_name() );
		$group->add_field( $this->get_address() );

		return $group->get_attributes();
	}

	private function get_name() {
		$field = new ACF\Field( self::NAME . '_' . self::PLACE );
		$field->set_attributes( [
			'label' => __( 'Name', 'tribe' ),
			'name'  => self::PLACE,
			'type'  => 'text',
		] );

		return $field;
	}

	private function get_address() {
		$field = new ACF\Field( self::NAME . '_' . self::ADDRESS );
		$field->set_attributes( [
			'label' => __( 'Address', 'tribe' ),
			'name'  => self::ADDRESS,
			'type'  => 'text',
		] );

		return $field;
	}

}