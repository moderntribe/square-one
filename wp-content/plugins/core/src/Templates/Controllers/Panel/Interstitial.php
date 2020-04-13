<?php


namespace Tribe\Project\Templates\Controllers\Panel;

use Exception;
use Tribe\Project\Panels\Types\Interstitial as Interstice;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Link;
use Tribe\Project\Templates\Components\Panels\Interstitial as Interstitial_Context;
use Tribe\Project\Templates\Components\Text;

class Interstitial extends Panel {
	use Traits\Headerless;
	use Traits\Unwrapped;

	protected function render_content( \ModularContent\Panel $panel, array $panel_vars ): string {
		return $this->factory->get( Interstitial_Context::class, [
			Interstitial_Context::COLOR         => $this->text_color( $panel_vars ),
			Interstitial_Context::LAYOUT        => $this->get_layout( $panel_vars ),
			Interstitial_Context::IMAGE         => $this->get_image( $panel_vars ),
			Interstitial_Context::CONTENT_BLOCK => $this->get_content_block( $panel, $panel_vars ),
		] )->render();
	}

	protected function get_image( array $panel_vars ) {

		if ( empty( $panel_vars[ Interstice::FIELD_IMAGE ] ) ) {
			return '';
		}

		try {
			$image = \Tribe\Project\Templates\Models\Image::factory( $panel_vars[ Interstice::FIELD_IMAGE ] );
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
				'data-name'     => esc_attr( Interstice::FIELD_TITLE ),
				'data-livetext' => true,
			];

			$description_attrs = [
				'data-depth'    => $depth,
				'data-name'     => esc_attr( Interstice::FIELD_DESCRIPTION ),
				'data-autop'    => 'true',
				'data-livetext' => true,
			];
		}

		$options = [
			Content_Block::TITLE           => $this->get_interstitial_title( $title_attrs, $panel_vars ),
			Content_Block::CLASSES         => '',
			Content_Block::BUTTON          => $this->get_interstitial_button( $panel_vars ),
			Content_Block::CONTENT_CLASSES => '',
			Content_Block::TEXT            => $this->get_interstitial_text( $description_attrs, $panel_vars ),
		];

		return $this->factory->get( Content_Block::class, $options )->render();
	}

	protected function get_interstitial_title( $title_attrs, array $panel_vars ) {
		$options = [
			Text::CLASSES => '',
			Text::ATTRS   => $title_attrs,
			Text::TAG     => 'h2',
			Text::TEXT    => $panel_vars[ Interstice::FIELD_TITLE ],
		];

		return $this->factory->get( Text::class, $options )->render();
	}

	protected function get_interstitial_text( $description_attrs, array $panel_vars ) {
		$options = [
			Text::ATTRS   => $description_attrs,
			Text::CLASSES => '',
			Text::TEXT    => $panel_vars[ Interstice::FIELD_DESCRIPTION ],
		];

		return $this->factory->get( Text::class, $options )->render();
	}

	protected function get_interstitial_button( array $panel_vars ): string {
		if ( empty( $panel_vars[ Interstice::FIELD_CTA ][ Link::URL ] ) ) {
			return '';
		}

		$options = [
			Link::CLASSES => [ 'c-btn' ],
			Link::URL     => $panel_vars[ Interstice::FIELD_CTA ][ Link::URL ],
			Link::TARGET  => $panel_vars[ Interstice::FIELD_CTA ][ Link::TARGET ],
			Link::TEXT    => $panel_vars[ Interstice::FIELD_CTA ][ Link::TEXT ],
		];

		return $this->factory->get( Link::class, $options )->render();
	}

	protected function get_layout( array $panel_vars ): array {

		$classes = [];

		if ( Interstice::FIELD_LAYOUT_OPTION_CONTENT_RIGHT === $panel_vars[ Interstice::FIELD_LAYOUT ] ) {
			$classes[] = 'g-row--pull-right';
		}

		if ( Interstice::FIELD_LAYOUT_OPTION_CONTENT_CENTER === $panel_vars[ Interstice::FIELD_LAYOUT ] ) {
			$classes[] = 'g-row--center u-text-align-center';
		}

		return $classes;
	}

	protected function text_color( array $panel_vars ): array {

		$classes = [];

		if ( Interstice::FIELD_TEXT_LIGHT === $panel_vars[ Interstice::FIELD_TEXT_COLOR ] ) {
			$classes[] = 't-content--light';
		}

		if ( Interstice::FIELD_TEXT_DARK === $panel_vars[ Interstice::FIELD_TEXT_COLOR ] ) {
			$classes[] = 't-content--dark';
		}

		return $classes;
	}
}
