<?php

namespace Tribe\Project\Templates\Components;

class Pagination extends Component {

	const TEMPLATE_NAME = 'components/pagination.twig';

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
			static::WRAPPER_CLASSES    => [],
			static::WRAPPER_ATTRS      => [],
			static::LIST_CLASSES       => [],
			static::LIST_ATTRS         => [],
			static::LIST_ITEM_CLASSES  => [],
			static::LIST_ITEM_ATTRS    => [],
			static::FIRST_POST         => '',
			static::LAST_POST          => '',
			static::PREV_POST          => '',
			static::NEXT_POST          => '',
			static::PAGINATION_NUMBERS => '',
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			static::WRAPPER_CLASSES    => $this->merge_classes( [], $this->options[ static::WRAPPER_CLASSES ], true ),
			static::WRAPPER_ATTRS      => $this->merge_attrs( [], $this->options[ self::WRAPPER_ATTRS ], true ),
			static::LIST_CLASSES       => $this->merge_classes( [], $this->options[ static::LIST_CLASSES ], true ),
			static::LIST_ATTRS         => $this->merge_attrs( [], $this->options[ self::LIST_ATTRS ], true ),
			static::LIST_ITEM_CLASSES  => $this->merge_classes( [], $this->options[ static::LIST_ITEM_CLASSES ], true ),
			static::LIST_ITEM_ATTRS    => $this->merge_attrs( [], $this->options[ self::LIST_ITEM_ATTRS ], true ),
			static::FIRST_POST         => $this->options[ static::FIRST_POST ],
			static::LAST_POST          => $this->options[ static::LAST_POST ],
			static::PREV_POST          => $this->options[ static::PREV_POST ],
			static::NEXT_POST          => $this->options[ static::NEXT_POST ],
			static::PAGINATION_NUMBERS => $this->options[ static::PAGINATION_NUMBERS ],
		];

		return $data;
	}
}
