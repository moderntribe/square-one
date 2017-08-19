<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class Testimonial extends Panel_Type_Config {

	const NAME = 'testimonial';

	const FIELD_TITLE      = 'title';
	const FIELD_IMAGE      = 'image';
	const FIELD_QUOTES     = 'quotes';
	const FIELD_QUOTE      = 'quote';
	const FIELD_CITE       = 'cite';
	const FIELD_TEXT_COLOR = 'text_color';
	const FIELD_TEXT_WHITE = 't-content--light';
	const FIELD_TEXT_BLACK = 't-content--dark';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Testimonial', 'tribe' ) );
		$panel->set_description( __( 'A series of testimonials or quotes.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'testimonial.svg' ) );

		$panel->add_settings_field( new Fields\Radio( [
			'name'    => self::FIELD_TEXT_COLOR,
			'label'   => __( 'Text Color', 'tribe' ),
			'options' => [
				self::FIELD_TEXT_WHITE => __( 'White', 'tribe' ),
				self::FIELD_TEXT_BLACK => __( 'Black', 'tribe' ),
			],
			'default' => self::FIELD_TEXT_BLACK,
		] ) );

		$panel->add_field( new Fields\Image( [
			'name'        => self::FIELD_IMAGE,
			'label'       => __( 'Background Image', 'tribe' ),
			'description' => __( 'Optimal image sizes: 1500 x 1125 for Left/Right Aligned layouts; 1500 x 844 for Boxed/Hero layouts.', 'tribe' ),
			'size'        => 'medium', // the size displayed in the admin.
		] ) );

		$quote = new Fields\Repeater( [
			'label'            => __( 'Testimonials', 'tribe' ),
			'name'             => self::FIELD_QUOTES,
			'min'              => 1,
			'max'              => 4,
			'new_button_label' => __( 'Add Quote', 'tribe' ),
			'strings'          => [
				'label.row_index' => 'Quote %{index} |||| Quote %{index}',
				'button.delete'   => __( 'Delete Quote', 'tribe' ),
			],
		] );

		$quote->add_field( new Fields\TextArea( [
			'name'     => self::FIELD_QUOTE,
			'label'    => __( 'Quote', 'tribe' ),
		] ) );

		$quote->add_field( new Fields\Text( [
			'name'  => self::FIELD_CITE,
			'label' => __( 'Cite', 'tribe' ),
		] ) );

		$panel->add_field( $quote );

		return $panel;

	}
}