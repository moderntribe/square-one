<?php

namespace Tribe\Project\Panels\Types;
use ModularContent\Fields;

class Wysiwyg extends Panel_Type_Config {
	protected function panel() {

		$helper_text = '<p>' . __( 'Sometimes you need to lay out content your own way. This panel allows you to use the WordPress WYSIWYG editor to lay out text and images in a single column, two or even three columns.', 'tribe' ) . '</p><p><strong>' . __( 'GOOD FOR:', 'tribe' ) . '</strong> ' . __( 'Displaying text and images, embedding YouTube videos, or social media feeds.', 'tribe' ) . '</p>';
		$panel = $this->handler->factory( 'wysiwyg', $helper_text );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'WYSIWYG Editor', 'tribe' ) );
		$panel->set_description( __( 'Displays custom content', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->inactive_icon_url( 'module-wysiwyg.png' ) );

		// Field: Editor Columns
		$group = new Fields\Repeater( array(
			'label'            => __( 'Columns', 'tribe' ),
			'name'             => 'repeater',
			'min'              => 1,
			'max'              => 3,
			'new_button_label' => __( 'Add Column', 'tribe' )
		) );

		$group->add_field( new Fields\TextArea( array(
			'label'    => __( 'Column', 'tribe' ),
			'name'     => 'column',
			'richtext' => true
		) ) );

		$panel->add_field( $group );

		return $panel;
	}
}