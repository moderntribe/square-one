<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class ContentSlider extends Panel_Type_Config {

	const NAME = 'content-slider';

	const FIELD_TITLE         = 'title';
	const FIELD_DESCRIPTION   = 'description';
	const FIELD_SLIDES        = 'slides';
	const FIELD_SLIDE_IMAGE   = 'slide_img';
	const FIELD_SLIDE_TITLE   = 'slide_title';
	const FIELD_SLIDE_CONTENT = 'slide_content';
	const FIELD_SLIDE_CTA     = 'slide_cta';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Slider', 'tribe' ) );
		$panel->set_description( __( 'An image + content slider', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'slider.svg' ) );

		$panel->add_field( new Fields\Text( [
			'name'     => self::FIELD_TITLE,
			'label'    => __( 'Title', 'tribe' ),
			'description' => __( 'Title does not display on the front-end of the website.', 'tribe' ),
		] ) );

		$slides = new Fields\Repeater( [
			'label'            => __( 'Logos', 'tribe' ),
			'name'             => self::FIELD_SLIDES,
			'min'              => 2,
			'max'              => 5,
			'new_button_label' => __( 'Add Slide', 'tribe' ),
			'strings'          => [
				'label.row_index' => 'Slide %{index} |||| Slide %{index}',
				'button.delete'   => __( 'Delete Slide', 'tribe' ),
			],
		] );

		$slides->add_field( new Fields\Image( [
			'name'        => self::FIELD_SLIDE_IMAGE,
			'label'       => __( 'Image', 'tribe' ),
			'description' => __( 'Optimal image sizes: 1700 x 600 pixels', 'tribe' ),
			'size'        => 'medium', // the size displayed in the admin.
		] ) );

		$slides->add_field( new Fields\Text( [
			'name'     => self::FIELD_SLIDE_TITLE,
			'label'    => __( 'Title', 'tribe' ),
		] ) );

		$slides->add_field( new Fields\TextArea( [
			'name'     => self::FIELD_SLIDE_CONTENT,
			'label'    => __( 'Content', 'tribe' ),
			'richtext' => true,
		] ) );

		$slides->add_field( new Fields\Link( [
			'name'  => self::FIELD_SLIDE_CTA,
			'label' => __( 'Logo Link', 'tribe' ),
		] ) );

		$panel->add_field( $slides );

		return $panel;

	}
}
