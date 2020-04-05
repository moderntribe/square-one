<?php


namespace Tribe\Project\Templates\Controllers\Panels;

use Tribe\Project\Panels\Types\ImageText as ImageTextPanel;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Panels\Imagetext as Imagetext_Context;
use Tribe\Project\Templates\Components\Text;
use Tribe\Project\Templates\Components\Title;

class Imagetext extends Panel {
	use Traits\Headerless;
	use Traits\Unwrapped;

	protected function render_content( \ModularContent\Panel $panel, array $panel_vars ): string {
		return $this->factory->get( Imagetext_Context::class, [
			Imagetext_Context::WRAPPER_CLASSES => $this->get_wrapper_classes( $panel_vars ),
			Imagetext_Context::IMAGE           => $this->get_panel_image( $panel_vars ),
			Imagetext_Context::CONTENT_BLOCK   => $this->get_content_block( $panel_vars ),
		] )->render();
	}

	protected function get_classes( \ModularContent\Panel $panel ): array {
		$classes   = parent::get_classes( $panel );
		$classes[] = 's-wrapper--no-padding';

		return $classes;
	}

	protected function get_content_block( array $panel_vars ): string {

		$title_attrs       = [];
		$description_attrs = [];

		if ( is_panel_preview() ) {

			$title_attrs = [
				'data-depth'    => 0,
				'data-name'     => ImageTextPanel::FIELD_TITLE,
				'data-livetext' => true,
			];

			$description_attrs = [
				'data-depth'    => 0,
				'data-name'     => ImageTextPanel::FIELD_DESCRIPTION,
				'data-autop'    => 'true',
				'data-livetext' => true,
			];
		}

		$options = [
			Content_Block::TITLE           => $this->get_image_text_title( $panel_vars, $title_attrs ),
			Content_Block::CLASSES         => '',
			Content_Block::BUTTON          => $this->get_image_text_button( $panel_vars ),
			Content_Block::CONTENT_CLASSES => '',
			Content_Block::TEXT            => $this->get_image_text_text( $panel_vars, $description_attrs ),
		];

		return $this->factory->get( Content_Block::class, $options )->render();
	}

	protected function get_image_text_title( array $panel_vars, $title_attrs ): string {
		$options = [
			Title::CLASSES => [ 'h2' ],
			Title::TAG     => 'h2',
			Title::ATTRS   => $title_attrs,
			Title::TITLE   => esc_html( $panel_vars[ ImageTextPanel::FIELD_TITLE ] ),
		];

		return $this->factory->get( Title::class, $options )->render();
	}

	protected function get_image_text_text( array $panel_vars, $description_attrs ): string {
		$options = [
			Text::ATTRS   => $description_attrs,
			Text::CLASSES => '',
			Text::TEXT    => $panel_vars[ ImageTextPanel::FIELD_DESCRIPTION ],
		];

		return $this->factory->get( Text::class, $options )->render();
	}

	protected function get_image_text_button( array $panel_vars ): string {
		$options = [
			Button::TAG         => '',
			Button::URL         => $panel_vars[ ImageTextPanel::FIELD_CTA ][ Button::URL ],
			Button::TYPE        => '',
			Button::TARGET      => $panel_vars[ ImageTextPanel::FIELD_CTA ][ Button::TARGET ],
			Button::CLASSES     => [ 'c-btn c-btn--sm' ],
			Button::ATTRS       => '',
			Button::LABEL       => $panel_vars[ ImageTextPanel::FIELD_CTA ][ Button::LABEL ],
			Button::BTN_AS_LINK => true,
		];

		return $this->factory->get( Button::class, $options )->render();
	}

	protected function get_panel_image( array $panel_vars ): string {

		if ( empty( $panel_vars[ ImageTextPanel::FIELD_IMAGE ] ) ) {
			return '';
		}

		try {
			$image = \Tribe\Project\Templates\Models\Image::factory( $panel_vars[ ImageTextPanel::FIELD_IMAGE ] );
		} catch ( \Exception $e ) {
			return '';
		}

		$options = [
			Image::ATTACHMENT      => $image,
			Image::IMG_CLASSES     => [ 'c-image', 'c-image--rect' ],
			Image::AS_BG           => true,
			Image::USE_LAZYLOAD    => false,
			Image::WRAPPER_CLASSES => [ 'c-image__bg' ],
		];

		return $this->factory->get( Image::class, $options )->render();
	}

	protected function get_wrapper_classes( array $panel_vars ) {

		$classes = [];

		if ( ImageTextPanel::FIELD_LAYOUT_OPTION_IMAGE_RIGHT === $panel_vars[ ImageTextPanel::FIELD_LAYOUT ] ) {
			$classes[] = 'g-row--reorder-2-col';
		}

		return $classes;
	}
}
