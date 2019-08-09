<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class MicroNavButtons extends Panel_Type_Config {

	const NAME = 'micronavbuttons';

	const FIELD_TITLE       = 'title';
	const FIELD_DESCRIPTION = 'description';
	const FIELD_ITEMS       = 'items';
	const FIELD_ITEM_CTA    = 'item_cta';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Micro Nav Buttons', 'tribe' ) );
		$panel->set_description( __( 'A grid of buttons', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'micronav.svg' ) );

		$panel->add_field( new Fields\TextArea( [
			'name'     => self::FIELD_DESCRIPTION,
			'label'    => __( 'Description', 'tribe' ),
			'richtext' => true,
		] ) );

		$card = new Fields\Repeater( [
			'label'            => __( 'Items', 'tribe' ),
			'name'             => self::FIELD_ITEMS,
			'min'              => 2,
			'max'              => 4,
			'new_button_label' => __( 'Add Item', 'tribe' ),
			'strings'          => [
				'label.row_index' => __( 'Item %{index} |||| Item %{index}', 'tribe' ),
				'button.delete'   => __( 'Delete Item', 'tribe' ),
			],
		] );

		$card->add_field( new Fields\Link( [
			'name'  => self::FIELD_ITEM_CTA,
			'label' => __( 'Item Call To Action Link', 'tribe' ),
		] ) );

		$panel->add_field( $card );

		return $panel;

	}
}
