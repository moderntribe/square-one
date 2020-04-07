<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Title
 *
 * @property string   $title
 * @property string   $tag
 * @property string[] $classes
 * @property string[] $attrs
 */
class Title extends Context {
	public const TITLE   = 'title';
	public const TAG     = 'tag';
	public const CLASSES = 'classes';
	public const ATTRS   = 'attrs';

	protected $path = __DIR__ . '/title.twig';

	protected $properties = [
		self::TITLE   => [
			self::DEFAULT => 'h2',
		],
		self::TAG     => [
			self::DEFAULT => '',
		],
		self::CLASSES => [
			self::DEFAULT => [],
			self::MERGE_CLASSES => [],
		],
		self::ATTRS   => [
			self::DEFAULT => [],
			self::MERGE_ATTRIBUTES => [],
		],
	];

}
