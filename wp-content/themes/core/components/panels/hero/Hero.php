<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Templates\Components\Component;

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
			self::LAYOUT             => '',
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
		if ( $this->data[ self::LAYOUT ] ) {
			$this->data[ self::CLASSES ][] = 'c-panel--' . $this->data[ self::LAYOUT ];
		}
	}

	public function render(): void {
		?>
		<section {{ classes|stringify }} {{ attrs|stringify }}>
			<div {{ container_classes|stringify }}>

				<div {{ media_classes|stringify }}>
					{{ component( 'image/Image.php', media ) }}
				</div>

				<div {{ content_classes|stringify }}>
					{{ component( 'content-block/Content_Block.php', content ) }}
				</div>

			</div>
		</section>
		<?php
	}

}
