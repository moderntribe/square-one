<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Components\Component;
use Tribe\Project\Blocks\Types\Hero as Hero_Block;

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
class Hero extends Component {

	public const LAYOUT            = 'layout';
	public const MEDIA             = 'media';
	public const CONTENT           = 'content';
	public const CONTAINER_CLASSES = 'container_classes';
	public const MEDIA_CLASSES     = 'media_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';

	protected function defaults(): array {
		return [
			self::LAYOUT            => Hero_Block::LAYOUT_LEFT,
			self::MEDIA             => '',
			self::CONTENT           => '',
			self::CONTAINER_CLASSES => [ 'hero__container', 'l-container' ],
			self::MEDIA_CLASSES     => [ 'hero__media' ],
			self::CONTENT_CLASSES   => [ 'hero__content' ],
			self::CLASSES           => [ 'c-panel', 'c-panel--hero', 'c-panel--full-bleed' ],
			self::ATTRS             => [],
		];
	}

	public function init() {
		$this->data[ self::CLASSES ][] = 'c-panel--' . $this->data[ self::LAYOUT ];
	}

}
