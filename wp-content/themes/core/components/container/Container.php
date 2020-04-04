<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Container
 *
 * A component to contain arbitrary html and take classes/attributes. Useful when
 * composing more complex ui's in other controllers
 *
 * @property string   $content
 * @property string   $tag
 * @property string[] $classes
 * @property string[] $attrs
 */
class Container extends Context {
	public const CONTENT = 'content';
	public const TAG     = 'tag';
	public const CLASSES = 'classes';
	public const ATTRS   = 'attrs';

	protected $path = __DIR__ . '/container.twig';

	protected $properties = [
		self::CONTENT => [
			self::DEFAULT => '',
		],
		self::TAG     => [
			self::DEFAULT => 'div',
		],
		self::CLASSES => [
			self::DEFAULT       => [ 'c-container' ],
			self::MERGE_CLASSES => [],
		],
		self::ATTRS   => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
	];
}
