<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\Hero as HeroPanel;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Text;
use Tribe\Project\Templates\Components\Title;

class Hero extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'text_color'    => $this->text_color(),
			'layout'        => $this->get_layout(),
			'image'         => $this->get_image(),
			'content_block' => $this->get_content_block(),
		];

		return $data;
	}

	protected function get_image() {

		if ( empty( $this->panel_vars[ HeroPanel::FIELD_IMAGE ] ) ) {
			return false;
		}

		$options = [
			Image::IMG_ID          => $this->panel_vars[ HeroPanel::FIELD_IMAGE ],
			Image::COMPONENT_CLASS => 'c-image',
			Image::AS_BG           => true,
			Image::USE_LAZYLOAD    => false,
			Image::ECHO            => false,
			Image::WRAPPER_CLASS   => 'c-image__bg',
		];

		$image_obj = Image::factory( $options );

		return $image_obj->render();
	}

	protected function get_content_block() {

		$title_attrs       = [];
		$description_attrs = [];

		if ( is_panel_preview() ) {
			$title_attrs = [
				'data-depth'    => $this->panel->get_depth(),
				'data-name'     => esc_attr( HeroPanel::FIELD_TITLE ),
				'data-livetext' => true,
			];

			$description_attrs = [
				'data-depth'    => $this->panel->get_depth(),
				'data-name'     => esc_attr( HeroPanel::FIELD_DESCRIPTION ),
				'data-autop'    => 'true',
				'data-livetext' => true,
			];
		}

		$options = [
			Content_Block::TITLE           => $this->get_hero_title( $title_attrs ),
			Content_Block::TEXT            => $this->get_hero_text( $description_attrs ),
			Content_Block::BUTTON          => $this->get_hero_button(),
			Content_Block::CLASSES         => [],
			Content_Block::CONTENT_CLASSES => [],
		];

		$content_block_obj = Content_Block::factory( $options );

		return $content_block_obj->render();
	}

	protected function get_layout() {

		$classes = [];

		if ( HeroPanel::FIELD_LAYOUT_OPTION_CONTENT_RIGHT === $this->panel_vars[ HeroPanel::FIELD_LAYOUT ] ) {
			$classes[] = 'g-row--pull-right';
		}

		if ( HeroPanel::FIELD_LAYOUT_OPTION_CONTENT_CENTER === $this->panel_vars[ HeroPanel::FIELD_LAYOUT ] ) {
			$classes[] = 'g-row--center u-text-align-center';
		}

		return implode( ' ', $classes );
	}

	protected function text_color() {

		$classes = [];

		if ( HeroPanel::FIELD_TEXT_LIGHT === $this->panel_vars[ HeroPanel::FIELD_TEXT_COLOR ] ) {
			$classes[] = 't-content--light';
		}

		if ( HeroPanel::FIELD_TEXT_DARK === $this->panel_vars[ HeroPanel::FIELD_TEXT_COLOR ] ) {
			$classes[] = 't-content--dark';
		}

		return implode( ' ', $classes );
	}

	protected function get_hero_title( $title_attrs ) {
		$options = [
			Title::CLASSES => [],
			Title::TAG     => 'h1',
			Title::ATTRS   => $title_attrs,
			Title::TITLE   => $this->panel_vars[ HeroPanel::FIELD_TITLE ],
		];

		$title_object = Title::factory( $options );

		return $title_object->render();
	}

	protected function get_hero_text( $description_attrs ) {
		$options = [
			Text::ATTRS   => $description_attrs,
			Text::CLASSES => [ 'site-panel--hero__desc' ],
			Text::TEXT    => $this->panel_vars[ HeroPanel::FIELD_DESCRIPTION ],
		];

		$text_object = Text::factory( $options );

		return $text_object->render();
	}

	protected function get_hero_button() {
		$options = [
			Button::CLASSES     => [ 'c-btn' ],
			Button::ATTRS       => '',
			Button::TAG         => '',
			Button::TARGET      => $this->panel_vars[ HeroPanel::FIELD_CTA ][ Button::TARGET ],
			Button::BTN_AS_LINK => true,
			Button::URL         => $this->panel_vars[ HeroPanel::FIELD_CTA ][ Button::URL ],
			Button::LABEL       => $this->panel_vars[ HeroPanel::FIELD_CTA ][ Button::LABEL ],
		];

		$button_object = Button::factory( $options );

		return $button_object->render();
	}

	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/hero'];
	}
}
