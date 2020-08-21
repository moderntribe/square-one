<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\media_text;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Types\Media_Text\Media_Text as Media_Text_Block;
use Tribe\Project\Templates\Components\content_block\Controller;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Models\Image;
use Tribe\Project\Theme\Config\Image_Sizes;

class Media_Text_Block_Controller extends Abstract_Controller {
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const WIDTH             = 'width';
	public const LAYOUT            = 'layout';
	public const CONTAINER_CLASSES = 'container_classes';
	public const MEDIA_CLASSES     = 'media_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const MEDIA_TYPE        = 'media_type';
	public const IMAGE             = 'image';
	public const VIDEO             = 'video';
	public const TITLE             = 'title';
	public const CONTENT           = 'content';
	public const CTA               = 'cta';

	private array $classes;
	private array $attrs;
	private string $width;
	private string $layout;
	private array $container_classes;
	private array $media_classes;
	private array $content_classes;
	private string $media_type;
	private ?int $image;
	private string $video;
	private string $title;
	private string $content;
	private array $cta;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->width             = (string) $args[ self::WIDTH ];
		$this->layout            = (string) $args[ self::LAYOUT ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->media_classes     = (array) $args[ self::MEDIA_CLASSES ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->media_type        = (string) $args[ self::MEDIA_TYPE ];
		$this->image             = (int) $args[ self::IMAGE ];
		$this->video             = (string) $args[ self::VIDEO ];
		$this->title             = (string) $args[ self::TITLE ];
		$this->content           = (string) $args[ self::CONTENT ];
		$this->cta               = (array) $args[ self::CTA ];
	}

	protected function defaults(): array {
		return [
			self::CLASSES           => [],
			self::ATTRS             => [],
			self::WIDTH             => Media_Text_Block::WIDTH_GRID,
			self::LAYOUT            => Media_Text_Block::MEDIA_LEFT,
			self::CONTAINER_CLASSES => [],
			self::MEDIA_CLASSES     => [],
			self::CONTENT_CLASSES   => [],
			self::MEDIA_TYPE        => Media_Text_Block::IMAGE,
			self::IMAGE             => [],
			self::VIDEO             => '',
			self::TITLE             => '',
			self::CONTENT           => '',
			self::CTA               => [],
		];
	}

	protected function required(): array {
		return [
			self::CLASSES           => [ 'c-block', 'b-media-text' ],
			self::CONTAINER_CLASSES => [ 'b-media-text__container' ],
			self::MEDIA_CLASSES     => [ 'b-media-text__media' ],
			self::CONTENT_CLASSES   => [ 'b-media-text__content' ],
		];
	}

	public function get_classes(): string {
		$this->classes[] = 'c-block--' . $this->layout;
		$this->classes[] = 'c-block--' . $this->width;
		if ( $this->width === Media_Text_Block::WIDTH_GRID ) {
			$this->classes[] = 'l-container';
		}

		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_container_classes(): string {
		return Markup_Utils::class_attribute( $this->container_classes );
	}

	public function get_content_classes(): string {
		if ( $this->width === Media_Text_Block::WIDTH_FULL ) {
			$this->content_classes[] = 'l-container';
		}

		return Markup_Utils::class_attribute( $this->content_classes );
	}

	public function get_media_classes(): string {
		return Markup_Utils::class_attribute( $this->media_classes );
	}

	public function get_media_type(): string {
		return $this->media_type;
	}

	/**
	 * @return array
	 */
	public function get_content_args(): array {
		return [
			'classes' => [ 'b-media-text__content-container' ],
			'title'   => defer_template_part( 'components/text/text', null, [
				'content' => $this->title,
				Controller::CLASSES
			] ),
			'content' => defer_template_part( 'components/container/container', null, [
				'content' => $this->content,
			] ),
			'cta'     => defer_template_part( 'components/link/link', null, $this->cta ?? [] ),
			'layout'  => $this->layout === Media_Text_Block::MEDIA_CENTER ? Controller::LAYOUT_INLINE : Controller::LAYOUT_LEFT,
		];
	}

}
