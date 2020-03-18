<?php

namespace Tribe\Project\Templates\Components;

class Breadcrumbs extends Component {

	const TEMPLATE_NAME = 'components/breadcrumbs.twig';

	const ITEMS           = 'items';
	const WRAPPER_CLASSES = 'wrapper_classes';
	const WRAPPER_ATTRS   = 'wrapper_attrs';
	const MAIN_CLASSES    = 'main_classes';
	const ITEM_CLASSES    = 'item_classes';
	const LINK_CLASSES    = 'link_classes';
	const LINK_ATTRS      = 'link_attrs';

	protected function parse_options( array $options ): array {
		$defaults = [
			static::ITEMS           => [],
			static::WRAPPER_CLASSES => [],
			static::WRAPPER_ATTRS   => [],
			static::MAIN_CLASSES    => [],
			static::ITEM_CLASSES    => [],
			static::LINK_CLASSES    => [],
			static::LINK_ATTRS      => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			static::ITEMS           => $this->options[ static::ITEMS ],
			static::WRAPPER_CLASSES => $this->merge_classes( [ 'l-container', 'c-breadcrumbs__wrapper' ], $this->options[ static::WRAPPER_CLASSES ], true ),
			static::WRAPPER_ATTRS   => $this->merge_attrs( [], $this->options[ self::WRAPPER_ATTRS ], true ),
			static::MAIN_CLASSES    => $this->merge_classes( [ 'c-breadcrumbs' ], $this->options[ static::MAIN_CLASSES ], true ),
			static::ITEM_CLASSES    => $this->merge_classes( [ 'c-breadcrumbs__item' ], $this->options[ static::ITEM_CLASSES ], true ),
			static::LINK_CLASSES    => $this->merge_classes( [ 'anchor', 'c-breadcrumbs__anchor' ], $this->options[ static::LINK_CLASSES ], true ),
			static::LINK_ATTRS      => $this->merge_attrs( [], $this->options[ self::LINK_ATTRS ], true ),
		];

		return $data;
	}
}
