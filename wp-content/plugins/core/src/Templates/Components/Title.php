<?php

namespace Tribe\Project\Templates\Components;

class Title extends Component {

	const TEMPLATE_NAME = 'components/title.twig';
	const TITLE         = 'title';
	const TAG           = 'tag';
	const CLASSES       = 'classes';
	const ATTRS         = 'attrs';

	protected function parse_options( array $options ): array {
		$defaults = [
			static::TITLE   => '',
			static::TAG     => '',
			static::CLASSES => [],
			static::ATTRS   => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			static::TITLE   => $this->options[ static::TITLE ],
			static::TAG     => $this->options[ static::TAG ],
			static::CLASSES => $this->merge_classes( [], $this->options[ static::CLASSES ], true ),
			static::ATTRS   => $this->merge_attrs( [], $this->options[ static::ATTRS ], true ),
		];

		return $data;
	}
}