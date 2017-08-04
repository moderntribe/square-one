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
	const ONE  = 'example_object_meta_one';
	const TWO  = 'example_object_meta_two';

	public function get_keys() {
		return [
			static::ONE,
			static::TWO,
		];
	}

	public function get_group_config() {

		$key   = static::NAME;
		$group = new ACF\Group( $key, $this->object_types );
		$group->set( 'title', __( 'Example Object Meta', 'tribe' ) );

		$one = new ACF\Field( "{$key}_example_object_meta_one" );
		$one->set_attributes( [
			'label'             => __( 'Example Object Meta #1', 'tribe' ),
			'name'              => self::ONE,
			'type'              => 'text',
		] );

		$group->add_field( $one );

		$two = new ACF\Field( "{$key}_example_object_meta_two" );
		$two->set_attributes( [
			'label' => __( 'Example Object Meta #2', 'tribe' ),
			'name'  => self::TWO,
			'type'  => 'text',
		] );

		$group->add_field( $two );

		return $group->get_attributes();
	}

}