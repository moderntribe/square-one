<?php

namespace Tribe\Project\Panels\Types;
use ModularContent\Fields;

class Gallery extends Panel_Type_Config {
	protected function panel() {

		$panel = $this->handler->factory( 'gallery' );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Gallery', 'tribe' ) );
		$panel->set_description( __( 'An image gallery slider.', 'tribe' ) );
		$panel->set_icon( $this->handler->inactive_icon_url( 'module-gallery.png' ), 'inactive' );
		$panel->set_icon( $this->handler->active_icon_url( 'module-gallery.png' ), 'active' );

		// Panel Description
		$panel->add_field( new Fields\TextArea( array(
			'name'      => 'content',
			'label'     => __( 'Description', 'tribe' ),
		) ) );

		// Image Treatment
		/*
		$panel->add_field( new Fields\Radio( array(
			'label'   => __( 'Image Treatment', 'tribe' ),
			'name'    => 'image_treatment',
			'options' => array(
				'crop'      => __( 'Crop', 'tribe' ),
				'letterbox' => __( 'Letterbox', 'tribe' ),
			),
			'default' => 'crop',
		) ) );
		*/

		// ImageGallery
		$panel->add_field( new Fields\ImageGallery( array(
			'label' => __( 'Image Gallery', 'tribe' ),
			'name'  => 'gallery',
		) ) );

		return $panel;

	}
}