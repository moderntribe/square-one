<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\ContentCarousel as ContentCarouselPanel;
use Tribe\Project\Templates\Components\Card;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Slider;
use Tribe\Project\Templates\Components\Text;
use Tribe\Project\Templates\Components\Title;
use Tribe\Project\Theme\Image_Sizes;
use Tribe\Project\Theme\Util;

class ContentCarousel extends Panel {

	/**
	 * Get the data.
	 *
	 * @return array
	 */
	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	/**
	 * Get the mapped panel data.
	 *
	 * @return array
	 */
	public function get_mapped_panel_data(): array {
		$data = [
			'title'    => $this->get_title( $this->panel_vars[ ContentCarouselPanel::FIELD_TITLE ], [ 's-title', 'h2' ] ),
			'carousel' => $this->get_slider(),
			'attrs'    => $this->get_panel_attributes(),
		];

		return $data;
	}

	/**
	 * Overrides `get_classes()` from the Panel parent class.
	 *
	 * Return value is available in the twig template via the `classes` twig variable in the parent class.
	 *
	 * @return string
	 */
	protected function get_classes(): string {
		$classes = [
			'panel',
			's-wrapper',
			'site-panel',
			's-wrapper--no-padding',
			'c-slider__carousel-pull-right',
			sprintf( 'site-panel--%s', $this->panel->get_type_object()->get_id() ),
		];

		return Util::class_attribute( $classes );
	}

	/**
	 * Set attributes for use by Live Preview mode.
	 *
	 * @return string
	 */
	protected function get_panel_attributes(): string {
		$attrs = '';

		if ( is_panel_preview() ) {
			$attrs = 'data-depth=' . $this->panel->get_depth() . ' data-name="' . esc_attr( ContentCarouselPanel::FIELD_POSTS ) . '" data-index="0" data-livetext="true"';
		}

		if ( empty( $attrs ) ) {
			return '';
		}

		return $attrs;
	}

	/**
	 * Get Slider using the Slider Component.
	 *
	 * @return string
	 */
	protected function get_slider(): string {
		$main_attrs = [];

		if ( is_panel_preview() ) {
			$main_attrs[ 'data-depth' ]    = $this->panel->get_depth();
			$main_attrs[ 'data-name' ]     = Slider::SLIDES;
			$main_attrs[ 'data-livetext' ] = true;
		}

		$swiper_options = '{
		"ally":"true",
		"keyboard":"true",
		"grabCursor":"true",
		"slidesPerView":"auto",
		"freeMode":"true",
		"spaceBetween":0}';

		$main_attrs['data-swiper-options'] = $swiper_options;

		$options = [
			Slider::SLIDES          => $this->get_posts(),
			Slider::THUMBNAILS      => false,
			Slider::SHOW_CAROUSEL   => false,
			Slider::SHOW_ARROWS     => true,
			Slider::SHOW_PAGINATION => false,
			Slider::MAIN_CLASSES    => [ 'c-slider__carousel-pull-right__wrap' ],
			Slider::MAIN_ATTRS      => $main_attrs,
			Slider::SLIDE_CLASSES	=> [ 'c-slider__carousel-pull-right__slide' ],
		];

		$slider = Slider::factory( $options );

		return $slider->render();
	}

	/**
	 * Get posts and output using the Card component.
	 *
	 * @return array
	 */
	protected function get_posts(): array {
		$posts = [];

		if ( ! empty( $this->panel_vars[ ContentCarouselPanel::FIELD_POSTS ] ) ) {

			for ( $i = 0; $i < count( $this->panel_vars[ ContentCarouselPanel::FIELD_POSTS ] ); $i ++ ) {

				$post = $this->panel_vars[ ContentCarouselPanel::FIELD_POSTS ][ $i ];

				$options = [
					Card::PRE_TITLE  => $this->get_categories( $post['post_id'] ),
					Card::IMAGE      => $this->get_post_image( $post[ 'image' ], $post['link'] ),
					Card::POST_TITLE => $this->get_post_title( esc_html( $post['title'] ), $post['link'], $i ),
					Card::CLASSES	 => [ 'c-card--simple c-slider__carousel-pull-right__card' ],
				];

				$post_obj = Card::factory( $options );
				$posts[]  = $post_obj->render();
			}
		}

		return $posts;
	}

	/**
	 * Get post image using the Image component.
	 *
	 * @param $image_id
	 * @param $link
	 *
	 * @return string
	 */
	protected function get_post_image( $image_id, $link ): string {
		if ( empty( $image_id ) ) {
			return '';
		}

		$options = [
			Image::IMG_ID       => esc_attr( $image_id ),
			Image::AS_BG        => false,
			Image::USE_LAZYLOAD => false,
			Image::ECHO         => false,
			Image::SRC_SIZE     => Image_Sizes::COMPONENT_CARD,
			Image::LINK			=> esc_url( $link['url'] ),
		];

		$image_obj = Image::factory( $options );

		return $image_obj->render();
	}

	/**
	 * Get the categories.
	 *
	 * @param $id
	 *
	 * @return string
	 */
	protected function get_categories( int $id ): string {
		$categories = get_the_category_list( '', '', $id );

		if ( empty( $categories ) ) {
			return '';
		}

		$options = [
			Text::TEXT     => $categories,
			Title::CLASSES => [ 'c-card__link-list' ],
			Title::TAG     => 'div',
		];

		$text_obj = Text::factory( $options );

		return $text_obj->render();
	}

	/**
	 * Get post title using the Title component.
	 *
	 * @param $title
	 * @param $link
	 * @param $index
	 *
	 * @return string
	 */
	protected function get_post_title( $title, $link, $index ): string {
		if ( empty( $title ) ) {
			return '';
		}

		$attrs = [];

		if ( is_panel_preview() ) {
			$attrs = [
				'data-depth'    => $this->panel->get_depth(),
				'data-name'     => esc_attr( $title ),
				'data-index'    => esc_attr( $index ),
				'data-livetext' => true,
			];
		}

		$options = [
			Title::TITLE   => '<a href="' . esc_url( $link['url'] ) . '">' . esc_html( $title ) . '</a>',
			Title::CLASSES => [ 'h6' ],
			Title::ATTRS   => $attrs,
			Title::TAG     => 'h2',
		];

		$title_obj = Title::factory( $options );

		return $title_obj->render();
	}

	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/contentcarousel'];
	}
}
