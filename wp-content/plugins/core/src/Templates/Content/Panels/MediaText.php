<?php


namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\MediaText as MediaTextPanel;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Text;
use Tribe\Project\Templates\Components\Title;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Video;
use Tribe\Project\Theme\Oembed_Filter;
use Tribe\Project\Theme\Util;

class MediaText extends Panel {

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
	protected function get_mapped_panel_data(): array {
		$data = [
			'content_block'  => $this->get_media_text_content_block(),
			'media'          => $this->get_media_text_media(),
			'media_position' => $this->get_media_text_image_position(),
			'layout'         => $this->get_media_text_layout(),
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
		$no_padding = '';
		$wrapper_class = $this->get_media_text_wrapper_class();

		if ( MediaTextPanel::OPTION_LAYOUT_FULL_BLEED === $this->panel_vars[ MediaTextPanel::FIELD_LAYOUT ] ) {
			$no_padding = 's-wrapper--no-padding';
		}

		$classes = [
			'panel',
			's-wrapper',
			'site-panel',
			$no_padding,
			sprintf( 'site-panel--%s', $this->panel->get_type_object()->get_id() ),
			$wrapper_class,
		];

		return Util::class_attribute( $classes );
	}

	/**
	 * Get the Content (Title, Description, and Button) using the Content Block component.
	 *
	 * @return string
	 */
	protected function get_media_text_content_block() {
		$title_attrs       = [];
		$description_attrs = [];

		if ( is_panel_preview() ) {

			$title_attrs = [
				'data-depth'    => 0,
				'data-name'     => esc_attr( MediaTextPanel::FIELD_TITLE ),
				'data-livetext' => true,
			];

			$description_attrs = [
				'data-depth'    => 0,
				'data-name'     => esc_attr( MediaTextPanel::FIELD_DESCRIPTION ),
				'data-autop'    => 'true',
				'data-livetext' => true,
			];
		}

		$options = [
			Content_Block::TITLE  => $this->get_media_text_title( $title_attrs ),
			Content_Block::BUTTON => $this->get_media_text_button(),
			Content_Block::TEXT   => $this->get_media_text_description( $description_attrs ),
		];

		$content_block_obj = Content_Block::factory( $options );

		return $content_block_obj->render();
	}

	/**
	 * Get the Media + Text Title using the Title component.
	 *
	 * @param $title_attrs
	 * @return string
	 */
	protected function get_media_text_title( $title_attrs ) {
		$options = [
			Title::CLASSES => [ 'h2' ],
			Title::TAG     => 'h2',
			Title::ATTRS   => $title_attrs,
			Title::TITLE   => esc_html( $this->panel_vars[ MediaTextPanel::FIELD_TITLE ] ),
		];

		$title_object = Title::factory( $options );

		return $title_object->render();
	}

	/**
	 * Get the Media + Text Description using the Text component.
	 *
	 * @param $description_attrs
	 * @return string
	 */
	protected function get_media_text_description( $description_attrs ) {
		$options = [
			Text::ATTRS => $description_attrs,
			Text::TEXT  => $this->panel_vars[ MediaTextPanel::FIELD_DESCRIPTION ],
		];

		$text_object = Text::factory( $options );

		return $text_object->render();
	}

	/**
	 * Get the Media + Text Button using the Button component.
	 *
	 * @return string
	 */
	protected function get_media_text_button() {
		$options = [
			Button::URL         => $this->panel_vars[ MediaTextPanel::FIELD_CTA ][ Button::URL ],
			Button::TARGET      => $this->panel_vars[ MediaTextPanel::FIELD_CTA ][ Button::TARGET ],
			Button::CLASSES     => [ 'c-btn' ],
			Button::LABEL       => $this->panel_vars[ MediaTextPanel::FIELD_CTA ][ Button::LABEL ],
			Button::BTN_AS_LINK => true,
		];

		$button_object = Button::factory( $options );

		return $button_object->render();
	}

	/**
	 * Get the Media + Text Image using the Image component.
	 *
	 * @return string
	 */
	protected function get_media_text_image(): string {
		if ( empty( $this->panel_vars[ MediaTextPanel::FIELD_IMAGE ] ) ) {
			return '';
		}

		$options = [
			Image::IMG_ID          => $this->panel_vars[ MediaTextPanel::FIELD_IMAGE ],
			Image::COMPONENT_CLASS => 'c-image c-image--rect',
			Image::AS_BG           => true,
			Image::USE_LAZYLOAD    => false,
			Image::ECHO            => false,
			Image::WRAPPER_CLASS   => 'c-image__bg',
		];

		$image_obj = Image::factory( $options );

		return $image_obj->render();
	}

	/**
	 * Get the Media + Text video using the Video component.
	 *
	 * @return string
	 */
	protected function get_media_text_video(): string {
		if ( empty( $this->panel_vars[ MediaTextPanel::FIELD_VIDEO ] ) ) {
			return '';
		}

		global $wp_embed;

		$url      = $this->panel_vars[ MediaTextPanel::FIELD_VIDEO ];
		$rendered = $wp_embed->shortcode( [], $url );

		return $rendered;
	}

	/**
	 * Get Media Type.
	 * @return string
	 */
	protected function get_media_text_media(): string {
		$media = $this->get_media_text_image();

		if ( MediaTextPanel::OPTION_MEDIA_TYPE_VIDEO === $this->panel_vars[ MediaTextPanel::FIELD_MEDIA_TYPE ] ) {
			$media = $this->get_media_text_video();
		}

		return $media;
	}

	/**
	 * Get Media + Text Image Position.
	 *
	 * @return string
	 */
	protected function get_media_text_image_position() {
		$classes = [];

		if ( MediaTextPanel::OPTION_LAYOUT_IMAGE_RIGHT === $this->panel_vars[ MediaTextPanel::FIELD_MEDIA_POSITION ] ) {
			$classes[] = 'g-row--reorder-2-col';
		}

		return Util::class_attribute( $classes, false );
	}

	/**
	 * Get Media + Text Layout.
	 *
	 * @return string
	 */
	protected function get_media_text_layout() {
		$classes = [];

		if ( MediaTextPanel::OPTION_LAYOUT_FULL_BLEED === $this->panel_vars[ MediaTextPanel::FIELD_LAYOUT ] ) {
			$classes[] = 'l-container--full';
		}

		return Util::class_attribute( $classes, false );
	}

	/**
	 * Get Media + Text Media Spacing.
	 *
	 * @return string
	 */
	protected function get_media_text_wrapper_class(): string {
		$classes = 'site-panel--mediatext__image-position-left';

		if ( MediaTextPanel::OPTION_LAYOUT_IMAGE_RIGHT === $this->panel_vars[ MediaTextPanel::FIELD_MEDIA_POSITION ] ) {
			$classes = 'site-panel--mediatext__image-position-right';
		}

		return $classes;
	}

	/**
	 * Return instance.
	 * @return mixed
	 */
	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/mediatext'];
	}
}
