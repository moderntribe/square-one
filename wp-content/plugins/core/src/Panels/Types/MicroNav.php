<?php

namespace Tribe\Project\Panels\Types;
use ModularContent\Fields;

class MicroNav extends Panel_Type_Config {
	protected function panel() {

		$panel = $this->handler->factory( 'micronav' );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'MicroNav', 'tribe' ) );
		$panel->set_description( __( 'Display a set of links and related content.', 'tribe' ) );
		$panel->set_icon( $this->handler->inactive_icon_url( 'module-micronav.png' ), 'inactive' );
		$panel->set_icon( $this->handler->active_icon_url( 'module-micronav.png' ), 'active' );

		// Panel Layout
		$panel->add_field( $this->handler->field( 'ImageSelect', array(
			'name'      => 'layout',
			'label'     => __( 'Style', 'tribe' ),
			'options'   => array(
				'buttons'   => $this->handler->layout_icon_url( 'module-micronav-buttons.png' ),
				'list'      => $this->handler->layout_icon_url( 'module-micronav-list.png' ),
			),
			'default' => 'buttons',
		) ) );

		// Optional Content
		$panel->add_field( $this->handler->field( 'TextArea', array(
			'name'      => 'content',
			'label'     => __( 'Content', 'tribe' ),
			'richtext'  => true
		) ) );

		$panel->add_field( $this->handler->field( 'Post_List', array(
			'name' => 'items',
			'label' => __( 'Links', 'tribe' ),
			'max' => 12,
			'min' => 1,
			'suggested' => 3,
			'show_max_control' => true,
			'hidden_fields' => [ 'post_content', 'thumbnail_id' ],
			'strings' => [
				'button.create_content' => 'Add Link',
			]
		) ) );

		return $panel;

	}
}