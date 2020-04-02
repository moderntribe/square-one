<?php

namespace Tribe\Project\Templates\Components;

class Pagination extends Component {

	protected $path = __DIR__ . '/pagination.twig';

	const WRAPPER_CLASSES    = 'wrapper_classes';
	const WRAPPER_ATTRS      = 'wrapper_attrs';
	const LIST_CLASSES       = 'list_classes';
	const LIST_ATTRS         = 'list_attrs';
	const LIST_ITEM_CLASSES  = 'list_item_classes';
	const LIST_ITEM_ATTRS    = 'list_item_attrs';
	const FIRST_POST         = 'first_post';
	const LAST_POST          = 'last_post';
	const PREV_POST          = 'prev_post';
	const NEXT_POST          = 'next_post';
	const PAGINATION_NUMBERS = 'pagination_numbers';

	protected function parse_options( array $options ): array {
		$defaults = [
			self::WRAPPER_CLASSES    => [],
			self::WRAPPER_ATTRS      => [],
			self::LIST_CLASSES       => [],
			self::LIST_ATTRS         => [],
			self::LIST_ITEM_CLASSES  => [],
			self::LIST_ITEM_ATTRS    => [],
			self::FIRST_POST         => '',
			self::LAST_POST          => '',
			self::PREV_POST          => '',
			self::NEXT_POST          => '',
			self::PAGINATION_NUMBERS => '',
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			self::WRAPPER_CLASSES    => $this->merge_classes( [], $this->options[ self::WRAPPER_CLASSES ], true ),
			self::WRAPPER_ATTRS      => $this->merge_attrs( [], $this->options[ self::WRAPPER_ATTRS ], true ),
			self::LIST_CLASSES       => $this->merge_classes( [], $this->options[ self::LIST_CLASSES ], true ),
			self::LIST_ATTRS         => $this->merge_attrs( [], $this->options[ self::LIST_ATTRS ], true ),
			self::LIST_ITEM_CLASSES  => $this->merge_classes( [], $this->options[ self::LIST_ITEM_CLASSES ], true ),
			self::LIST_ITEM_ATTRS    => $this->merge_attrs( [], $this->options[ self::LIST_ITEM_ATTRS ], true ),
			self::FIRST_POST         => $this->options[ self::FIRST_POST ],
			self::LAST_POST          => $this->options[ self::LAST_POST ],
			self::PREV_POST          => $this->options[ self::PREV_POST ],
			self::NEXT_POST          => $this->options[ self::NEXT_POST ],
			self::PAGINATION_NUMBERS => $this->options[ self::PAGINATION_NUMBERS ],
		];

		return $data;
	}
}
