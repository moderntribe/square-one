<?php
namespace Tribe\Project\Templates\Components;

use Tribe\Project\Taxonomies\Category\Category;

class Items extends Component {

	const TEMPLATE_NAME = 'components/items.twig';

	const LIST_CLASSES      = 'list_classes';
	const LIST_ITEM_CLASSES = 'list_item_classes';
	const TAG               = 'list_type';
	const LIST_ITEM_TAG     = 'list_item_tag';
	const ATTRS             = 'list_attrs';
	const LIST_ITEM_ATTR    = 'list_item_attrs';
	const LIST_ITEMS        = 'list_items';
	const LINK_CLASSES      = 'list_item_link_classes';
	const LINK_ATTRS        = 'list_item_link_attrs';

	protected function parse_options( array $options ): array {

		$defaults = [
			static::LIST_CLASSES        => [],
			static::LIST_ITEM_CLASSES   => [],
			static::TAG                 => 'ul',
			static::LIST_ITEM_TAG       => 'li',
			static::ATTRS               => [],
			static::LIST_CLASSES        => [],
			static::LIST_ITEM_ATTR      => [],
			static::LIST_ITEMS          => [],
			static::LINK_CLASSES        => [],
			static::LINK_ATTRS          => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {

		$data = [
			static::LIST_CLASSES        => $this->merge_classes( [ 'c-taxonomy-list' ], $this->options[ self::LIST_CLASSES ], true ),
			static::LIST_ITEM_CLASSES   => $this->merge_classes( [ 'c-taxonomy-list__list-item' ], $this->options[ self::LIST_ITEM_CLASSES ], true ),
			static::TAG                 => $this->options[ self::TAG ],
			static::LIST_ITEM_TAG       => $this->options[ self::LIST_ITEM_TAG ],
			static::ATTRS               => $this->merge_attrs( [], $this->options[ static::ATTRS ], true ),
			static::LIST_ITEM_ATTR      => $this->merge_attrs( [], $this->options[ static::LIST_ITEM_ATTR ], true ),
			static::LIST_ITEMS          => $this->options[ self::LIST_ITEMS ],
			static::LINK_CLASSES        => $this->merge_classes( [], $this->options[ static::LINK_CLASSES ], true ),
			static::LINK_ATTRS          => $this->merge_attrs( [], $this->options[ static::LINK_ATTRS ], true ),
		];

		return $data;
	}
}
