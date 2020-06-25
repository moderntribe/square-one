<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Text
 *
 * @property string   $content
 * @property string   $tag
 * @property string[] $classes
 * @property string[] $attrs
 */
class Text extends Component {
	public const TEXT    = 'content';
	public const TAG     = 'tag';
	public const CLASSES = 'classes';
	public const ATTRS   = 'attrs';

	protected $path = __DIR__ . '/text.twig';

	protected $properties = [
		self::TEXT    => [
			self::DEFAULT => '',
		],
		self::TAG     => [
			self::DEFAULT => 'div',
		],
		self::CLASSES => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [],
		],
		self::ATTRS   => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
	];
}
