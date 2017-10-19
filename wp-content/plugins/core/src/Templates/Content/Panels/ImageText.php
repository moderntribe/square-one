<?php


namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\ImageText as ImageTextPanel;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Text;
use Tribe\Project\Templates\Components\Title;
use Tribe\Project\Templates\Components\Button;

class ImageText extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	protected function get_mapped_panel_data(): array {

		$data = [
			'wrapper_classes' => $this->get_panel_classes(),
			'image'           => $this->get_panel_image(),
			'content_block'   => $this->get_content_block(),
		];

		return $data;
	}

	protected function get_content_block() {

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
			Content_Block::TITLE           => $this->get_image_text_title( $title_attrs ),
			Content_Block::CLASSES         => '',
			Content_Block::BUTTON          => $this->get_image_text_button(),
			Content_Block::CONTENT_CLASSES => [ 't-content' ],
			Content_Block::TEXT            => $this->get_image_text_text( $description_attrs ),
		];

		$content_block_obj = Content_Block::factory( $options );

		return $content_block_obj->render();
	}

	protected function get_image_text_title( $title_attrs ) {
		$options = [
			Title::CLASSES => [ 'h2' ],
			Title::TAG     => 'h2',
			Title::ATTRS   => $title_attrs,
			Title::TITLE   => esc_html( $this->panel_vars[ ImageTextPanel::FIELD_TITLE ] ),
		];

		$title_object = Title::factory( $options );

		return $title_object->render();
	}

	protected function get_image_text_text( $description_attrs ) {
		$options = [
			Text::ATTRS => $description_attrs,
			Text::CLASSES => '',
			Text::TEXT => $this->panel_vars[ ImageTextPanel::FIELD_DESCRIPTION ],
		];

		$text_object = Text::factory( $options );

		return $text_object->render();
	}

	protected function get_image_text_button() {
		$options = [
			Button::TAG         => '',
			Button::URL         => $this->panel_vars[ ImageTextPanel::FIELD_CTA ][Button::URL],
			Button::TYPE        => '',
			Button::TARGET      => $this->panel_vars[ ImageTextPanel::FIELD_CTA ][Button::TARGET],
			Button::ATTRS       => '',
			Button::LABEL       => $this->panel_vars[ ImageTextPanel::FIELD_CTA ][Button::LABEL],
			Button::BTN_AS_LINK => true,
		];

		$button_object = Button::factory( $options );

		return $button_object->render();
	}

	protected function get_panel_image(): string {

		if ( empty( $this->panel_vars[ ImageTextPanel::FIELD_IMAGE ] ) ) {
			return '';
		}

		$options = [
			'img_id'          => $this->panel_vars[ ImageTextPanel::FIELD_IMAGE ],
			'component_class' => 'c-image c-image--rect',
			'as_bg'           => true,
			'use_lazyload'    => false,
			'echo'            => false,
			'wrapper_class'   => 'c-image__bg',
		];

		$image_obj = Image::factory( $options );

		return $image_obj->render();
	}

	protected function get_panel_classes() {

		$classes = [];

		if ( ImageTextPanel::FIELD_LAYOUT_OPTION_IMAGE_RIGHT === $this->panel_vars[ ImageTextPanel::FIELD_LAYOUT ] ) {
			$classes[] = 'g-row--reorder-2-col';
		}

		return implode( ' ', $classes );
	}

	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/imagetext'];
	}
}
