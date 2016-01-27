<?php

namespace Tribe\Project\Panels\Types;
use ModularContent\Fields;

class ImageText extends Panel_Type_Config {
	protected function panel() {

		$panel = $this->handler->factory( 'imagetext' );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Image+Text', 'tribe' ) );
		$panel->set_description( __( 'An image and text with several layout options.', 'tribe' ) );
		$panel->set_icon( $this->handler->inactive_icon_url( 'module-imagetext.png' ), 'inactive' );
		$panel->set_icon( $this->handler->active_icon_url( 'module-imagetext.png' ), 'active' );

		// Panel Layout
		$panel->add_field( $this->handler->field( 'ImageSelect', array(
			'name'      => 'layout',
			'label'     => __( 'Layout', 'tribe' ),
			'options'   => array(
				'image-right'   => $this->handler->layout_icon_url( 'module-imagetext-right.png' ),
				'image-left'    => $this->handler->layout_icon_url( 'module-imagetext-left.png' ),
				'boxed'         => $this->handler->layout_icon_url( 'module-imagetext-boxed.png' ),
				'hero'          => $this->handler->layout_icon_url( 'module-imagetext-hero.png' ),
			),
			'default' => 'image-right',
		) ) );

		// Content
		$panel->add_field( $this->handler->field( 'TextArea', array(
			'name'      => 'content',
			'label'     => __( 'Description', 'tribe' ),
			'richtext'  => true
		) ) );

		// Image
		$panel->add_field( $this->handler->field( 'Image', array(
			'name'          => 'image',
			'label'         => __( 'Image', 'tribe' ),
			'description'   => __( 'Optimal image sizes: 1500 x 1125 for Left/Right Aligned layouts; 1500 x 844 for Boxed/Hero layouts.', 'tribe' ),
			'size'          => 'medium', // the size displayed in the admin
		) ) );

		// Image Overlay
		$panel->add_field( $this->handler->field( 'ImageSelect', array(
			'name'          => 'overlay',
			'label'         => __( 'Image Overlay', 'tribe' ),
			'description'   => __( 'Apply a color over the image to improve text visibility. Only applies to Boxed/Hero layouts.', 'tribe' ),
			'options'       => array(
				'none'          => $this->handler->swatch_icon_url( 'module-imagetext-none.png' ),
				'tint'          => $this->handler->swatch_icon_url( 'module-imagetext-tint.png' ),
				'primary'       => $this->handler->swatch_icon_url( 'module-imagetext-blue.png' ),
				'secondary'     => $this->handler->swatch_icon_url( 'module-imagetext-orange.png' ),
			),
			'default' => 'none',
		) ) );

		// CTA Link
		$panel->add_field( $this->handler->field( 'Link', array(
			'name'      => 'cta',
			'label'     => __( 'Call To Action Link', 'tribe' ),
		) ) );

		// CTA Link Style
		$panel->add_field( $this->handler->field( 'ImageSelect', array(
			'name'      => 'cta_style',
			'label'     => __( 'Call To Action Link Style', 'tribe' ),
			'options'   => array(
				'text'      => $this->handler->layout_icon_url( 'link-style-text.png' ),
				'button'    => $this->handler->layout_icon_url( 'link-style-button.png' ),
		    ),
			'default' => 'text',
		) ) );

		return $panel;

	}
}