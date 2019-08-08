<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class MediaText extends Panel_Type_Config {

	const NAME = 'mediatext';

	const FIELD_TITLE          = 'title';
	const FIELD_DESCRIPTION    = 'description';
	const FIELD_IMAGE          = 'image';
	const FIELD_VIDEO          = 'video';
	const FIELD_CTA            = 'cta';
	const FIELD_MEDIA_TYPE	   = 'media_type';
	const FIELD_MEDIA_POSITION = 'media_position';
	const FIELD_LAYOUT         = 'layout';

	const OPTION_MEDIA_TYPE_IMAGE   = 'option_media_type_image';
	const OPTION_MEDIA_TYPE_VIDEO	= 'option_media_type_video';
	const OPTION_LAYOUT_IMAGE_RIGHT = 'option_media_position_right';
	const OPTION_LAYOUT_IMAGE_LEFT  = 'option_media_position_left';
	const OPTION_LAYOUT_BOXED       = 'option_layout_boxed';
	const OPTION_LAYOUT_FULL_BLEED  = 'option_layout_full_bleed';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Media + Text', 'tribe' ) );
		$panel->set_description( __( 'Displays an image or video with content and 4 layout options.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'media-text.svg' ) );

		$panel->add_settings_field( new Fields\ImageSelect( [
			'name'    => self::FIELD_MEDIA_POSITION,
			'label'   => __( 'Layout', 'tribe' ),
			'options' => [
				self::OPTION_LAYOUT_IMAGE_RIGHT => $this->handler->layout_icon_url( 'media-text-left.svg' ),
				self::OPTION_LAYOUT_IMAGE_LEFT  => $this->handler->layout_icon_url( 'media-text-right.svg' ),
			],
			'default' => self::OPTION_LAYOUT_IMAGE_LEFT,
		] ) );

		$panel->add_settings_field( new Fields\ImageSelect( [
			'name'    => self::FIELD_LAYOUT,
			'label'   => __( 'Layout', 'tribe' ),
			'options' => [
				self::OPTION_LAYOUT_BOXED      => $this->handler->layout_icon_url( 'media-text-boxed.svg' ),
				self::OPTION_LAYOUT_FULL_BLEED => $this->handler->layout_icon_url( 'media-text-full-bleed.svg' ),
			],
			'default' => self::OPTION_LAYOUT_BOXED,
		] ) );

		$panel->add_field( new Fields\TextArea( [
			'name'     => self::FIELD_DESCRIPTION,
			'label'    => __( 'Description', 'tribe' ),
			'richtext' => true,
		] ) );

		$panel->add_field( new Fields\ImageSelect( [
			'name'    => self::FIELD_MEDIA_TYPE,
			'label'   => __( 'Media Type', 'tribe' ),
			'options' => [
				self::OPTION_MEDIA_TYPE_IMAGE => $this->handler->layout_icon_url( 'media-type-image.svg' ),
				self::OPTION_MEDIA_TYPE_VIDEO => $this->handler->layout_icon_url( 'media-type-video.svg' ),
			],
			'default' => self::OPTION_MEDIA_TYPE_IMAGE,
		] ) );

		$panel->add_field( new Fields\Image( [
			'name'        => self::FIELD_IMAGE,
			'label'       => __( 'Image', 'tribe' ),
			'description' => __( 'Optimal image sizes: 1500 x 1125 for Left/Right Aligned layouts', 'tribe' ),
			'size'        => 'medium',
		] ) );

		$panel->add_field( new Fields\Text( [
			'name'        => self::FIELD_VIDEO,
			'label'       => __( 'Video', 'tribe' ),
			'description' => __( 'Add a video url.', 'tribe' ),
			'size'        => 'medium',
		] ) );

		$panel->add_field( new Fields\Link( [
			'name'  => self::FIELD_CTA,
			'label' => __( 'Call To Action Link', 'tribe' ),
		] ) );

		return $panel;

	}
}