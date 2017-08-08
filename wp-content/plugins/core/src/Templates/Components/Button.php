<?php

namespace Tribe\Project\Templates\Components;

class Button extends Component {

	const TEMPLATE_NAME = 'components/button.twig';

	const URL         = 'url';
	const TYPE        = 'type';
	const TARGET      = 'target';
	const CLASSES     = 'classes';
	const ATTRS       = 'attrs';
	const LABEL       = 'label';
	const BTN_AS_LINK = 'btn_as_link';

	protected function parse_options( array $options ): array {
		$defaults = [
			static::URL         => '',
			static::TYPE        => 'button',
			static::TARGET      => '',
			static::CLASSES     => [],
			static::ATTRS       => [],
			static::LABEL       => false,
			static::BTN_AS_LINK => false,
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			'tag'     => $this->options['btn_as_link'] ? 'a' : 'button',
			'url'     => $this->options['btn_as_link'] ? $this->options['url'] : '',
			'classes' => $this->merge_classes( [ 'btn' ], $this->options[ static::CLASSES ], true ),
			'attrs'   => $this->merge_attrs( [], $this->options[ static::ATTRS ], true ),
			'type'    => $this->options['btn_as_link'] ? '' : $this->options['type'],
			'target'  => $this->options['btn_as_link'] ? $this->options['target'] : '',
			'label'   => $this->options['label'],
		];

		return $data;
	}
}