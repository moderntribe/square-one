<?php

namespace Tribe\Project\Templates\Components;

class Button extends Component {

	const TEMPLATE_NAME = 'components/button.twig';

	const TAG         = 'tag';
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
			static::TAG     => $this->options['btn_as_link'] ? 'a' : 'button',
			static::URL     => $this->options['btn_as_link'] ? $this->options['url'] : '',
			static::CLASSES => $this->merge_classes( [ 'c-btn' ], $this->options[ static::CLASSES ], true ),
			static::ATTRS   => $this->get_attrs(),
			static::TYPE    => $this->options['btn_as_link'] ? '' : $this->options['type'],
			static::TARGET  => $this->options['btn_as_link'] ? $this->options['target'] : '',
			static::LABEL   => $this->options['label'],
		];

		return $data;
	}

	protected function get_attrs(): string {
		$attrs = [];

		if( $this->options['btn_as_link'] && $this->options['target'] === '_blank' ) {
			$attrs[ 'rel' ] = 'noopener';
		}

		return $this->merge_attrs( $attrs, $this->options[ static::ATTRS ], true );
	}
}
