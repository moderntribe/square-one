<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class LogoFarm extends Panel_Type_Config {

	const NAME = 'logofarm';

	const FIELD_TITLE       = 'title';
	const FIELD_DESCRIPTION = 'description';
	const FIELD_LOGOS       = 'logos';
	const FIELD_LOGO_IMAGE  = 'logo_image';
	const FIELD_LOGO_CTA    = 'logo_cta';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Logo farm', 'tribe' ) );
		$panel->set_description( __( 'A collection of logos.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'logo-farm.svg' ) );

		$panel->add_field( new Fields\TextArea( [
			'name'     => self::FIELD_DESCRIPTION,
			'label'    => __( 'Description', 'tribe' ),
			'richtext' => true,
		] ) );

		$logo = new Fields\Repeater( [
			'label'            => __( 'Logos', 'tribe' ),
			'name'             => self::FIELD_LOGOS,
			'min'              => 2,
			'max'              => 6,
			'new_button_label' => __( 'Add Logo', 'tribe' ),
			'strings'          => [
				'label.row_index' => 'Logo %{index} |||| Logo %{index}',
				'button.delete'   => __( 'Delete Logo', 'tribe' ),
			],
		] );

		$logo->add_field( new Fields\Image( [
			'name'        => self::FIELD_LOGO_IMAGE,
			'label'       => __( 'Image', 'tribe' ),
			'description' => __( 'Optimal image sizes: 300 x 300.', 'tribe' ),
			'size'        => 'medium', // the size displayed in the admin.
		] ) );

		$logo->add_field( new Fields\Link( [
			'name'  => self::FIELD_LOGO_CTA,
			'label' => __( 'Logo Link', 'tribe' ),
		] ) );

		$panel->add_field( $logo );

		return $panel;

	}
}