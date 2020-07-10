<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Components\Component;

/**
 * Class Breadcrumbs
 *
 * @property string[] $items
 * @property string[] $wrapper_classes
 * @property string[] $wrapper_attrs
 * @property string[] $main_classes
 * @property string[] $item_classes
 * @property string[] $link_classes
 * @property string[] $link_attrs
 */
class Breadcrumbs extends Component {

	public const ITEMS           = 'items';
	public const WRAPPER_CLASSES = 'wrapper_classes';
	public const WRAPPER_ATTRS   = 'wrapper_attrs';
	public const MAIN_CLASSES    = 'main_classes';
	public const ITEM_CLASSES    = 'item_classes';
	public const LINK_CLASSES    = 'link_classes';
	public const LINK_ATTRS      = 'link_attrs';

	protected function defaults(): array {
		return [
			self::WRAPPER_CLASSES => [],
			self::WRAPPER_ATTRS   => [],
			self::MAIN_CLASSES    => [],
			self::ITEM_CLASSES    => [],
			self::LINK_CLASSES    => [],
			self::LINK_ATTRS      => [],
		];
	}
}
