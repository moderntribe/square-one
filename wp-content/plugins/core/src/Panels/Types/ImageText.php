<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class ImageText extends Panel_Type_Config {

	const NAME = 'imagetext';

	const FIELD_LAYOUT                    = 'layout';
	const FIELD_LAYOUT_OPTION_IMAGE_RIGHT = 'image-right';
	const FIELD_LAYOUT_OPTION_IMAGE_LEFT  = 'image-left';
	const FIELD_LAYOUT_OPTION_BOXED       = 'boxed';
	const FIELD_LAYOUT_OPTION_HERO        = 'hero';
	const FIELD_CONTENT                   = 'content';
	const FIELD_IMAGE                     = 'image';
	const FIELD_OVERLAY                   = 'overlay';
	const FIELD_OVERLAY_OPTION_NONE       = 'none';
	const FIELD_OVERLAY_OPTION_TINT       = 'tint';
	const FIELD_OVERLAY_OPTION_PRIMARY    = 'primary';
	const FIELD_OVERLAY_OPTION_SECONDARY  = 'secondary';
	const FIELD_CTA                       = 'cta';
	const FIELD_CTA_STYLE                 = 'cta_style';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Image+Text', 'tribe' ) );
		$panel->set_description( __( 'An image and text with several layout options.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'module-imagetext.png' ) );

		// Panel Layout
		$panel->add_settings_field( new Fields\ImageSelect( [
			'name'    => self::FIELD_LAYOUT,
			'label'   => __( 'Layout', 'tribe' ),
			'options' => [
				self::FIELD_LAYOUT_OPTION_IMAGE_RIGHT => $this->handler->layout_icon_url( 'module-imagetext-right.png' ),
				self::FIELD_LAYOUT_OPTION_IMAGE_LEFT  => $this->handler->layout_icon_url( 'module-imagetext-left.png' ),
				self::FIELD_LAYOUT_OPTION_BOXED       => $this->handler->layout_icon_url( 'module-imagetext-boxed.png' ),
				self::FIELD_LAYOUT_OPTION_HERO        => $this->handler->layout_icon_url( 'module-imagetext-hero.png' ),
			],
			'default' => self::FIELD_LAYOUT_OPTION_IMAGE_RIGHT,
		] ) );

		// Content
		$panel->add_field( new Fields\TextArea( [
			'name'     => self::FIELD_CONTENT,
			'label'    => __( 'Description', 'tribe' ),
			'richtext' => true
		] ) );

		// Image
		$panel->add_field( new Fields\Image( [
			'name'        => self::FIELD_IMAGE,
			'label'       => __( 'Image', 'tribe' ),
			'description' => __( 'Optimal image sizes: 1500 x 1125 for Left/Right Aligned layouts; 1500 x 844 for Boxed/Hero layouts.', 'tribe' ),
			'size'        => 'medium', // the size displayed in the admin
		] ) );

		// Image Overlay
		$panel->add_settings_field( new Fields\ImageSelect( [
			'name'        => 'overlay',
			'label'       => __( 'Image Overlay', 'tribe' ),
			'description' => __( 'Apply a color over the image to improve text visibility. Only applies to Boxed/Hero layouts.', 'tribe' ),
			'options'     => [
				self::FIELD_OVERLAY_OPTION_NONE      => $this->handler->swatch_icon_url( 'module-imagetext-none.png' ),
				self::FIELD_OVERLAY_OPTION_TINT      => $this->handler->swatch_icon_url( 'module-imagetext-tint.png' ),
				self::FIELD_OVERLAY_OPTION_PRIMARY   => $this->handler->swatch_icon_url( 'module-imagetext-blue.png' ),
				self::FIELD_OVERLAY_OPTION_SECONDARY => $this->handler->swatch_icon_url( 'module-imagetext-orange.png' ),
			],
			'default'     => self::FIELD_OVERLAY_OPTION_NONE,
		] ) );

		// CTA Link
		$panel->add_field( new Fields\Link( [
			'name'  => self::FIELD_CTA,
			'label' => __( 'Call To Action Link', 'tribe' ),
		] ) );

		// CTA Link Style
		$panel->add_settings_field( new Fields\ImageSelect( [
			'name'    => self::FIELD_CTA_STYLE,
			'label'   => __( 'Call To Action Link Style', 'tribe' ),
			'options' => [
				'text'   => $this->handler->layout_icon_url( 'link-style-text.png' ),
				'button' => $this->handler->layout_icon_url( 'link-style-button.png' ),
			],
			'default' => 'text',
		] ) );

		return $panel;

	}
}