<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class Testimonial extends Panel_Type_Config {

	const NAME = 'testimonial';

	const FIELD_TITLE  = 'title';
	const FIELD_QUOTES = 'quotes';
	const FIELD_QUOTE  = 'quote';
	const FIELD_CITE   = 'cite';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Testimonial', 'tribe' ) );
		$panel->set_description( __( 'A series of testimonials or quotes.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'testimonial.svg' ) );

		$quote = new Fields\Repeater( [
			'label'            => __( 'Testimonials', 'tribe' ),
			'name'             => self::FIELD_QUOTES,
			'min'              => 2,
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