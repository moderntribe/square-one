<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\Gallery as GalleryPanel;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Dialog;
use Tribe\Project\Templates\Components\Image as ImageComponent;
use Tribe\Project\Templates\Components\Slider as SliderComponent;
use Tribe\Project\Templates\Components\Text;
use Tribe\Project\Theme\Image_Sizes;
use Tribe\Project\Theme\Util;

class Gallery extends Panel {

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
	 * Get mapped panel date
	 *
	 * @return array
	 */
	public function get_mapped_panel_data(): array {
		$data = [
			'title'          => $this->get_title( $this->panel_vars[ GalleryPanel::FIELD_TITLE ], [ 's-title', 'h2' ] ),
			'gallery_images' => $this->get_gallery_images(),
			'dialog'         => $this->get_dialog_popup(),
			'columns'        => $this->get_column_layout(),
			'content_layout' => $this->get_content_layout(),
			'button'		 => $this->get_button(),
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
		$content_layout = $this->get_content_layout_section_class();

		$classes = [
			'panel',
			's-wrapper',
			'site-panel',
			sprintf( 'site-panel--%s', $this->panel->get_type_object()->get_id() ),
			$content_layout,
		];

		return Util::class_attribute( $classes );
	}

	/**
	 * Get Slides
	 *
	 * @param string $size
	 * @return array
	 */
	protected function get_slides( $size = 'full' ): array {
		$slide_ids    = $this->panel_vars[ GalleryPanel::FIELD_GALLERY ];

		if ( empty( $slide_ids ) ) {
			return [];
		}

		return array_map( function ( $slide_id ) use ( $size ) {
			$slide_markup = '';

			$options = [
				ImageComponent::IMG_ID        => $slide_id,
				ImageComponent::AS_BG         => false,
				ImageComponent::USE_LAZYLOAD  => false,
				ImageComponent::ECHO          => false,
				ImageComponent::USE_WRAPPER   => false,
				ImageComponent::SRC_SIZE      => Image_Sizes::CORE_RECTANGLE,
			];

			$image = ImageComponent::factory( $options );

			$slide_markup .= $image->render();
			$slide_markup .= $this->get_image_caption( $slide_id );

			$slides[] = $slide_markup;

			return $slide_markup;

		}, $slide_ids );
	}

	/**
	 * Get the Image Caption using the Text Component.
	 *
	 * @param int $slide_id The slide ID
	 * @return string
	 */
	protected function get_image_caption( int $slide_id ): string {
		$thumbnail_image = get_posts( array( 'p' => $slide_id, 'post_type' => 'attachment' ) );

		if ( empty( $thumbnail_image ) && empty( $thumbnail_image[0] ) ) {
			return '';
		}

		$options = [
			Text::CLASSES => [ 'site-panel--gallery__slide-caption' ],
			Text::TEXT    => esc_html( $thumbnail_image[0]->post_excerpt ),
		];

		$text_object = Text::factory( $options );

		return $text_object->render();
	}

	/**
	 * Get the gallery images.
	 *
	 * @param string $size
	 * @return array
	 */
	protected function get_gallery_images( $size = 'full' ): array {
		$slide_ids = $this->panel_vars[ GalleryPanel::FIELD_GALLERY ];

		if ( empty( $slide_ids ) ) {
			return [];
		}

		return array_map( function ( $slide_id ) use ( $size ) {

			$options = [
				ImageComponent::IMG_ID        => $slide_id,
				ImageComponent::AS_BG         => false,
				ImageComponent::USE_LAZYLOAD  => false,
				ImageComponent::ECHO          => false,
				ImageComponent::USE_WRAPPER   => false,
				ImageComponent::SRC_SIZE      => Image_Sizes::CORE_SQUARE,
			];

			$image = ImageComponent::factory( $options );

			return $image->render();

		}, $slide_ids );
	}

	/**
	 * Get the Slider
	 *
	 * @return string
	 */
	protected function get_slider(): string {
		$main_attrs = [];

		if ( is_panel_preview() ) {
			$main_attrs[ 'data-depth' ]    = $this->panel->get_depth();
			$main_attrs[ 'data-name' ]     = SliderComponent::SLIDES;
			$main_attrs[ 'data-livetext' ] = true;
		}

		$json_options = '{"spaceBetween":60,"ally":"true","keyboard":"true","grabCursor":"true","pagination":{"el":".swiper-pagination","type":"fraction"},"navigation":{"nextEl":".swiper-button-next","prevEl":".swiper-button-prev"}}';

		$main_attrs['data-swiper-options'] = $json_options;
		$main_attrs['data-id'] = 'dialog-' . get_nest_index();

		$options = [
			SliderComponent::SLIDES          => $this->get_slides(),
			SliderComponent::THUMBNAILS      => false,
			SliderComponent::SHOW_CAROUSEL   => false,
			SliderComponent::SHOW_ARROWS     => true,
			SliderComponent::SHOW_PAGINATION => true,
			SliderComponent::MAIN_CLASSES    => [ 'c-slider__main ' ],
			SliderComponent::MAIN_ATTRS      => $main_attrs,
			SliderComponent::CLASSES		 => [ 'site-panel--gallery__slider' ]
		];

		$slider = SliderComponent::factory( $options );

		return $slider->render();
	}

	/**
	 * Get the Dialog Popup
	 *
	 * @return string
	 */
	protected function get_dialog_popup(): string {
		$lightbox_toggle = $this->lightbox_on();

		if ( false === $lightbox_toggle ) {
			return '';
		}

		$options = [
			Dialog::CONTENT        => $this->get_slider(),
			Dialog::HEADER_CONTENT => $this->get_title( $this->panel_vars[ GalleryPanel::FIELD_TITLE ], [ 's-title c-dialog__title', 'h5' ] ),
			Dialog::DIALOG_ATTRS => [
				'data-js' => 'dialog-' . get_nest_index(),
			],
		];

		$dialog = Dialog::factory( $options );

		return $dialog->render();
	}

	/**
	 * Lightbox toggle
	 *
	 * @return bool
	 */
	protected function lightbox_on(): bool {
		$lightbox_on = true;

		if ( ! empty( $this->panel_vars[ GalleryPanel::FIELD_LIGHTBOX ] ) && $this->panel_vars[ GalleryPanel::FIELD_LIGHTBOX ] == GalleryPanel::OPTION_LIGHTBOX_OFF ) {
			$lightbox_on = false;
		}

		return $lightbox_on;
	}

	/**
	 * Get Content Layout
	 *
	 * @return string
	 */
	protected function get_content_layout(): string {

		$classes = '';

		if ( GalleryPanel::OPTION_CONTENT_INLINE === $this->panel_vars[ GalleryPanel::FIELD_CONTENT_LAYOUT ] ) {
			$classes = 'g-row--col-2--min-full';
		}

		return $classes;
	}

	/**
	 * Get Content Layout
	 *
	 * @return string
	 */
	protected function get_content_layout_section_class(): string {

		$classes = '';

		if ( GalleryPanel::OPTION_CONTENT_INLINE === $this->panel_vars[ GalleryPanel::FIELD_CONTENT_LAYOUT ] ) {
			$classes = 'site-panel--gallery--content-inline';
		}

		return $classes;
	}

	/**
	 * Get Column Layout
	 *
	 * @return string
	 */
	protected function get_column_layout(): string {

		$classes = '';

		if ( GalleryPanel::OPTION_TWO_COLUMNS === $this->panel_vars[ GalleryPanel::FIELD_COLUMNS ] ) {
			$classes = 'g-row--col-2--min-xxsmall';
		}

		if ( GalleryPanel::OPTION_THREE_COLUMNS === $this->panel_vars[ GalleryPanel::FIELD_COLUMNS ] ) {
			$classes = 'g-row--col-3--min-full';
		}

		if ( GalleryPanel::OPTION_FOUR_COLUMNS === $this->panel_vars[ GalleryPanel::FIELD_COLUMNS ] ) {
			$classes = 'g-row--col-2--min-xxsmall g-row--col-4--min-full';
		}

		return $classes;
	}

	/**
	 * Get the button using the Button Component.
	 *
	 * @return string
	 */
	protected function get_button(): string {
		$btn_label = $this->panel_vars[ GalleryPanel::FIELD_BUTTON_LABEL ];

		if ( empty( $btn_label ) ) {
			return '';
		}

		$options = [
			Button::LABEL       => esc_html( $btn_label ),
			Button::BTN_AS_LINK => false,
			Button::CLASSES     => [ 'c-btn site-panel--gallery__btn' ],
			Button::ATTRS		=> [
				'data-js'        => 'c-dialog-trigger',
				'data-content'   => 'dialog-' . get_nest_index(),
			],
		];

		$button_obj = Button::factory( $options );

		return $button_obj->render();
	}

	/**
	 * Instance
	 *
	 * @return mixed
	 */
	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/gallery'];
	}
}
