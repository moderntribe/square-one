<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Templates\Components\Context;

/**
 * Class Hero
 *
 * @property string   $layout
 * @property string   $media
 * @property string   $content
 * @property string[] $container_classes
 * @property string[] $media_classes
 * @property string[] $content_classes
 * @property string[] $classes
 * @property string[] $attrs
 */
class Hero extends Context {
	public const LAYOUT            = 'layout';
	public const MEDIA             = 'media';
	public const CONTENT           = 'content';
	public const CONTAINER_CLASSES = 'container_classes';
	public const MEDIA_CLASSES     = 'media_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';

	protected $path = __DIR__ . '/hero.twig';

	protected $properties = [
		self::LAYOUT            => [
			self::DEFAULT => '',
		],
		self::MEDIA             => [
			self::DEFAULT => '',
		],
		self::CONTENT           => [
			self::DEFAULT => '',
		],
		self::CONTAINER_CLASSES => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'hero__container', 'l-container' ],
		],
		self::MEDIA_CLASSES     => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'hero__media' ],
		],
		self::CONTENT_CLASSES   => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'hero__content' ],
		],
		self::CLASSES           => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'c-panel', 'c-panel--hero', 'c-panel--full-bleed' ],
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

		return parent::get_data();
	}

}
