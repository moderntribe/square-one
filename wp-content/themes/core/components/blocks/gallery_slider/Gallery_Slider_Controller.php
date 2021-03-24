<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\gallery_slider;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\container\Container_Controller;
use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\image\Image_Controller;
use Tribe\Project\Theme\Config\Image_Sizes;
use Tribe\Project\Templates\Components\button\Button_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Components\slider\Slider_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;

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

	public const FIXED    = 'fixed';
	public const VARIABLE = 'variable';

	/**
	 * @var string[]
	 */
	private array  $classes;
	private array  $attrs;
	private array  $container_classes;
	private array  $content_classes;
	private string $title;
	private string $description;
	private array  $cta;
	private array  $gallery;
	private string $image_ratio;
	private string $id;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes           = (array) $args[ self::CLASSES ];
		$this->attrs             = (array) $args[ self::ATTRS ];
		$this->container_classes = (array) $args[ self::CONTAINER_CLASSES ];
		$this->content_classes   = (array) $args[ self::CONTENT_CLASSES ];
		$this->title             = (string) $args[ self::TITLE ];
		$this->description       = (string) $args[ self::DESCRIPTION ];
		$this->cta               = (array) $args[ self::CTA ];
		$this->gallery           = (array) $args[ self::GALLERY ];
		$this->image_ratio       = (string) $args[ self::IMAGE_RATIO ];
		$this->id                = uniqid();
	}

	protected function defaults(): array {
		return [
			self::CLASSES           => [],
			self::ATTRS             => [],
			self::CONTAINER_CLASSES => [],
			self::CONTENT_CLASSES   => [],
			self::TITLE             => '',
			self::DESCRIPTION       => '',
			self::CTA               => [],
			self::GALLERY           => [],
			self::IMAGE_RATIO       => self::FIXED,
		];
	}

	protected function required(): array {
		return [
			self::CLASSES           => [ 'c-block', 'b-gallery-slider' ],
			self::CONTAINER_CLASSES => [ 'b-gallery-slider__container', ],
			self::CONTENT_CLASSES   => [ 'b-gallery-slider__content' ],
		];
	}

	public function get_block_id(): string {
		return $this->id;
	}

	public function get_classes(): string {
		$this->classes[] = 'b-gallery-slider--' . $this->image_ratio;

		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	/**
	 * @return string
	 */
	public function get_container_classes(): string {
		return Markup_Utils::class_attribute( $this->container_classes );
	}

	/**
	 * @return string
	 */
	public function get_content_classes(): string {
		return Markup_Utils::class_attribute( $this->content_classes );
	}

	/**
	 * @return array
	 */
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
				'b-gallery-slider__header'
			],
		];
	}

	/**
	 * @return Deferred_Component
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
	 * @return Deferred_Component
	 */
	private function get_content(): Deferred_Component {
		return defer_template_part( 'components/container/container', null, [
			Container_Controller::CLASSES => [
				'c-block__description',
				'b-gallery-slider__description',
				't-sink',
				's-sink',
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
	 * @return string
	 */
	protected function get_slider_options(): string {
		$args = [
			'preloadImages'  => "true",
			'lazy'           => "true",
			'keyboard'       => "true",
			'grabCursor'     => "true",
			'slidesPerView'  => 3,
			'centeredSlides' => "true",
			'spaceBetween'   => 40,
		];

		return json_encode( $args );
	}

	/**
	 * Get the Slider
	 *
	 * @return array
	 */
	public function get_slider_args(): array {
		$main_attrs = [];

		$main_attrs['data-swiper-options'] = $this->get_slider_options();

		$slider = [
			Slider_Controller::SLIDES     => $this->get_slides(),
			Slider_Controller::MAIN_ATTRS => $main_attrs,
			Slider_Controller::CLASSES    => [ 'b-gallery-slider__slider' ],
		];

		return $slider;
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
	 * @param $img_id
	 *
	 * @return string
	 */
	protected function get_image_template( $img_id ): string {
		$img          = $this->get_slide_img( $img_id );
		$slide_markup = $img;
		$slide_markup .= defer_template_part( 'components/container/container', null, [
			Container_Controller::CLASSES => [
				'b-gallery-slider__meta-wrap',
			],
			Container_Controller::CONTENT => $this->get_image_caption( $img_id ),
		] );

		return $slide_markup;
	}

	/**
	 * @param int $img_id
	 *
	 * @return Deferred_Component
	 */
	public function get_slide_img( int $img_id ): Deferred_Component {
		return defer_template_part(
			'components/image/image',
			null,
			[
				Image_Controller::IMG_ID       => $img_id,
				Image_Controller::AS_BG        => false,
				Image_Controller::USE_LAZYLOAD => false,
				Image_Controller::SRC_SIZE     => Image_Sizes::CORE_FULL,
				Image_Controller::SRCSET_SIZES => [
					'medium',
					'medium_large',
					'large',
					Image_Sizes::CORE_FULL,
				],
			],
		);
	}

	/**
	 * @param int $slide_id
	 *
	 * @return string|Deferred_Component
	 */
	protected function get_image_caption( int $slide_id ) {
		$thumbnail_image = get_posts( [ 'p' => $slide_id, 'post_type' => 'attachment' ] );

		if ( empty( $thumbnail_image ) && empty( $thumbnail_image[0] ) ) {
			return '';
		}

		return defer_template_part( 'components/text/text', null, [
			Text_Controller::CLASSES => [
				'b-gallery-slider__meta-caption',
			],
			Text_Controller::CONTENT => esc_html( $thumbnail_image[0]->post_excerpt ) ?? '',
		] );
	}
}
