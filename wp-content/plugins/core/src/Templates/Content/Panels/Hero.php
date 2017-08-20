<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\Hero as HeroPanel;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Content_Block;

class Hero extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	protected function get_image() {

		if ( empty( $this->panel_vars[ HeroPanel::FIELD_IMAGE ] ) ) {
			return false;
		}

		$options = [
			'img_id'          => $this->panel_vars[ HeroPanel::FIELD_IMAGE ],
			'component_class' => 'c-image',
			'as_bg'           => true,
			'use_lazyload'    => false,
			'echo'            => false,
			'wrapper_class'   => 'c-image__bg',
		];

		$image_obj = Image::factory( $options );

		return $image_obj->render();
	}

	protected function get_content_block() {

		$title_attrs       = [];
		$description_attrs = [];

		if ( $this->panel_vars[ HeroPanel::FIELD_TITLE ] ||
		     $this->panel_vars[ HeroPanel::FIELD_DESCRIPTION ] ||
		     is_panel_preview() ) {

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
			Content_Block::TITLE             => $this->panel_vars[ HeroPanel::FIELD_TITLE ],
			Content_Block::DESCRIPTION       => $this->panel_vars[ HeroPanel::FIELD_DESCRIPTION ],
			Content_Block::CTA               => $this->panel_vars[ HeroPanel::FIELD_CTA ],
			Content_Block::TITLE_ATTRS       => $title_attrs,
			Content_Block::DESCRIPTION_ATTRS => $description_attrs,
			Content_Block::TITLE_TAG         => 'h1',
		];

		$content_block_obj = Content_Block::factory( $options );

		return $content_block_obj->render();
	}

	protected function get_layout() {

		$classes = [];

		if ( HeroPanel::FIELD_LAYOUT_OPTION_CONTENT_RIGHT === $this->panel_vars[ HeroPanel::FIELD_LAYOUT ] ) {
			$classes[] = 'site-grid--pull-right';
		}

		if ( HeroPanel::FIELD_LAYOUT_OPTION_CONTENT_CENTER === $this->panel_vars[ HeroPanel::FIELD_LAYOUT ] ) {
			$classes[] = 'site-grid--center u-text-center';
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

	public function get_mapped_panel_data(): array {
		$data = [
			'text_color'    => $this->text_color(),
			'layout'        => $this->get_layout(),
			'image'         => $this->get_image(),
			'content_block' => $this->get_content_block(),
		];

		return $data;
	}
}
