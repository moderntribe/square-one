<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\gallery_grid;

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
use Tribe\Project\Templates\Components\dialog\Dialog_Controller;

class Gallery_Grid_Controller extends Abstract_Controller {

	public const ID                = 'id';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const CONTAINER_CLASSES = 'container_classes';
	public const CONTENT_CLASSES   = 'content_classes';
	public const TITLE             = 'title';
	public const DESCRIPTION       = 'description';
	public const CTA               = 'cta';
	public const GALLERY           = 'gallery';
	public const GRID_LAYOUT       = 'grid_layout';
	public const SLIDESHOW         = 'slideshow';

	public const ONE               = 'one';
	public const TWO               = 'two';
	public const THREE             = 'three';
	public const FOUR              = 'four';

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
	private string $grid_layout;
	private bool   $slideshow;
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
		$this->grid_layout       = (string) $args[ self::GRID_LAYOUT ];
		$this->slideshow         = (bool) $args[ self::SLIDESHOW ];
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
			self::GRID_LAYOUT       => 'three',
			self::SLIDESHOW         => false,
		];
	}

	protected function required(): array {
		return [
			self::CLASSES           => [ 'c-block', 'b-gallery-grid' ],
			self::CONTAINER_CLASSES => [ 'b-gallery-grid__container', 'l-container' ],
			self::CONTENT_CLASSES   => [ 'b-gallery-grid__content' ],
		];
	}

	public function get_block_id(): string {
		return $this->id;
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	/**
	 * @return bool
	 */
	public function is_slideshow(): bool {
		return $this->slideshow;
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
		$this->content_classes[] = 'gallery-layout--' . $this->grid_layout;

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
			Content_Block_Controller::TITLE   => $this->get_title(),
			Content_Block_Controller::CONTENT => $this->get_content(),
			Content_Block_Controller::CLASSES => [
				'c-block__content-block',
				'c-block__header',
				'b-gallery-grid'
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
				'b-gallery-grid__title',
				'h3',
			],
			Text_Controller::CONTENT => $this->title ?? '',
		] );
	}

	/**
	 * @return string
	 */
	public function get_slideshow_title() {
		return $this->title ?? '';
	}

	/**
	 * @return Deferred_Component
	 */
	private function get_content(): Deferred_Component {
		return defer_template_part( 'components/container/container', null, [
			Container_Controller::CLASSES => [
				'c-block__description',
				'b-gallery-grid__description',
				't-sink',
				's-sink',
			],
			Container_Controller::CONTENT => $this->description ?? '',
		] );
	}

	/**
	 * data-content attribute with a matching ID to the dialog is required for dialog to work.
	 *
	 * @return array
	 */
	public function get_slideshow_button() {
		return [
			Button_Controller::CLASSES    => [
				'c-block__cta-link',
				'a-btn',
				'a-btn--has-icon-after',
				'icon-arrow-right'
			],
			Button_Controller::ATTRS      => [ 'data-js'  => 'dialog-trigger', 'data-content' => 'dialog-content-' . $this->get_block_id() ],
			Button_Controller::CONTENT    => esc_html__( 'View slideshow', 'tribe' ),
		];
	}

	/**
	 * @return string
	 */
	protected function get_slider_options(): string {
		$args = [
			'preloadImages'         => "true",
			'lazy'                  => "true",
			'spaceBetween'          => 60,
			'keyboard'              => "true",
			'grabCursor'            => "true",
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
			Slider_Controller::CLASSES    => [ 'b-gallery-grid__slider' ],
		];

		return $slider;
	}

	/**
	 * @return array
	 */
	public function get_gallery_img_ids(): array {
		return ! empty( $this->gallery ) ? array_filter( wp_list_pluck( $this->gallery, 'id' ) ) : [];
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
	 * @param int $index
	 *
	 * @return Deferred_Component
	 */
	protected function gallery_count( int $index ): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			Text_Controller::CLASSES => [
				'b-gallery-grid__meta-count',
			],
			Text_Controller::CONTENT => sprintf( __( '%d of %d', 'tribe' ), $index + 1, count( $this->get_gallery_img_ids() ) ),
		] );
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
				'b-gallery-grid__meta-caption',
			],
			Text_Controller::CONTENT => esc_html( $thumbnail_image[0]->post_excerpt ) ?? '',
		] );
	}

	/**
	 * @param $img_id
	 *
	 * @return string
	 */
	protected function get_image_template( $index, $img_id ): string {
		$img          = $this->get_slide_img( $img_id );
		$slide_markup = $img;
		$slide_markup .= defer_template_part( 'components/container/container', null, [
			Container_Controller::CLASSES => [
				'b-gallery-grid__meta-wrap',
			],
			Container_Controller::CONTENT => $this->gallery_count( $index ) . $this->get_image_caption( $img_id ),
		] );

		return $slide_markup;
	}

	/**
	 * Get Slides
	 *
	 * @return array
	 */
	protected function get_slides(): array {
		$slide_ids = $this->get_gallery_img_ids();
		$slides    = [];

		if ( empty( $slide_ids ) ) {
			return $slides;
		}

		return array_map( function ( $index, $slide_id ) {
			return $this->get_image_template( $index, $slide_id );
		}, array_keys($slide_ids), $slide_ids );
	}

	/**
	 * @return array
	 */
	public function get_gallery_img_thumbs(): array {
		$ids          = $this->get_gallery_img_ids();
		$gallery_imgs = [];
		$i            = 1;
		$img_size     = Image_Sizes::SQUARE_MEDIUM;
		$img_bg       = true;
		$img_bg_class = 'c-image--bg';
		$img_aspect   = 's-aspect-ratio-1-1';
		$img_srcset   = [
			Image_Sizes::SQUARE_MEDIUM,
			Image_Sizes::SQUARE_XSMALL
		];

		if ( empty( $ids ) ) {
			return $gallery_imgs;
		}

		if ( $this->grid_layout === self::ONE ) {
			$img_size     = Image_Sizes::CORE_FULL;
			$img_bg       = false;
			$img_bg_class = '';
			$img_aspect   = '';
			$img_srcset   = [
				'medium',
				'medium_large',
				'large',
				Image_Sizes::CORE_FULL
			];
		}

		if ( $this->grid_layout === self::TWO ) {
			$img_size     = Image_Sizes::SQUARE_LARGE;
			$img_srcset   = [
				Image_Sizes::SQUARE_XSMALL,
				Image_Sizes::SQUARE_MEDIUM,
				Image_Sizes::SQUARE_LARGE

			];
		}

		foreach ( $ids as $id ) {
			$gallery_imgs[] = [
				Image_Controller::IMG_ID => $id,
				Image_Controller::AS_BG => $img_bg,
				Image_Controller::USE_LAZYLOAD => false,
				Image_Controller::WRAPPER_TAG => 'div',
				Image_Controller::CLASSES => [
					'b-gallery-grid__figure',
					'b-gallery-grid__figure--' . $i,
					$img_aspect,
					$img_bg_class
				],
				Image_Controller::IMG_CLASSES => [ 'b-gallery_img' ],
				Image_Controller::SRC_SIZE => $img_size,
				Image_Controller::SRCSET_SIZES => $img_srcset,
			];

			$i ++;
		}

		return $gallery_imgs;
	}

	/**
	 *
	 * @return array
	 */
	public function get_dialog_args(): array {
		return [
			Dialog_Controller::ID  => $this->get_block_id(),
			Dialog_Controller::TITLE  => $this->get_slideshow_title(),
			Dialog_Controller::CONTENT  => defer_template_part( 'components/slider/slider', null, $this->get_slider_args() ),
		];
	}
}
