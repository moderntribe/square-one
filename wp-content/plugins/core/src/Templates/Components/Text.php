<?php

namespace Tribe\Project\Templates\Components;

class Text extends Component {

	protected $path = 'components/text.twig';

	const TEXT    = 'content';
	const TAG     = 'tag';
	const CLASSES = 'classes';
	const ATTRS   = 'attrs';

	protected function parse_options( array $options ): array {
		$defaults = [
			self::TEXT    => '',
			self::TAG     => '',
			self::CLASSES => [],
			self::ATTRS   => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			self::TEXT    => $this->options[ self::TEXT ],
			self::TAG     => $this->options[ self::TAG ] ? $this->options[ self::TAG ] : 'div',
			self::CLASSES => $this->merge_classes( [], $this->options[ self::CLASSES ], true ),
			self::ATTRS   => $this->merge_attrs( [], $this->options[ self::ATTRS ], true ),
		];

		return $data;
	}
}
