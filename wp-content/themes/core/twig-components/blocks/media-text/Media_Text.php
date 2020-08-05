<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Blocks;

use Tribe\Project\Components\Component;
use Tribe\Project\Blocks\Types\Media_Text\Media_Text as Media_Text_Block;

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
class Media_Text extends Component {

	public const WIDTH             = 'width';
	public const LAYOUT            = 'layout';
	public const MEDIA             = 'media';
	public const CONTENT           = 'content';
	public const CONTAINER_CLASSES = 'container_classes';
	public const MEDIA_CLASSES     = 'media_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const MEDIA_TYPE        = 'media_type';

	protected function defaults(): array {
		return [
			self::WIDTH             => Media_Text_Block::WIDTH_GRID,
			self::LAYOUT            => Media_Text_Block::MEDIA_LEFT,
			self::MEDIA             => '',
			self::CONTENT           => '',
			self::CONTAINER_CLASSES => [ 'b-media-text__container' ],
			self::MEDIA_CLASSES     => [ 'b-media-text__media' ],
			self::CONTENT_CLASSES   => [ 'b-media-text__content' ],
			self::CLASSES           => [ 'c-block', 'b-media-text' ],
			self::ATTRS             => [],
			self::MEDIA_TYPE        => 'image',
		];
	}

	public function init() {
		$this->data[ self::CLASSES ][] = 'c-block--' . $this->data[ self::LAYOUT ];
		$this->data[ self::CLASSES ][] = 'c-block--' . $this->data[ self::WIDTH ];

		if ( $this->data[ self::WIDTH ] === Media_Text_Block::WIDTH_GRID ) {
			$this->data[ self::CLASSES ][] = 'l-container';
		}

		if ( $this->data[ self::WIDTH ] === Media_Text_Block::WIDTH_FULL ) {
			$this->data[ self::CONTENT_CLASSES ][] = 'l-container';
		}
	}

}
