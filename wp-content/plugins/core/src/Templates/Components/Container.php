<?php

namespace Tribe\Project\Templates\Components;

class Container extends Component {

	protected $path = 'components/container.twig';

	const CONTENT       = 'content';
	const TAG           = 'tag';
	const CLASSES       = 'classes';
	const ATTRS         = 'attrs';

	protected function parse_options( array $options ): array {
		$defaults = [
			self::CONTENT => '',
			self::TAG     => 'div',
			self::CLASSES => [ 'c-container' ],
			self::ATTRS   => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			self::CONTENT => $this->options[ self::CONTENT ],
			self::TAG     => $this->options[ self::TAG ],
			self::CLASSES => $this->merge_classes( [], $this->options[ self::CLASSES ], true ),
			self::ATTRS   => $this->merge_attrs( [], $this->options[ self::ATTRS ], true ),
		];

		return $data;
	}
}
