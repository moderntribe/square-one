<?php

namespace Tribe\Project\Templates\Components;
use Tribe\Project\Object_Meta;

class Place extends Component {

	const TEMPLATE_NAME = 'components/place.twig';

	const NAME    = 'name';
	const ADDRESS = 'address';

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
		];

		return $data;
	}

	private function get_name() {
		return get_field( Object_Meta\Place::PLACE );
	}

	private function get_address() {
		return get_field( Object_Meta\Place::ADDRESS );
	}
}