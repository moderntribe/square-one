<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Templates\Components\Component;
use Tribe\Project\Templates\Components\Context;
use Tribe\Project\Blocks\Types\Media_Text as Media_Text_Block;

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
			self::WIDTH             => '',
			self::LAYOUT            => '',
			self::MEDIA             => '',
			self::CONTENT           => '',
			self::CONTAINER_CLASSES => [ 'media-text__container' ],
			self::MEDIA_CLASSES     => [ 'media-text__media' ],
			self::CONTENT_CLASSES   => [ 'media-text__content' ],
			self::CLASSES           => [ 'c-panel', 'c-panel--media-text' ],
			self::ATTRS             => [],
			self::MEDIA_TYPE        => 'image',
		];
	}

	public function init() {
		if ( $this->data[ self::LAYOUT ] ) {
			$this->data[ self::CLASSES ][] = 'c-panel--layout-media-' . $this->data[ self::LAYOUT ];
		}

		if ( $this->data[ self::WIDTH ] ) {
			$this->data[ self::CLASSES ][] = 'c-panel--width-' . $this->data[ self::WIDTH ];
		}

		if ( $this->data[ self::WIDTH ] === Media_Text_Block::WIDTH_BOXED ) {
			$this->data[ self::CLASSES ][] = 'l-container';
		}

		if ( $this->data[ self::WIDTH ] === Media_Text_Block::WIDTH_FULL ) {
			$this->data[ self::CONTENT_CLASSES ][] = 'l-container';
		}
	}

	public function render(): void {
		?>
        <section {{ classes|stringify }} {{ attrs|stringify }}>
            <div {{ container_classes|stringify }}>

                <div {{ media_classes|stringify }}>
                    {% if media_type == 'image' %}
	                    {{ component( 'image/Image.php', media ) }}
	                {% elseif media_type == 'embed' %}
	                    {{ component( 'text/Text.php', media ) }}
	                {% endif %}
                </div>

                <div {{ content_classes|stringify }}>
                    {{ component( 'content-block/Content_Block.php', content ) }}
                </div>

            </div>
        </section>
		<?php
	}

}
