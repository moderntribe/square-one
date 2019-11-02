<?php

namespace Tribe\Project\Templates\Components;

class Container extends Component {

	const TEMPLATE_NAME = 'components/container.twig';
	const CONTENT       = 'content';
	const TAG           = 'tag';
	const CLASSES       = 'classes';
	const ATTRS         = 'attrs';

	protected function parse_options( array $options ): array {
		$defaults = [
			static::CONTENT => '',
			static::TAG     => 'div',
			static::CLASSES => [ 'c-container' ],
			static::ATTRS   => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			static::CONTENT => $this->options[ static::CONTENT ],
			static::TAG     => $this->options[ static::TAG ],
			static::CLASSES => $this->merge_classes( [], $this->options[ static::CLASSES ], true ),
			static::ATTRS   => $this->merge_attrs( [], $this->options[ static::ATTRS ], true ),
		];

		return $data;
	}
}