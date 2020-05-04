<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Content_Block
 *
 * @property string   $title
 * @property string   $text
 * @property string   $button
 * @property string[] $classes
 * @property string[] $content_classes
 */
class Content_Block extends Context {
	public const TAG     = 'tag';
	public const TITLE           = 'title';
	public const TEXT            = 'text';
	public const BUTTON          = 'button';
	public const CLASSES         = 'classes';
	public const WRAPPER_CLASSES = 'content_classes';

	protected $path = __DIR__ . '/contentblock.twig';

	protected $properties = [
		self::TAG     => [
			self::DEFAULT => 'div',
		],
		self::TITLE           => [
			self::DEFAULT => '',
		],
		self::TEXT            => [
			self::DEFAULT => '',
		],
		self::BUTTON          => [
			self::DEFAULT => '',
		],
		self::CLASSES         => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'c-content-block' ],
		],
		self::WRAPPER_CLASSES => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'c-content-block__content' ],
		],
	];
}
