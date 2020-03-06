<?php

namespace Tribe\Project\Templates\Components;

class Title extends Component {

	protected $path = 'components/title.twig';

	const TITLE         = 'title';
	const TAG           = 'tag';
	const CLASSES       = 'classes';
	const ATTRS         = 'attrs';

	protected function parse_options( array $options ): array {
		$defaults = [
			self::TITLE   => '',
			self::TAG     => '',
			self::CLASSES => [],
			self::ATTRS   => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			self::TITLE   => $this->options[ self::TITLE ],
			self::TAG     => $this->options[ self::TAG ],
			self::CLASSES => $this->merge_classes( [], $this->options[ self::CLASSES ], true ),
			self::ATTRS   => $this->merge_attrs( [], $this->options[ self::ATTRS ], true ),
		];

		return $data;
	}
}
