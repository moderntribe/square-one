<?php
/**
 * Example Meta
 *
 * An example of how to register object meta.
 */

namespace Tribe\Project\Object_Meta;

use Tribe\Libs\ACF;

class Example extends ACF\ACF_Meta_Group {

	const NAME = 'example_meta';

	const ONE = 'example_object_meta_one';
	const TWO = 'example_object_meta_two';

	public function get_keys() {
		return [
			static::ONE,
			static::TWO,
		];
	}

	public function get_group_config() {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', __( 'Example Object Meta', 'tribe' ) );

		$group->add_field( $this->get_field_one() );
		$group->add_field( $this->get_field_two() );

		return $group->get_attributes();
	}

	private function get_field_one() {
		$field = new ACF\Field( self::NAME . '_' . self::ONE );
		$field->set_attributes(
			[
				'label' => __( 'Example Object Meta #1', 'tribe' ),
				'name'  => self::ONE,
				'type'  => 'text',
			]
		);

		return $field;
	}

	private function get_field_two() {
		$field = new ACF\Field( self::NAME . '_' . self::TWO );
		$field->set_attributes(
			[
				'label' => __( 'Example Object Meta #2', 'tribe' ),
				'name'  => self::TWO,
				'type'  => 'text',
			]
		);

		return $field;
	}

}