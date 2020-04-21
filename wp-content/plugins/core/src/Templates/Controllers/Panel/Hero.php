<?php

namespace Tribe\Project\Templates\Controllers\Panel;

use Exception;
use Tribe\Project\Panels\Types\Hero as HeroPanel;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Link;
use Tribe\Project\Templates\Components\Panels\Hero as Hero_Context;
use Tribe\Project\Templates\Components\Text;

class Hero extends Panel {
	use Traits\Unwrapped;
	use Traits\Headerless;

	protected function render_content( \ModularContent\Panel $panel, array $panel_vars ): string {
		return $this->factory->get( Hero_Context::class, [
			Hero_Context::COLOR         => $this->text_color( $panel_vars ),
			Hero_Context::LAYOUT        => $this->get_layout( $panel_vars ),
			Hero_Context::IMAGE         => $this->get_image( $panel_vars ),
			Hero_Context::CONTENT_BLOCK => $this->get_content_block( $panel, $panel_vars ),
		] )->render();
	}

	protected function get_image( array $panel_vars ) {

		if ( empty( $panel_vars[ HeroPanel::FIELD_IMAGE ] ) ) {
			return '';
		}

		try {
			$image = \Tribe\Project\Templates\Models\Image::factory( $panel_vars[ HeroPanel::FIELD_IMAGE ] );
		} catch ( Exception $e ) {
			return '';
		}

		$options = [
			Image::ATTACHMENT      => $image,
			Image::IMG_CLASSES     => [ 'c-image' ],
			Image::AS_BG           => true,
			Image::USE_LAZYLOAD    => false,
			Image::WRAPPER_CLASSES => [ 'c-image__bg' ],
		];

		return $this->factory->get( Image::class, $options )->render();
	}

	protected function get_content_block( \ModularContent\Panel $panel, array $panel_vars ) {
		$depth = $panel->get_depth();

		$title_attrs       = [];
		$description_attrs = [];

		if ( is_panel_preview() ) {

			$title_attrs = [
				'data-depth'    => $depth,
				'data-name'     => esc_attr( HeroPanel::FIELD_TITLE ),
				'data-livetext' => true,
			];

			$description_attrs = [
				'data-depth'    => $depth,
				'data-name'     => esc_attr( HeroPanel::FIELD_DESCRIPTION ),
				'data-autop'    => 'true',
				'data-livetext' => true,
			];
		}

		$options = [
			Content_Block::TITLE           => $this->get_hero_title( $title_attrs, $panel_vars ),
			Content_Block::TEXT            => $this->get_hero_text( $description_attrs, $panel_vars ),
			Content_Block::BUTTON          => $this->get_hero_button( $panel_vars ),
			Content_Block::CLASSES         => [],
			Content_Block::CONTENT_CLASSES => [],
		];

		return $this->factory->get( Content_Block::class, $options )->render();
	}

	protected function get_layout( array $panel_vars ): array {

		$classes = [];

		if ( HeroPanel::FIELD_LAYOUT_OPTION_CONTENT_RIGHT === $panel_vars[ HeroPanel::FIELD_LAYOUT ] ) {
			$classes[] = 'g-row--pull-right';
		}

		if ( HeroPanel::FIELD_LAYOUT_OPTION_CONTENT_CENTER === $panel_vars[ HeroPanel::FIELD_LAYOUT ] ) {
			$classes[] = 'g-row--center';
			$classes[] = 'u-text-align-center';
		}

		return $classes;
	}

	protected function text_color( array $panel_vars ): array {

		$classes = [];

		if ( HeroPanel::FIELD_TEXT_LIGHT === $panel_vars[ HeroPanel::FIELD_TEXT_COLOR ] ) {
			$classes[] = 't-theme--light';
		}

		if ( HeroPanel::FIELD_TEXT_DARK === $panel_vars[ HeroPanel::FIELD_TEXT_COLOR ] ) {
			$classes[] = 't-theme--dark';
		}

		return $classes;
	}

	protected function get_hero_title( $title_attrs, array $panel_vars ): string {
		$options = [
			Text::CLASSES => [],
			Text::TAG     => 'h1',
			Text::ATTRS   => $title_attrs,
			Text::TEXT    => $panel_vars[ HeroPanel::FIELD_TITLE ],
		];

		return $this->factory->get( Text::class, $options )->render();
	}

	protected function get_hero_text( $description_attrs, array $panel_vars ): string {
		$options = [
			Text::ATTRS   => $description_attrs,
			Text::CLASSES => [ 'site-panel--hero__desc' ],
			Text::TEXT    => $panel_vars[ HeroPanel::FIELD_DESCRIPTION ],
		];

		return $this->factory->get( Text::class, $options )->render();
	}

	protected function get_hero_button( array $panel_vars ): string {
		$options = [
			Link::CLASSES => [ 'c-btn' ],
			Link::TARGET  => $panel_vars[ HeroPanel::FIELD_CTA ][ Link::TARGET ],
			Link::URL     => $panel_vars[ HeroPanel::FIELD_CTA ][ Link::URL ],
			Link::CONTENT => $panel_vars[ HeroPanel::FIELD_CTA ][ Link::CONTENT ],
		];

		return $this->factory->get( Link::class, $options )->render();
	}
}
