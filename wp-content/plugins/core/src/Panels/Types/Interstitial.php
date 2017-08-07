<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class Interstitial extends Panel_Type_Config {

	const NAME = 'interstitial';

	const FIELD_LAYOUT                       = 'layout';
	const FIELD_LAYOUT_OPTION_CONTENT_RIGHT  = 'content-right';
	const FIELD_LAYOUT_OPTION_CONTENT_CENTER = 'content-center';
	const FIELD_LAYOUT_OPTION_CONTENT_LEFT   = 'content-left';
	const FIELD_OPACITY                      = 'opacity';
	const FIELD_TITLE                        = 'title';
	const FIELD_DESCRIPTION                  = 'description';
	const FIELD_IMAGE                        = 'image';
	const FIELD_CTA                          = 'cta';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Interstitial', 'tribe' ) );
		$panel->set_description( __( 'Content overlay with a background image and 3 layout options.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'module-interstitial.jpg' ) );

		// Panel Layout.
		$panel->add_settings_field( new Fields\ImageSelect( [
			'name'    => self::FIELD_LAYOUT,
			'label'   => __( 'Layout', 'tribe' ),
			'options' => [
				self::FIELD_LAYOUT_OPTION_CONTENT_LEFT   => $this->handler->layout_icon_url( 'module-imagetext-left.png' ),
				self::FIELD_LAYOUT_OPTION_CONTENT_CENTER => $this->handler->layout_icon_url( 'module-imagetext-boxed.png' ),
				self::FIELD_LAYOUT_OPTION_CONTENT_RIGHT  => $this->handler->layout_icon_url( 'module-imagetext-right.png' ),
			],
			'default' => self::FIELD_LAYOUT_OPTION_CONTENT_CENTER,
		] ) );

		$panel->add_settings_field( new Fields\Text( [
			'name'    => self::FIELD_OPACITY,
			'label'   => __( 'Opacity Level', 'tribe' ),
			'description' => __( 'Change the level of the opacity overlay', 'tribe' ),
		] ) );

		$panel->add_field( new Fields\TextArea( [
			'name'     => self::FIELD_DESCRIPTION,
			'label'    => __( 'Description', 'tribe' ),
			'richtext' => true,
		] ) );

		$panel->add_field( new Fields\Image( [
			'name'        => self::FIELD_IMAGE,
			'label'       => __( 'Background Image', 'tribe' ),
			'description' => __( 'Optimal image sizes: 1500 x 1125 for Left/Right Aligned layouts; 1500 x 844 for Boxed/Hero layouts.', 'tribe' ),
			'size'        => 'medium', // the size displayed in the admin.
		] ) );

		$panel->add_field( new Fields\Link( [
			'name'  => self::FIELD_CTA,
			'label' => __( 'Call To Action Link', 'tribe' ),
		] ) );

		return $panel;

	}
}