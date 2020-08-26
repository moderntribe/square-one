<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\media_text;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Types\Media_Text\Media_Text as Media_Text_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\image\Image_Controller;
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
	public const LEADIN            = 'leadin';
	public const DESCRIPTION       = 'description';
	public const CTA               = 'cta';

	private array  $classes;
	private array  $attrs;
	private string $width;
	private string $layout;
	private array  $container_classes;
	private array  $media_classes;
	private array  $content_classes;
	private string $media_type;
	private int    $image;
	private string $video;
	private string $title;
	private string $leadin;
	private string $description;
	private array  $cta;

	/**
	 * @param array $args
	 */
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
		$this->leadin            = (string) $args[ self::LEADIN ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
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
			self::LEADIN            => '',
			self::DESCRIPTION       => '',
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
			Content_Block_Controller::TAG     => 'header',
			Content_Block_Controller::LEADIN  => $this->get_leadin(),
			Content_Block_Controller::TITLE   => $this->get_title(),
			Content_Block_Controller::CONTENT => $this->get_content(),
			Content_Block_Controller::CTA     => $this->get_cta(),
			Content_Block_Controller::LAYOUT  => $this->layout === Media_Text_Block::MEDIA_CENTER ? Content_Block_Controller::LAYOUT_INLINE : Content_Block_Controller::LAYOUT_LEFT,
			Content_Block_Controller::CLASSES => [
				'c-block__content-block',
				'c-block__header',
				'b-media-text__content-container'
			],
		];
	}

	/**
	 * @return Deferred_Component
	 */
	private function get_leadin(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::CLASSES => [
				'c-block__leadin',
				'b-media-text__leadin'
			],
			Text_Controller::CONTENT => $this->leadin ?? '',
		] );
	}

	/**
	 * @return Deferred_Component
	 */
	private function get_title(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::TAG     => 'h2',
			Text_Controller::CLASSES => [
				'c-block__title',
				'b-media-text__title',
				'h3'
			],
			Text_Controller::CONTENT => $this->title ?? '',
		] );
	}

	/**
	 * @return Deferred_Component
	 */
	private function get_content(): Deferred_Component {
		return defer_template_part( 'components/container/container', null, [
			Container_Controller::CLASSES => [
				'c-block__description',
				'b-media-text__text',
				't-sink',
				's-sink'
			],
			Container_Controller::CONTENT => $this->description ?? '',
		] );
	}

	/**
	 * @return Deferred_Component
	 */
	private function get_cta(): Deferred_Component {
		$cta = wp_parse_args( $this->cta, [
			'content' => '',
			'url'     => '',
			'target'  => '',
		] );
		return defer_template_part( 'components/link/link', null, [
			Link_Controller::URL     => $cta['url'],
			Link_Controller::CONTENT => $cta['content'] ?: $cta['url'],
			Link_Controller::TARGET  => $cta['target'],
			Link_Controller::CLASSES => [
				'c-block__cta-link',
				'a-btn',
				'a-btn--has-icon-after',
				'icon-arrow-right'
			],
		] );
	}

	/**
	 * @return array
	 */
	public function get_image_args(): array {
		if ( ! $this->image ) {
			return [];
		}

		$src_size     = Image_Sizes::FOUR_THREE;
		$srcset_sizes = [
			Image_Sizes::FOUR_THREE_SMALL,
			Image_Sizes::FOUR_THREE,
			Image_Sizes::FOUR_THREE_LARGE,
		];

		if ( $this->layout === Media_Text_Block::MEDIA_CENTER ) {
			$src_size     = Image_Sizes::SIXTEEN_NINE;
			$srcset_sizes = [
				Image_Sizes::SIXTEEN_NINE_SMALL,
				Image_Sizes::SIXTEEN_NINE,
				Image_Sizes::SIXTEEN_NINE_LARGE,
			];
		}

		return [
			Image_Controller::ATTACHMENT   => Image::factory( (int) $this->image ),
			Image_Controller::AS_BG        => true,
			Image_Controller::USE_LAZYLOAD => true,
			Image_Controller::WRAPPER_TAG  => 'div',
			Image_Controller::CLASSES      => [ 'b-interstitial__figure' ],
			Image_Controller::IMG_CLASSES  => [ 'b-interstitial__img', 'c-image__bg' ],
			Image_Controller::SRC_SIZE     => $src_size,
			Image_Controller::SRCSET_SIZES => $srcset_sizes,
		];
	}

	/**
	 * @return string
	 */
	public function get_video_embed(): string {
		if ( ! $this->video ) {
			return '';
		}

		return $this->video;
	}

}
