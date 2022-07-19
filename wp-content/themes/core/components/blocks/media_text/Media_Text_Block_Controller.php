<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\blocks\media_text;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Libs\Field_Models\Models\Image;
use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Blocks\Types\Media_Text\Media_Text as Media_Text_Block;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Theme\Config\Image_Sizes;

class Media_Text_Block_Controller extends Abstract_Controller {

	public const ATTRS             = 'attrs';
	public const CLASSES           = 'classes';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CTA               = 'cta';
	public const DESCRIPTION       = 'description';
	public const IMAGE             = 'image';
	public const LAYOUT            = 'layout';
	public const LEADIN            = 'leadin';
	public const MEDIA_CLASSES     = 'media_classes';
	public const MEDIA_TYPE        = 'media_type';
	public const TITLE             = 'title';
	public const VIDEO             = 'video';
	public const WIDTH             = 'width';

	/**
	 * @var string[]
	 */
	private array $attrs;

	/**
	 * @var string[]
	 */
	private array $classes;

	/**
	 * @var string[]
	 */
	private array $container_classes;

	/**
	 * @var string[]
	 */
	private array $content_classes;

	private Cta $cta;

	/**
	 * @var string[]
	 */
	private array $media_classes;
	private Image $image;
	private string $description;
	private string $layout;
	private string $leadin;
	private string $media_type;
	private string $title;
	private string $video;
	private string $width;

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
		$this->image             = $args[ self::IMAGE ];
		$this->video             = (string) $args[ self::VIDEO ];
		$this->title             = (string) $args[ self::TITLE ];
		$this->leadin            = (string) $args[ self::LEADIN ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->cta               = $args[ self::CTA ];
	}

	public function get_classes(): string {
		$this->classes[] = 'c-block--layout-' . $this->layout;
		$this->classes[] = 'c-block--width-' . $this->width;

		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_container_classes(): string {
		if ( $this->width === Media_Text_Block::WIDTH_GRID ) {
			$this->container_classes[] = 'l-container';
		}

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

	public function get_content_args(): array {
		return [
			Content_Block_Controller::LEADIN  => $this->get_leadin(),
			Content_Block_Controller::TITLE   => $this->get_title(),
			Content_Block_Controller::CONTENT => $this->get_content(),
			Content_Block_Controller::CTA     => $this->get_cta(),
			Content_Block_Controller::LAYOUT  => $this->layout === Media_Text_Block::MEDIA_CENTER ? Content_Block_Controller::LAYOUT_INLINE : Content_Block_Controller::LAYOUT_LEFT,
			Content_Block_Controller::CLASSES => [
				'c-block__content-block',
				'c-block__header',
				'b-media-text__content-container',
			],
		];
	}

	public function get_image_args(): array {
		if ( ! $this->image->id ) {
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
			Image_Controller::IMG_ID       => $this->image->id,
			Image_Controller::SRC_SIZE     => $src_size,
			Image_Controller::SRCSET_SIZES => $srcset_sizes,
		];
	}

	public function get_video_embed(): string {
		if ( ! $this->video ) {
			return '';
		}

		return $this->video;
	}

	protected function defaults(): array {
		return [
			self::ATTRS             => [],
			self::CLASSES           => [],
			self::CONTAINER_CLASSES => [],
			self::CONTENT_CLASSES   => [],
			self::CTA               => new Cta(),
			self::DESCRIPTION       => '',
			self::IMAGE             => new Image(),
			self::LAYOUT            => Media_Text_Block::MEDIA_LEFT,
			self::LEADIN            => '',
			self::MEDIA_CLASSES     => [],
			self::MEDIA_TYPE        => Media_Text_Block::IMAGE,
			self::TITLE             => '',
			self::VIDEO             => '',
			self::WIDTH             => Media_Text_Block::WIDTH_GRID,
		];
	}

	protected function required(): array {
		return [
			self::CLASSES           => [ 'c-block', 'b-media-text' ],
			self::CONTAINER_CLASSES => [ 'b-media-text__container' ],
			self::CONTENT_CLASSES   => [ 'b-media-text__content' ],
			self::MEDIA_CLASSES     => [ 'b-media-text__media' ],
		];
	}

	private function get_leadin(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::CLASSES => [
				'c-block__leadin',
				'b-media-text__leadin',
				'h6',
			],
			Text_Controller::CONTENT => $this->leadin ?? '',
		] );
	}

	private function get_title(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::TAG     => 'h2',
			Text_Controller::CLASSES => [
				'c-block__title',
				'b-media-text__title',
				'h3',
			],
			Text_Controller::CONTENT => $this->title ?? '',
		] );
	}

	private function get_content(): Deferred_Component {
		return defer_template_part( 'components/container/container', null, [
			Container_Controller::CLASSES => [
				'c-block__description',
				'b-media-text__text',
			],
			Container_Controller::CONTENT => $this->description ?? '',
		] );
	}

	private function get_cta(): Deferred_Component {
		return defer_template_part( 'components/link/link', null, [
			Link_Controller::URL            => $this->cta->link->url,
			Link_Controller::CONTENT        => $this->cta->link->title ?: $this->cta->link->url,
			Link_Controller::TARGET         => $this->cta->link->target,
			Link_Controller::ADD_ARIA_LABEL => $this->cta->add_aria_label,
			Link_Controller::ARIA_LABEL     => $this->cta->aria_label,
			Link_Controller::CLASSES        => [
				'c-block__cta-link',
				'a-btn',
				'a-btn--has-icon-after',
				'icon-arrow-right',
			],
		] );
	}

}
