<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class Gallery extends Panel_Type_Config {

	const NAME = 'gallery';

	const FIELD_TITLE          = 'title';
	const FIELD_DESCRIPTION    = 'description';
	const FIELD_GALLERY        = 'gallery';
	const FIELD_CTA            = 'cta';
	const FIELD_LIGHTBOX       = 'lightbox';
	const FIELD_CONTENT_LAYOUT = 'content_layout';
	const FIELD_COLUMNS        = 'columns';
	const FIELD_BUTTON_LABEL   = 'button_label';

	const OPTION_LIGHTBOX_ON     = 'lightbox_on';
	const OPTION_LIGHTBOX_OFF    = 'lightbox_off';
	const OPTION_CONTENT_INLINE  = 'content_inline';
	const OPTION_CONTENT_STACKED = 'content_stacked';
	const OPTION_TWO_COLUMNS     = 'two_columns';
	const OPTION_THREE_COLUMNS   = 'three_columns';
	const OPTION_FOUR_COLUMNS    = 'four_columns';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Gallery', 'tribe' ) );
		$panel->set_description( __( 'An image gallery with layout, columns, and lightbox options.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'gallery.svg' ) );

		$panel->add_settings_field( new Fields\ImageSelect( [
			'name'    => self::FIELD_CONTENT_LAYOUT,
			'label'   => __( 'Content Layout', 'tribe' ),
			'options' => [
				self::OPTION_CONTENT_INLINE   => $this->handler->layout_icon_url( 'layout-content-inline.svg' ),
				self::OPTION_CONTENT_STACKED => $this->handler->layout_icon_url( 'layout-content-stacked.svg' ),
			],
			'default' => self::OPTION_CONTENT_INLINE,
		] ) );

		$panel->add_settings_field( new Fields\ImageSelect( [
			'name'    => self::FIELD_COLUMNS,
			'label'   => __( 'Columns', 'tribe' ),
			'options' => [
				self::OPTION_TWO_COLUMNS   => $this->handler->layout_icon_url( 'columns-two.svg' ),
				self::OPTION_THREE_COLUMNS => $this->handler->layout_icon_url( 'columns-three.svg' ),
				self::OPTION_FOUR_COLUMNS  => $this->handler->layout_icon_url( 'columns-four.svg' ),
			],
			'default' => self::OPTION_TWO_COLUMNS,
		] ) );

		$panel->add_settings_field( new Fields\ImageSelect( [
			'name'    => self::FIELD_LIGHTBOX,
			'label'   => __( 'Lightbox', 'tribe' ),
			'options' => [
				self::OPTION_LIGHTBOX_ON  => $this->handler->layout_icon_url( 'lightbox-on.svg' ),
				self::OPTION_LIGHTBOX_OFF => $this->handler->layout_icon_url( 'lightbox-off.svg' ),
			],
			'default' => self::OPTION_LIGHTBOX_ON,
		] ) );

		$panel->add_field( new Fields\TextArea( [
			'name'     => self::FIELD_DESCRIPTION,
			'label'    => __( 'Description', 'tribe' ),
			'richtext' => true,
		] ) );

		$panel->add_field( new Fields\ImageGallery( [
			'label' => __( 'Image Gallery', 'tribe' ),
			'name'  => self::FIELD_GALLERY,
		] ) );

		$panel->add_field( new Fields\Text( [
			'name'     => self::FIELD_BUTTON_LABEL,
			'label'    => __( 'Button Label', 'tribe' ),
		] ) );

		return $panel;

	}
}
