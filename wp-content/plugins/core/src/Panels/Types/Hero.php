<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class Hero extends Panel_Type_Config {

	const NAME = 'hero';

	const FIELD_LAYOUT                            = 'layout';
	const FIELD_LAYOUT_OPTION_CONTENT_CENTER      = 'content-center';
	const FIELD_LAYOUT_OPTION_CONTENT_LEFT        = 'content-left';
	const FIELD_LAYOUT_OPTION_CONTENT_SPLIT_RIGHT = 'content-split-right';
	const FIELD_LAYOUT_OPTION_CONTENT_SPLIT_LEFT  = 'content-split-left';
	const FIELD_TITLE                             = 'title';
	const FIELD_DESCRIPTION                       = 'description';
	const FIELD_IMAGE                             = 'image';
	const FIELD_CTA                               = 'cta';
	const FIELD_TEXT_COLOR                        = 'text_color';
	const FIELD_TEXT_LIGHT                        = 't-content--light';
	const FIELD_TEXT_DARK                         = 't-content--dark';
	const FIELD_BG_COLOR                          = 'bg_color';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Hero', 'tribe' ) );
		$panel->set_description( __( 'Hero-style with content overlay with a background image and 4 layout options.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'hero.svg' ) );

		// Panel Layout.
		$panel->add_settings_field( new Fields\ImageSelect( [
			'name'    => self::FIELD_LAYOUT,
			'label'   => __( 'Layout', 'tribe' ),
			'options' => [
				self::FIELD_LAYOUT_OPTION_CONTENT_LEFT        => $this->handler->layout_icon_url( 'card-left.svg' ),
				self::FIELD_LAYOUT_OPTION_CONTENT_CENTER      => $this->handler->layout_icon_url( 'card-center.svg' ),
				self::FIELD_LAYOUT_OPTION_CONTENT_SPLIT_LEFT  => $this->handler->layout_icon_url( 'imagetext-left.svg' ),
				self::FIELD_LAYOUT_OPTION_CONTENT_SPLIT_RIGHT => $this->handler->layout_icon_url( 'imagetext-right.svg' ),
			],
			'default' => self::FIELD_LAYOUT_OPTION_CONTENT_LEFT,
		] ) );

		$panel->add_settings_field( new Fields\Radio( [
			'name'    => self::FIELD_TEXT_COLOR,
			'label'   => __( 'Text Color', 'tribe' ),
			'options' => [
				self::FIELD_TEXT_LIGHT => __( 'Light', 'tribe' ),
				self::FIELD_TEXT_DARK  => __( 'Dark', 'tribe' ),
			],
			'default' => self::FIELD_TEXT_DARK,
		] ) );

		$panel->add_settings_field( new Fields\Radio( [
			'name'    => self::FIELD_BG_COLOR,
			'label'   => __( 'Background Color', 'tribe' ),
			'options' => [
				'u-bc-mine-shaft' => __( 'Black', 'tribe' ),
				'u-bc-white'      => __( 'White', 'tribe' ),
			],
			'default' => 'u-bc-mine-shaft',
		] ) );

		$panel->add_field( new Fields\TextArea( [
			'name'     => self::FIELD_DESCRIPTION,
			'label'    => __( 'Description', 'tribe' ),
			'richtext' => true,
		] ) );

		$panel->add_field( new Fields\Image( [
			'name'        => self::FIELD_IMAGE,
			'label'       => __( 'Background Image', 'tribe' ),
			'description' => __( 'Optimal image sizes: 1600 x 900 for Left/Center Aligned layouts; 900 x 900 for Split Left/Right layouts.', 'tribe' ),
			'size'        => 'medium', // the size displayed in the admin.
		] ) );

		$panel->add_field( new Fields\Link( [
			'name'  => self::FIELD_CTA,
			'label' => __( 'Call To Action Link', 'tribe' ),
		] ) );

		return $panel;

	}
}