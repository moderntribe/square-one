<?php

namespace Tribe\Project\Templates\Components;
use Tribe\Project\Object_Meta;

class Place extends Component {

	const TEMPLATE_NAME = 'components/place.twig';

	const NAME = 'name';
	const ADDRESS = 'address';
	const GOOGLE_PLACE_ID = 'place_id';


	public function parse_options( array $options ): array {
		$defaults = [
			static::NAME    => 'the White House',
			static::ADDRESS => '1600 Pennsylvania Ave',
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			static::NAME    => $this->get_name(),
			static::ADDRESS => $this->get_address(),
			static::GOOGLE_PLACE_ID => $this->get_google_place_id(),
		];

		return $data;
	}

	private function get_name() {
		$place_object = \Tribe\Project\Post_Types\Place\Place::factory( get_the_ID() );
		return $place_object->get_meta( Object_Meta\Place::PLACE );
	}

	private function get_address() {
		$place_object = \Tribe\Project\Post_Types\Place\Place::factory( get_the_ID() );
		return $place_object->get_meta( Object_Meta\Place::ADDRESS );
	}

	private function get_google_place_id() {
		$place_object = \Tribe\Project\Post_Types\Place\Place::factory( get_the_ID() );
		return $place_object->get_meta( Object_Meta\Place::PLACE_ID );
	}
}