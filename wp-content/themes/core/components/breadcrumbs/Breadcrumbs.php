<?php

namespace Tribe\Project\Templates\Components;

class Breadcrumbs extends Component {

	protected $path = __DIR__ . '/breadcrumbs.twig';

	const ITEMS           = 'items';
	const WRAPPER_CLASSES = 'wrapper_classes';
	const WRAPPER_ATTRS   = 'wrapper_attrs';
	const MAIN_CLASSES    = 'main_classes';
	const ITEM_CLASSES    = 'item_classes';
	const LINK_CLASSES    = 'link_classes';
	const LINK_ATTRS      = 'link_attrs';

	protected function parse_options( array $options ): array {
		$defaults = [
			self::ITEMS           => [],
			self::WRAPPER_CLASSES => [],
			self::WRAPPER_ATTRS   => [],
			self::MAIN_CLASSES    => [],
			self::ITEM_CLASSES    => [],
			self::LINK_CLASSES    => [],
			self::LINK_ATTRS      => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			self::ITEMS           => $this->options[ self::ITEMS ],
			self::WRAPPER_CLASSES => $this->merge_classes( [ 'l-container', 'c-breadcrumbs__wrapper' ], $this->options[ self::WRAPPER_CLASSES ], true ),
			self::WRAPPER_ATTRS   => $this->merge_attrs( [], $this->options[ self::WRAPPER_ATTRS ], true ),
			self::MAIN_CLASSES    => $this->merge_classes( [ 'c-breadcrumbs' ], $this->options[ self::MAIN_CLASSES ], true ),
			self::ITEM_CLASSES    => $this->merge_classes( [ 'c-breadcrumbs__item' ], $this->options[ self::ITEM_CLASSES ], true ),
			self::LINK_CLASSES    => $this->merge_classes( [ 'anchor', 'c-breadcrumbs__anchor' ], $this->options[ self::LINK_CLASSES ], true ),
			self::LINK_ATTRS      => $this->merge_attrs( [], $this->options[ self::LINK_ATTRS ], true ),
		];

		return $data;
	}
}
