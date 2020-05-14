<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Content_Block
 *
 * @property string   $tag
 * @property string[] $classes
 * @property string[] $attrs
 * @property string[] $container_classes
 * @property string   $leadin
 * @property string   $title
 * @property string   $text
 * @property string   $action
 */
class Content_Block extends Context {
	public const TAG               = 'tag';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const CONTAINER_CLASSES = 'container_classes';
	public const LEADIN            = 'leadin';
	public const TITLE             = 'title';
	public const TEXT              = 'text';
	public const ACTION            = 'action';

	protected $path = __DIR__ . '/content-block.twig';

	protected $properties = [
		self::TAG               => [
			self::DEFAULT => 'div',
		],
		self::CLASSES           => [
			self::DEFAULT       => [ 'c-content-block' ],
			self::MERGE_CLASSES => [],
		],
		self::ATTRS             => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
		self::CONTAINER_CLASSES => [
			self::DEFAULT       => [ 'c-content-block__container' ],
			self::MERGE_CLASSES => [],
		],
		self::LEADIN            => [
			self::DEFAULT => '',
		],
		self::TITLE             => [
			self::DEFAULT => '',
		],
		self::TEXT              => [
			self::DEFAULT => '',
		],
		self::ACTION            => [
			self::DEFAULT => '',
		],
	];
}
