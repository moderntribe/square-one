<?php

namespace Tribe\Project\Templates\Components;

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
class Breadcrumbs extends Context {
	public const ITEMS           = 'items';
	public const WRAPPER_CLASSES = 'wrapper_classes';
	public const WRAPPER_ATTRS   = 'wrapper_attrs';
	public const MAIN_CLASSES    = 'main_classes';
	public const ITEM_CLASSES    = 'item_classes';
	public const LINK_CLASSES    = 'link_classes';
	public const LINK_ATTRS      = 'link_attrs';

	protected $path = __DIR__ . '/breadcrumbs.twig';

	protected $properties = [
		self::ITEMS           => [
			self::DEFAULT => [],
		],
		self::WRAPPER_CLASSES => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'l-container', 'c-breadcrumbs__wrapper' ],
		],
		self::WRAPPER_ATTRS   => [
			self::DEFAULT          => [ 'c-container' ],
			self::MERGE_ATTRIBUTES => [],
		],
		self::MAIN_CLASSES    => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'c-breadcrumbs' ],
		],
		self::ITEM_CLASSES    => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'c-breadcrumbs__item' ],
		],
		self::LINK_CLASSES    => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'anchor', 'c-breadcrumbs__anchor' ],
		],
		self::LINK_ATTRS      => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
	];
}
