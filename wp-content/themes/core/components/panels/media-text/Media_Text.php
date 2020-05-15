<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Templates\Components\Context;

/**
 * Class Media_Text
 *
 * @property string   $width
 * @property string   $layout
 * @property string   $media
 * @property string   $content
 * @property string[] $container_classes
 * @property string[] $media_classes
 * @property string[] $content_classes
 * @property string[] $classes
 * @property string[] $attrs
 */
class Media_Text extends Context {
	public const WIDTH             = 'width';
	public const LAYOUT            = 'layout';
	public const MEDIA             = 'media';
	public const CONTENT           = 'content';
	public const CONTAINER_CLASSES = 'container_classes';
	public const MEDIA_CLASSES     = 'media_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';

	private const TYPE = 'media-text';

	protected $path = __DIR__ . '/media-text.twig';

	protected $properties = [
		self::WIDTH             => [
			self::DEFAULT => '',
		],
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
			self::MERGE_CLASSES => [ self::TYPE . '__container' ],
		],
		self::MEDIA_CLASSES     => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ self::TYPE . '__media' ],
		],
		self::CONTENT_CLASSES   => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ self::TYPE . '__content' ],
		],
		self::CLASSES           => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'c-panel', 'c-panel--' . self::TYPE ],
		],
		self::ATTRS             => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
	];

	public function get_data(): array {
		if ( $this->layout ) {
			$this->properties[ self::CLASSES ][ self::MERGE_CLASSES ][] = self::TYPE . '__layout-' . $this->layout;
		}
		if ( $this->width ) {
			$this->properties[ self::CLASSES ][ self::MERGE_CLASSES ][] = self::TYPE . '__width-' . $this->width;
		}

		return parent::get_data();
	}

}
