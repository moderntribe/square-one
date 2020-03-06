<?php

namespace Tribe\Project\Templates\Components;

class Button extends Component {

	protected $path = 'components/button.twig';

	const TAG         = 'tag';
	const URL         = 'url';
	const TYPE        = 'type';
	const TARGET      = 'target';
	const ARIA_LABEL  = 'aria_label';
	const CLASSES     = 'classes';
	const ATTRS         = 'attrs';
	const LABEL         = 'label';
	const FORCE_DISPLAY = 'force_display';
	const BTN_AS_LINK   = 'btn_as_link';
	const INNER_ATTRIBUTES   = 'inner_attributes';

	protected function parse_options( array $options ): array {
		$defaults = [
			self::URL         => '',
			self::TYPE        => 'button',
			self::TARGET      => '',
			self::ARIA_LABEL  => '',
			self::CLASSES     => [],
			self::ATTRS       => [],
			self::LABEL       => false,
			self::BTN_AS_LINK => false,
			self::FORCE_DISPLAY => false,
			self::INNER_ATTRIBUTES => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			self::TAG              => $this->options['btn_as_link'] ? 'a' : 'button',
			self::URL              => $this->options['btn_as_link'] ? $this->options['url'] : '',
			self::CLASSES          => $this->merge_classes( [ '' ], $this->options[ self::CLASSES ], true ),
			self::ATTRS            => $this->get_attrs(),
			self::TYPE             => $this->options['btn_as_link'] ? '' : $this->options['type'],
			self::TARGET           => $this->options['btn_as_link'] ? $this->options['target'] : '',
			self::LABEL            => $this->options['label'],
			self::ARIA_LABEL       => $this->options[ self::ARIA_LABEL ],
			self::FORCE_DISPLAY    => $this->options[ self::FORCE_DISPLAY ],
			self::INNER_ATTRIBUTES => $this->merge_attrs( [], $this->options[ self::INNER_ATTRIBUTES ], true ),
		];

		return $data;
	}

	protected function get_attrs(): string {
		$attrs = [];

		if( $this->options['btn_as_link'] && $this->options['target'] === '_blank' ) {
			$attrs[ 'rel' ] = 'noopener';
		}

		return $this->merge_attrs( $attrs, $this->options[ self::ATTRS ], true );
	}
}
