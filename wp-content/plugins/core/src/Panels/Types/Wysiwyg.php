<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class Wysiwyg extends Panel_Type_Config {

	const NAME = 'wysiwyg';

	const FIELD_LAYOUT              = 'layout';
	const FIELD_LAYOUT_OPTION_LEFT  = 'left';
	const FIELD_LAYOUT_OPTION_RIGHT = 'right';
	const FIELD_COLUMNS             = 'columns';
	const FIELD_COLUMN              = 'column';

	protected function panel() {

		$helper_text = '<p>' . __( 'Sometimes you need to lay out content your own way. This panel allows you to use the WordPress WYSIWYG editor to lay out text and images in a single column, two or even three columns.', 'tribe' ) . '</p><p><strong>' . __( 'GOOD FOR:', 'tribe' ) . '</strong> ' . __( 'Displaying text and images, embedding YouTube videos, or social media feeds.', 'tribe' ) . '</p>';
		$panel       = $this->handler->factory( self::NAME, $helper_text );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'WYSIWYG Editor', 'tribe' ) );
		$panel->set_description( __( 'Displays custom content', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'module-wysiwyg.jpg' ) );

		$panel->add_settings_field( new Fields\ImageSelect( [
			'name'    => self::FIELD_LAYOUT,
			'label'   => __( 'Layout', 'tribe' ),
			'options' => [
				self::FIELD_LAYOUT_OPTION_RIGHT => $this->handler->layout_icon_url( 'module-imagetext-right.png' ),
				self::FIELD_LAYOUT_OPTION_LEFT  => $this->handler->layout_icon_url( 'module-imagetext-left.png' ),
			],
			'default' => self::FIELD_LAYOUT_OPTION_RIGHT,
		] ) );

		$group = new Fields\Repeater( [
			'label'            => __( 'Columns', 'tribe' ),
			'name'             => self::FIELD_COLUMNS,
			'min'              => 1,
			'max'              => 3,
			'new_button_label' => __( 'Add Column', 'tribe' ),
		] );

		$group->add_field( new Fields\TextArea( [
			'label'    => __( 'Column', 'tribe' ),
			'name'     => self::FIELD_COLUMN,
			'richtext' => true,
		] ) );

		$panel->add_field( $group );

		return $panel;
	}
}