<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Templates\Components\Context;

/**
 * Class Accordion
 *
 * @property string   $layout
 * @property string   $width
 * @property string   $content
 * @property string   $header
 * @property string[] $container_classes
 * @property string[] $media_classes
 * @property string[] $content_classes
 * @property string[] $classes
 * @property string[] $attrs
 */
class Accordion extends Context {
	public const LAYOUT            = 'layout';
	public const WIDTH             = 'width';
	public const CONTENT           = 'content';
	public const HEADER            = 'header';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';

	protected $path = __DIR__ . '/accordion.twig';

	protected $properties = [
		self::LAYOUT            => [
			self::DEFAULT => '',
		],
		self::WIDTH            => [
			self::DEFAULT => '',
		],
		self::CONTENT           => [
			self::DEFAULT => '',
		],
		self::HEADER           => [
			self::DEFAULT => '',
		],
		self::CONTAINER_CLASSES => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'accordion__container', 'l-container' ],
		],
		self::CONTENT_CLASSES   => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'accordion__content' ],
		],
		self::CLASSES           => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'c-panel', 'c-panel--accordion' ],
		],
		self::ATTRS             => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
	];

	public function get_data(): array {
		if ( $this->layout ) {
			$this->properties[ self::CLASSES ][ self::MERGE_CLASSES ][] = 'c-panel--' . $this->layout;
		}

		if ( $this->layout ) {
			$this->properties[ self::CLASSES ][ self::MERGE_CLASSES ][] = 'c-panel--' . $this->width;
		}

		return parent::get_data();
	}

}
