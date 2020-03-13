<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\ContentSlider as ContentSliderPanel;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Slider as SliderComponent;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Text;
use Tribe\Project\Templates\Components\Title;

class ContentSlider extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'slider' => $this->get_slider(),
		];

		return $data;
	}

	protected function get_slider(): string {
		$main_attrs = [];
		if ( is_panel_preview() ) {
			$main_attrs[ 'data-depth' ]    = $this->panel->get_depth();
			$main_attrs[ 'data-name' ]     = SliderComponent::SLIDES;
			$main_attrs[ 'data-livetext' ] = true;
		}
		$options = [
			SliderComponent::SLIDES          => $this->get_slides(),
			SliderComponent::THUMBNAILS      => false,
			SliderComponent::SHOW_CAROUSEL   => false,
			SliderComponent::SHOW_ARROWS     => true,
			SliderComponent::SHOW_PAGINATION => false,
			SliderComponent::MAIN_CLASSES    => $this->get_slider_main_classes(),
			SliderComponent::MAIN_ATTRS      => $main_attrs,
		];

		$slider = SliderComponent::factory( $options );

		return $slider->render();
	}

	protected function get_slides(): array {
		$slides = [];

		if ( ! empty( $this->panel_vars[ ContentSliderPanel::FIELD_SLIDES ] ) ) {
			$index = 0;
			foreach ( $this->panel_vars[ ContentSliderPanel::FIELD_SLIDES ] as $slide ) {

				$slide_markup = '';

				$options = [
					Image::IMG_ID          => $slide[ ContentSliderPanel::FIELD_SLIDE_IMAGE ],
					Image::AS_BG           => true,
					Image::USE_LAZYLOAD    => false,
					Image::WRAPPER_CLASSES => [ 'c-image__bg' ],
				];

				$image_obj    = Image::factory( $options );
				$slide_markup .= $image_obj->render();

				$options = [
					Content_Block::TITLE           => $this->get_content_block_title( $slide, $index ),
					Content_Block::TEXT            => $this->get_content_block_text( $slide, $index ),
					Content_Block::CLASSES         => [ 't-content c-content-block--content-slider' ],
					Content_Block::CONTENT_CLASSES => [ 'c-content-block__desc--content-slider' ],
					Content_Block::BUTTON          => $this->get_content_block_button( $slide ),
				];

				$content_block_obj = Content_Block::factory( $options );

				$slide_markup .= $content_block_obj->render();

				$slides[] = $slide_markup;
				$index++;
			}
		}

		return $slides;
	}

	protected function get_slider_main_classes() {
		$classes = [ 'c-slider__main' ];

		return $classes;
	}

	protected function get_content_block_title( $slide, $index ) {
		if ( !is_panel_preview() && empty( $slide[ ContentSliderPanel::FIELD_SLIDE_TITLE ] ) ) {
			return '';
		}
		$attrs = [];
		if ( is_panel_preview() ) {
			$attrs = [
				'data-depth'    => $this->panel->get_depth(),
				'data-name'     => ContentSliderPanel::FIELD_SLIDE_TITLE,
				'data-index'    => $index,
				'data-livetext' => true,
			];
		}
		$options = [
			Title::CLASSES => [ 'h2' ],
			Title::TITLE   => esc_html( $slide[ ContentSliderPanel::FIELD_SLIDE_TITLE ] ),
			Title::ATTRS   => $attrs,
			Title::TAG     => 'h2',
		];

		$title_object = Title::factory( $options );

		return $title_object->render();
	}

	protected function get_content_block_text( $slide, $index ) {

		if ( !is_panel_preview() && empty( $slide[ ContentSliderPanel::FIELD_SLIDE_CONTENT ] ) ) {
			return '';
		}
		$attrs = [];
		if ( is_panel_preview() ) {
			$attrs = [
				'data-depth'    => $this->panel->get_depth(),
				'data-name'     => ContentSliderPanel::FIELD_SLIDE_CONTENT,
				'data-index'    => $index,
				'data-autop'    => 'true',
				'data-livetext' => true,
			];
		}

		$options = [
			Text::ATTRS   => $attrs,
			Text::CLASSES => [],
			Text::TEXT    => $slide[ ContentSliderPanel::FIELD_SLIDE_CONTENT ],
		];

		$text_object = Text::factory( $options );

		return $text_object->render();
	}

	protected function get_content_block_button( $slide ) {
		if ( empty( $slide[ ContentSliderPanel::FIELD_SLIDE_CTA ][ Button::LABEL ] ) || empty( $slide[ ContentSliderPanel::FIELD_SLIDE_CTA ][ Button::URL ] ) ) {
			return '';
		}
		$options = [
			Button::CLASSES     => [ 'c-btn c-btn--sm' ],
			Button::ATTRS       => '',
			Button::TAG         => '',
			Button::TARGET      => $slide[ ContentSliderPanel::FIELD_SLIDE_CTA ][ Button::TARGET ],
			Button::LABEL       => $slide[ ContentSliderPanel::FIELD_SLIDE_CTA ][ Button::LABEL ],
			Button::URL         => $slide[ ContentSliderPanel::FIELD_SLIDE_CTA ][ Button::URL ],
			Button::BTN_AS_LINK => true,
		];

		$button_object = Button::factory( $options );

		return $button_object->render();
	}

	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/content-slider'];
	}
}
