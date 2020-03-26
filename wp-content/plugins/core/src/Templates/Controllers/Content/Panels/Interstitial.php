<?php


namespace Tribe\Project\Templates\Controllers\Content\Panels;

use Tribe\Project\Panels\Types\Interstitial as Interstice;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Text;
use Tribe\Project\Templates\Components\Title;

class Interstitial extends Panel {
	protected $path = 'content/panels/interstitial.twig';

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

		if ( empty( $this->panel_vars[ Interstice::FIELD_IMAGE ] ) ) {
			return '';
		}

		try {
			$image = \Tribe\Project\Templates\Models\Image::factory( $this->panel_vars[ Interstice::FIELD_IMAGE ] );
		} catch ( \Exception $e ) {
			return '';
		}

		$options = [
			Image::ATTACHMENT      => $image,
			Image::COMPONENT_CLASS => 'c-image',
			Image::AS_BG           => true,
			Image::USE_LAZYLOAD    => false,
			Image::WRAPPER_CLASS   => 'c-image__bg',
		];

		return $this->factory->get( Image::class, $options )->render();
	}

	protected function get_content_block() {

		$title_attrs       = [];
		$description_attrs = [];

		if ( is_panel_preview() ) {

			$title_attrs = [
				'data-depth'    => $this->panel->get_depth(),
				'data-name'     => esc_attr( Interstice::FIELD_TITLE ),
				'data-livetext' => true,
			];

			$description_attrs = [
				'data-depth'    => $this->panel->get_depth(),
				'data-name'     => esc_attr( Interstice::FIELD_DESCRIPTION ),
				'data-autop'    => 'true',
				'data-livetext' => true,
			];
		}

		$options = [
			Content_Block::TITLE           => $this->get_interstitial_title( $title_attrs ),
			Content_Block::CLASSES         => '',
			Content_Block::BUTTON          => $this->get_interstitial_button(),
			Content_Block::CONTENT_CLASSES => '',
			Content_Block::TEXT            => $this->get_interstitial_text( $description_attrs ),
		];

		return $this->factory->get( Content_Block::class, $options )->render();
	}

	protected function get_interstitial_title( $title_attrs ) {
		$options = [
			Title::CLASSES => '',
			Title::ATTRS   => $title_attrs,
			Title::TAG     => 'h2',
			Title::TITLE   => $this->panel_vars[ Interstice::FIELD_TITLE ],
		];

		return $this->factory->get( Title::class, $options )->render();
	}

	protected function get_interstitial_text( $description_attrs ) {
		$options = [
			Text::ATTRS   => $description_attrs,
			Text::CLASSES => '',
			Text::TEXT    => $this->panel_vars[ Interstice::FIELD_DESCRIPTION ],
		];

		return $this->factory->get( Text::class, $options )->render();
	}

	protected function get_interstitial_button() {
		$options = [
			Button::TAG         => '',
			Button::URL         => $this->panel_vars[ Interstice::FIELD_CTA ][ Button::URL ],
			Button::TYPE        => '',
			Button::TARGET      => $this->panel_vars[ Interstice::FIELD_CTA ][ Button::TARGET ],
			Button::CLASSES     => [ 'c-btn' ],
			Button::ATTRS       => '',
			Button::LABEL       => $this->panel_vars[ Interstice::FIELD_CTA ][ Button::LABEL ],
			Button::BTN_AS_LINK => true,
		];

		if ( empty( $this->panel_vars[ Interstice::FIELD_CTA ][ Button::URL ] ) ) {
			return false;
		}

		return $this->factory->get( Button::class, $options )->render();
	}

	protected function get_layout() {

		$classes = [];

		if ( Interstice::FIELD_LAYOUT_OPTION_CONTENT_RIGHT === $this->panel_vars[ Interstice::FIELD_LAYOUT ] ) {
			$classes[] = 'g-row--pull-right';
		}

		if ( Interstice::FIELD_LAYOUT_OPTION_CONTENT_CENTER === $this->panel_vars[ Interstice::FIELD_LAYOUT ] ) {
			$classes[] = 'g-row--center u-text-align-center';
		}

		return implode( ' ', $classes );
	}

	protected function text_color() {

		$classes = [];

		if ( Interstice::FIELD_TEXT_LIGHT === $this->panel_vars[ Interstice::FIELD_TEXT_COLOR ] ) {
			$classes[] = 't-content--light';
		}

		if ( Interstice::FIELD_TEXT_DARK === $this->panel_vars[ Interstice::FIELD_TEXT_COLOR ] ) {
			$classes[] = 't-content--dark';
		}

		return implode( ' ', $classes );
	}
}
