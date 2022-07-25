<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\blocks\gallery_slider;

use Tribe\Libs\Field_Models\Models\Link;
use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\slider\Slider_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;
use Tribe\Project\Theme\Config\Image_Sizes;

class Gallery_Slider_Controller extends Abstract_Controller {

	public const ID                = 'id';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const TITLE             = 'title';
	public const DESCRIPTION       = 'description';
	public const CTA               = 'cta';
	public const GALLERY           = 'gallery';
	public const IMAGE_RATIO       = 'image_ratio';
	public const CAPTION_DISPLAY   = 'caption_display';

	public const FIXED    = 'fixed';
	public const VARIABLE = 'variable';

	public const CAPTION_DISPLAY_SHOW = 'caption_display_show';

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

	private Link $cta;

	/**
	 * @var string[]
	 */
	private array $gallery;
	private string $caption_display;
	private string $description;
	private string $image_ratio;
	private string $title;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->caption_display   = (string) $args[ self::CAPTION_DISPLAY ];
		$this->classes           = (array) $args[ self::CLASSES ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->cta               = $args[ self::CTA ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->gallery           = (array) $args[ self::GALLERY ];
		$this->image_ratio       = (string) $args[ self::IMAGE_RATIO ];
		$this->title             = (string) $args[ self::TITLE ];
	}

	public function get_classes(): string {
		$this->classes[] = 'b-gallery-slider--' . $this->image_ratio;

		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_container_classes(): string {
		return Markup_Utils::class_attribute( $this->container_classes );
	}

	public function get_content_classes(): string {
		if ( self::VARIABLE === $this->image_ratio ) {
			$this->content_classes[] = 'l-container';
		}

		return Markup_Utils::class_attribute( $this->content_classes );
	}

	public function get_header_args(): array {
		if ( empty( $this->title ) && empty( $this->description ) ) {
			return [];
		}

		return [
			Content_Block_Controller::TAG     => 'header',
			Content_Block_Controller::LAYOUT  => Content_Block_Controller::LAYOUT_CENTER,
			Content_Block_Controller::TITLE   => $this->get_title(),
			Content_Block_Controller::CONTENT => $this->get_content(),
			Content_Block_Controller::CTA     => $this->get_cta(),
			Content_Block_Controller::CLASSES => [
				'c-block__content-block',
				'c-block__header',
				'b-gallery-slider__header',
			],
		];
	}

	/**
	 * Get the Slider
	 *
	 * @return array
	 */
	public function get_slider_args(): array {
		$main_attrs = [];

		$main_attrs['data-swiper-options'] = $this->get_slider_options();

		return [
			Slider_Controller::SLIDES       => $this->get_slides(),
			Slider_Controller::MAIN_ATTRS   => $main_attrs,
			Slider_Controller::CLASSES      => [ 'b-gallery-slider__slider', 'c-slider--arrows-below' ],
			Slider_Controller::MAIN_CLASSES => [ 'b-gallery-slider--' . $this->image_ratio ],
		];
	}

	/**
	 * Get Slides
	 *
	 * @return array
	 */
	public function get_slides(): array {
		$img_ids = ! empty( $this->gallery ) ? array_filter( wp_list_pluck( $this->gallery, 'id' ) ) : [];
		$slides  = [];

		if ( empty( $img_ids ) ) {
			return $slides;
		}

		foreach ( $img_ids as $img_id ) {
			$slides[] = $this->get_image_template( $img_id );
		}

		return $slides;
	}

	/**
	 * @param int $img_id
	 *
	 * @return \Tribe\Project\Templates\Components\Deferred_Component
	 */
	public function get_slide_img( int $img_id ): Deferred_Component {
		$image_args = [
			Image_Controller::IMG_ID       => $img_id,
			Image_Controller::CLASSES      => [ 'b-gallery-slider__image' ],
			Image_Controller::AS_BG        => false,
			Image_Controller::USE_LAZYLOAD => false,
			Image_Controller::SRC_SIZE     => Image_Sizes::CORE_FULL,
			Image_Controller::SRCSET_SIZES => [
				'medium',
				'large',
				Image_Sizes::CORE_MOBILE,
				Image_Sizes::CORE_FULL,
			],
		];

		// Fixed image ratio settings.
		if ( self::FIXED === $this->image_ratio ) {
			$image_args[ Image_Controller::CLASSES ][]    = 's-aspect-ratio-16-9';
			$image_args[ Image_Controller::SRC_SIZE ]     = Image_Sizes::SIXTEEN_NINE;
			$image_args[ Image_Controller::SRCSET_SIZES ] = [
				Image_Sizes::SIXTEEN_NINE_SMALL,
				Image_Sizes::SIXTEEN_NINE,
				Image_Sizes::SIXTEEN_NINE_LARGE,
			];
		}

		return defer_template_part( 'components/image/image', null, $image_args );
	}

	protected function defaults(): array {
		return [
			self::ATTRS             => [],
			self::CAPTION_DISPLAY   => self::CAPTION_DISPLAY_SHOW,
			self::CLASSES           => [],
			self::CONTAINER_CLASSES => [],
			self::CONTENT_CLASSES   => [],
			self::CTA               => new Link(),
			self::DESCRIPTION       => '',
			self::GALLERY           => [],
			self::IMAGE_RATIO       => self::FIXED,
			self::TITLE             => '',
		];
	}

	protected function required(): array {
		return [
			self::CLASSES           => [ 'c-block', 'b-gallery-slider' ],
			self::CONTAINER_CLASSES => [ 'b-gallery-slider__container' ],
			self::CONTENT_CLASSES   => [ 'b-gallery-slider__content' ],
		];
	}

	private function get_slider_options(): string {
		$args = [
			'slidesPerView' => 'auto',
			'spaceBetween'  => 40,
			'preloadImages' => "true",
			'lazy'          => "true",
			'keyboard'      => "true",
			'grabCursor'    => "true",
		];

		// Fixed image ratio swiper options.
		// 'loopedSlides' => {number} slide count, not sure if needed? testing works without
		if ( self::FIXED === $this->image_ratio ) {
			$args['loop']           = "true";
			$args['centeredSlides'] = "true";
		}

		// Variable image ratio swiper options.
		if ( self::VARIABLE === $this->image_ratio ) {
			$args['spaceBetween'] = 0;
		}

		return json_encode( $args );
	}

	/**
	 * Get markup with or without caption based on block setting.
	 *
	 * @param int $img_id
	 *
	 * @return string
	 */
	private function get_image_template( int $img_id ): string {
		if ( self::CAPTION_DISPLAY_SHOW === $this->caption_display ) {
			$slide_markup  = $this->get_slide_img( $img_id );
			$slide_markup .= defer_template_part( 'components/container/container', null, [
				Container_Controller::CLASSES => [
					'b-gallery-slider__meta-wrap',
				],
				Container_Controller::CONTENT => $this->get_image_caption( $img_id ),
			] );
		} else {
			$slide_markup = $this->get_slide_img( $img_id )->render();
		}

		return $slide_markup;
	}

	private function get_image_caption( int $slide_id ): ?Deferred_Component {
		$thumbnail_image = get_posts( [ 'p' => $slide_id, 'post_type' => 'attachment' ] );

		if ( empty( $thumbnail_image[0]->post_excerpt ) ) {
			return null;
		}

		return defer_template_part( 'components/text/text', null, [
			Text_Controller::CLASSES => [
				'b-gallery-slider__meta-caption',
			],
			Text_Controller::CONTENT => esc_html( $thumbnail_image[0]->post_excerpt ),
		] );
	}

	/**
	 * @return \Tribe\Project\Templates\Components\Deferred_Component
	 */
	private function get_title(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::TAG     => 'h2',
			Text_Controller::CLASSES => [
				'c-block__title',
				'b-gallery-slider__title',
				'h3',
			],
			Text_Controller::CONTENT => $this->title ?? '',
		] );
	}

	/**
	 * @return \Tribe\Project\Templates\Components\Deferred_Component
	 */
	private function get_content(): Deferred_Component {
		return defer_template_part( 'components/container/container', null, [
			Container_Controller::CLASSES => [
				'c-block__description',
				'b-gallery-slider__description',
			],
			Container_Controller::CONTENT => $this->description ?? '',
		] );
	}

	private function get_cta(): ?Deferred_Component {
		if ( ! $this->cta->url ) {
			return null;
		}

		return defer_template_part( 'components/link/link', null, [
			Link_Controller::URL     => $this->cta->url,
			Link_Controller::CONTENT => $this->cta->title ?: $this->cta->url,
			Link_Controller::TARGET  => $this->cta->target,
			Link_Controller::CLASSES => [
				'c-block__cta-link',
				'a-btn',
				'a-btn--has-icon-after',
				'icon-arrow-right',
			],
		] );
	}

}
