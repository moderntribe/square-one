<?php

namespace Tribe\Project\Templates\Components;

class Text extends Component {

	const TEMPLATE_NAME = 'components/text.twig';

	const TEXT    = 'content';
	const CLASSES = 'classes';
	const ATTRS   = 'attrs';

	protected function parse_options( array $options ): array {
		$defaults = [
			static::TEXT    => '',
			static::CLASSES => [],
			static::ATTRS   => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			static::TEXT    => $this->options[ static::TEXT ],
			static::CLASSES => $this->merge_classes( [], $this->options[ static::CLASSES ], true ),
			static::ATTRS   => $this->merge_attrs( [], $this->options[ static::ATTRS ], true ),
		];

		return $data;
	}
}