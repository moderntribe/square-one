<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class CardGrid extends Panel_Type_Config {

	const NAME = 'cardgrid';

	const FIELD_TITLE            = 'title';
	const FIELD_DESCRIPTION      = 'description';
	const FIELD_CARDS            = 'cards';
	const FIELD_CARD_TITLE       = 'card_title';
	const FIELD_CARD_DESCRIPTION = 'card_description';
	const FIELD_CARD_IMAGE       = 'card_image';
	const FIELD_CARD_CTA         = 'card_cta';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Card Grid', 'tribe' ) );
		$panel->set_description( __( 'A grid of card components.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'card-grid.svg' ) );

		$panel->add_field( new Fields\TextArea( [
			'name'     => self::FIELD_DESCRIPTION,
			'label'    => __( 'Description', 'tribe' ),
			'richtext' => true,
		] ) );

		$card = new Fields\Repeater( [
			'label'            => __( 'Cards', 'tribe' ),
			'name'             => self::FIELD_CARDS,
			'min'              => 2,
			'max'              => 4,
			'new_button_label' => __( 'Add Card', 'tribe' ),
			'strings'          => [
				'label.row_index' => __( 'Card %{index} |||| Card %{index}', 'tribe' ),
				'button.delete'   => __( 'Delete Card', 'tribe' ),
			],
		] );

		$card->add_field( new Fields\Text( [
			'name'  => self::FIELD_CARD_TITLE,
			'label' => __( 'Card Title', 'tribe' ),
		] ) );

		$card->add_field( new Fields\TextArea( [
			'name'     => self::FIELD_CARD_DESCRIPTION,
			'label'    => __( 'Card Description', 'tribe' ),
			'richtext' => true,
		] ) );

		$card->add_field( new Fields\Image( [
			'name'        => self::FIELD_CARD_IMAGE,
			'label'       => __( 'Image', 'tribe' ),
			'description' => __( 'Optimal image sizes: 1500 x 1125 for Left/Right Aligned layouts; 1500 x 844 for Boxed/Hero layouts.', 'tribe' ),
			'size'        => 'medium', // the size displayed in the admin.
		] ) );

		$card->add_field( new Fields\Link( [
			'name'  => self::FIELD_CARD_CTA,
			'label' => __( 'Card Call To Action Link', 'tribe' ),
		] ) );

		$panel->add_field( $card );

		return $panel;
	}
}
