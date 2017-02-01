<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class ContentGrid extends Panel_Type_Config {

	const NAME = 'contentgrid';

	const FIELD_LAYOUT                         = 'layout';
	const FIELD_LAYOUT_OPTION_STANDARD         = 'standard';
	const FIELD_LAYOUT_OPTION_CARDS            = 'cards';
	const FIELD_LAYOUT_OPTION_FULL             = 'full';
	const FIELD_CONTENT                        = 'content';
	const FIELD_COLUMNS                        = 'columns';
	const FIELD_COLUMN_TITLE                   = 'column_title';
	const FIELD_COLUMN_TEXT                    = 'column_text';
	const FIELD_COLUMN_CTA                     = 'column_cta';
	const FIELD_COLUMN_CTA_STYLE               = 'column_cta_style';
	const FIELD_COLUMN_CTA_STYLE_OPTION_TEXT   = 'text';
	const FIELD_COLUMN_CTA_STYLE_OPTION_BUTTON = 'button';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Content Grid', 'tribe' ) );
		$panel->set_description( __( 'A grid of content with 2 layouts.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'module-contentgrid.png' ) );

		// Panel Style
		$panel->add_settings_field( $this->handler->field( 'ImageSelect', [
			'name'    => self::FIELD_LAYOUT,
			'label'   => __( 'Style', 'tribe' ),
			'options' => [
				self::FIELD_LAYOUT_OPTION_STANDARD => $this->handler->layout_icon_url( 'module-contentgrid-standard.png' ),
				self::FIELD_LAYOUT_OPTION_CARDS    => $this->handler->layout_icon_url( 'module-contentgrid-cards.png' ),
				self::FIELD_LAYOUT_OPTION_FULL     => $this->handler->layout_icon_url( 'module-contentgrid-full.png' ),
			],
			'default' => self::FIELD_LAYOUT_OPTION_STANDARD,
		] ) );

		// Panel Description
		$panel->add_field( $this->handler->field( 'TextArea', [
			'name'  => self::FIELD_CONTENT,
			'label' => __( 'Description', 'tribe' ),
		] ) );

		// Grid Columns
		/** @var Fields\Group $columns */
		$columns = $this->handler->field( 'Repeater', [
			'label'            => __( 'Content Blocks', 'tribe' ),
			'name'             => self::FIELD_COLUMNS,
			'min'              => 2,
			'max'              => 4,
			'new_button_label' => __( 'Add Content Block', 'tribe' )
		] );

		// Column Title
		$columns->add_field( $this->handler->field( 'Text', [
			'name'  => self::FIELD_COLUMN_TITLE,
			'label' => __( 'Column Title', 'tribe' ),
		] ) );

		// Column Text
		$columns->add_field( $this->handler->field( 'TextArea', [
			'name'     => self::FIELD_COLUMN_TEXT,
			'label'    => __( 'Column Text', 'tribe' ),
			'richtext' => true
		] ) );

		// Column CTA Link
		$columns->add_field( $this->handler->field( 'Link', [
			'name'  => self::FIELD_COLUMN_CTA,
			'label' => __( 'Call To Action Link', 'tribe' ),
		] ) );

		// Column CTA Link Style
		$columns->add_field( $this->handler->field( 'ImageSelect', [
			'name'    => self::FIELD_COLUMN_CTA_STYLE,
			'label'   => __( 'Call To Action Link Style', 'tribe' ),
			'options' => [
				self::FIELD_COLUMN_CTA_STYLE_OPTION_TEXT   => $this->handler->layout_icon_url( 'link-style-text.png' ),
				self::FIELD_COLUMN_CTA_STYLE_OPTION_BUTTON => $this->handler->layout_icon_url( 'link-style-button.png' ),
			],
			'default' => self::FIELD_COLUMN_CTA_STYLE_OPTION_TEXT,
		] ) );

		// Repeater Fields
		$panel->add_field( $columns );

		return $panel;

	}
}