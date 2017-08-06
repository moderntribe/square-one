<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class ImageText extends Panel_Type_Config {

	const NAME = 'imagetext';

	const FIELD_LAYOUT                    = 'layout';
	const FIELD_LAYOUT_OPTION_IMAGE_RIGHT = 'image-right';
	const FIELD_LAYOUT_OPTION_IMAGE_LEFT  = 'image-left';
	const FIELD_TITLE                     = 'title';
	const FIELD_CONTENT                   = 'content';
	const FIELD_IMAGE                     = 'image';
	const FIELD_CTA                       = 'cta';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Image + Text', 'tribe' ) );
		$panel->set_description( __( 'An image and text with 2 layout options.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'module-image-text.jpg' ) );

		// Panel Layout.
		$panel->add_settings_field( new Fields\ImageSelect( [
			'name'    => self::FIELD_LAYOUT,
			'label'   => __( 'Layout', 'tribe' ),
			'options' => [
				self::FIELD_LAYOUT_OPTION_IMAGE_LEFT  => $this->handler->layout_icon_url( 'module-imagetext-left.png' ),
				self::FIELD_LAYOUT_OPTION_IMAGE_RIGHT => $this->handler->layout_icon_url( 'module-imagetext-right.png' ),
			],
			'default' => self::FIELD_LAYOUT_OPTION_IMAGE_LEFT,
		] ) );

		// Content.
		$panel->add_field( new Fields\TextArea( [
			'name'     => self::FIELD_CONTENT,
			'label'    => __( 'Content', 'tribe' ),
			'richtext' => true,
		] ) );

		// Image.
		$panel->add_field( new Fields\Image( [
			'name'        => self::FIELD_IMAGE,
			'label'       => __( 'Image', 'tribe' ),
			'description' => __( 'Optimal image sizes: 1500 x 1125 for Left/Right Aligned layouts; 1500 x 844 for Boxed/Hero layouts.', 'tribe' ),
			'size'        => 'medium', // the size displayed in the admin.
		] ) );

		// CTA Link.
		$panel->add_field( new Fields\Link( [
			'name'  => self::FIELD_CTA,
			'label' => __( 'Call To Action Link', 'tribe' ),
		] ) );

		return $panel;

	}
}