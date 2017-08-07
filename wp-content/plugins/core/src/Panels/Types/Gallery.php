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
	const FIELD_GALLERY                          = 'gallery';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Gallery', 'tribe' ) );
		$panel->set_description( __( 'An image gallery slider.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'module-gallery.png' ) );

		// Panel Description
		$panel->add_field( new Fields\TextArea( [
			'name'  => self::FIELD_CONTENT,
			'label' => __( 'Description', 'tribe' ),
		] ) );

		// Image Treatment
		$panel->add_settings_field( new Fields\Radio( [
			'label'   => __( 'Image Treatment', 'tribe' ),
			'name'    => self::FIELD_IMAGE_TREATMENT,
			'options' => [
				self::FIELD_IMAGE_TREATMENT_OPTION_CROP      => __( 'Crop', 'tribe' ),
				self::FIELD_IMAGE_TREATMENT_OPTION_LETTERBOX => __( 'Letterbox', 'tribe' ),
			],
			'default' => self::FIELD_IMAGE_TREATMENT_OPTION_CROP,
		] ) );

		// ImageGallery
		$panel->add_field( new Fields\ImageGallery( [
			'label' => __( 'Image Gallery', 'tribe' ),
			'name'  => self::FIELD_GALLERY,
		] ) );

		return $panel;

	}
}
