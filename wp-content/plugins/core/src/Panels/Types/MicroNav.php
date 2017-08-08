<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class MicroNav extends Panel_Type_Config {

	const NAME = 'micronav';

	const FIELD_LAYOUT               = 'layout';
	const FIELD_LAYOUT_OPTION_BUTTON = 'buttons';
	const FIELD_LAYOUT_OPTION_LIST   = 'list';
	const FIELD_CONTENT              = 'content';
	const FIELD_ITEMS                = 'items';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'MicroNav', 'tribe' ) );
		$panel->set_description( __( 'Display a set of links and related content.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'micronav.svg' ) );

		// Panel Layout
		$panel->add_settings_field( new Fields\ImageSelect( [
			'name'    => self::FIELD_LAYOUT,
			'label'   => __( 'Style', 'tribe' ),
			'options' => [
				self::FIELD_LAYOUT_OPTION_BUTTON => $this->handler->layout_icon_url( 'module-micronav-buttons.png' ),
				self::FIELD_LAYOUT_OPTION_LIST   => $this->handler->layout_icon_url( 'module-micronav-list.png' ),
			],
			'default' => 'buttons',
		] ) );

		// Optional Content
		$panel->add_field( new Fields\TextArea( [
			'name'     => self::FIELD_CONTENT,
			'label'    => __( 'Content', 'tribe' ),
			'richtext' => true
		] ) );

		$panel->add_field( new Fields\Post_List( [
			'name'             => self::FIELD_ITEMS,
			'label'            => __( 'Links', 'tribe' ),
			'max'              => 12,
			'min'              => 1,
			'suggested'        => 3,
			'show_max_control' => true,
			'hidden_fields'    => [ 'post_content', 'thumbnail_id' ],
			'strings'          => [
				'button.create_content' => __( 'Add Link', 'tribe' ),
			]
		] ) );

		return $panel;

	}
}