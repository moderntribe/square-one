<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class Gallery extends Panel_Type_Config {

	const NAME = 'gallery';

	const FIELD_TITLE                            = 'title';
	const FIELD_CONTENT                          = 'content';
	const FIELD_IMAGE_TREATMENT                  = 'image_treatment';
	const FIELD_IMAGE_TREATMENT_OPTION_CROP      = 'crop';
	const FIELD_IMAGE_TREATMENT_OPTION_LETTERBOX = 'letterbox';
	const FIELD_CAROUSEL                         = 'carousel';
	const FIELD_CAROUSEL_SHOW                    = 'carousel_show';
	const FIELD_CAROUSEL_HIDE                    = 'carousel_hide';
	const FIELD_GALLERY                          = 'gallery';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Gallery', 'tribe' ) );
		$panel->set_description( __( 'An image gallery slider.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'gallery.svg' ) );

		// Panel Description
		$panel->add_field( new Fields\TextArea( [
			'name'     => self::FIELD_CONTENT,
			'label'    => __( 'Description', 'tribe' ),
			'richtext' => true,
		] ) );

		// Image Treatment
		$panel->add_settings_field( new Fields\ImageSelect( [
			'label'   => __( 'Image Treatment', 'tribe' ),
			'name'    => self::FIELD_IMAGE_TREATMENT,
			'options' => [
				self::FIELD_IMAGE_TREATMENT_OPTION_CROP      => $this->handler->layout_icon_url( 'gallery-crop.svg' ),
				self::FIELD_IMAGE_TREATMENT_OPTION_LETTERBOX => $this->handler->layout_icon_url( 'gallery-no-crop.svg' ),
			],
			'default' => self::FIELD_IMAGE_TREATMENT_OPTION_LETTERBOX,
		] ) );

		// Carousel
		$panel->add_settings_field( new Fields\ImageSelect( [
			'label'   => __( 'Show Carousel', 'tribe' ),
			'name'    => self::FIELD_CAROUSEL,
			'options' => [
				self::FIELD_CAROUSEL_SHOW => $this->handler->layout_icon_url( 'gallery-carousel.svg' ),
				self::FIELD_CAROUSEL_HIDE => $this->handler->layout_icon_url( 'gallery-no-carousel.svg' ),
			],
			'default' => self::FIELD_CAROUSEL_SHOW,
		] ) );

		// ImageGallery
		$panel->add_field( new Fields\ImageGallery( [
			'label' => __( 'Image Gallery', 'tribe' ),
			'name'  => self::FIELD_GALLERY,
		] ) );

		return $panel;

	}
}
