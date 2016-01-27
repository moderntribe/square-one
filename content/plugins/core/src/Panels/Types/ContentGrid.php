<?php

namespace Tribe\Project\Panels\Types;
use ModularContent\Fields;

class ContentGrid extends Panel_Type_Config {
	protected function panel() {

		$panel = $this->handler->factory( 'contentgrid' );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Content Grid', 'tribe' ) );
		$panel->set_description( __( 'A grid of content with 2 layouts.', 'tribe' ) );
		$panel->set_icon( $this->handler->inactive_icon_url( 'module-contentgrid.png' ), 'inactive' );
		$panel->set_icon( $this->handler->active_icon_url( 'module-contentgrid.png' ), 'active' );

		// Panel Style
		$panel->add_field( $this->handler->field( 'ImageSelect', array(
			'name'      => 'layout',
			'label'     => __( 'Style', 'tribe' ),
			'options'   => array(
				'standard'  => $this->handler->layout_icon_url( 'module-contentgrid-standard.png' ),
				'cards'     => $this->handler->layout_icon_url( 'module-contentgrid-cards.png' ),
				'full'      => $this->handler->layout_icon_url( 'module-contentgrid-full.png' ),
			),
			'default' => 'standard',
		) ) );

		// Panel Description
		$panel->add_field( $this->handler->field( 'TextArea', array(
			'name'      => 'content',
			'label'     => __( 'Description', 'tribe' ),
		) ) );

		// Grid Columns
		/** @var Fields\Group $columns */
		$columns = $this->handler->field( 'Repeater', array(
			'label'            => __( 'Content Blocks', 'tribe' ),
			'name'             => 'columns',
			'min'              => 2,
			'max'              => 4,
			'new_button_label' => __( 'Add Content Block', 'tribe' )
		) );

		// Column Title
		$columns->add_field( $this->handler->field( 'Text', array(
			'name'      => 'title',
			'label'     => __( 'Column Title', 'tribe' ),
		) ) );

		// Column Text
		$columns->add_field( $this->handler->field( 'TextArea', array(
			'name'      => 'text',
			'label'     => __( 'Column Text', 'tribe' ),
			'richtext'  => true
		) ) );

		// Column CTA Link
		$columns->add_field( $this->handler->field( 'Link', array(
			'name'      => 'cta',
			'label'     => __( 'Call To Action Link', 'tribe' ),
		) ) );

		// Column CTA Link Style
		$columns->add_field( $this->handler->field( 'ImageSelect', array(
			'name'      => 'cta_style',
			'label'     => __( 'Call To Action Link Style', 'tribe' ),
			'options'   => array(
				'text'      => $this->handler->layout_icon_url( 'link-style-text.png' ),
				'button'    => $this->handler->layout_icon_url( 'link-style-button.png' ),
		    ),
			'default' => 'text',
		) ) );

		// Repeater Fields
		$panel->add_field( $columns );

		return $panel;

	}
}