<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class Wysiwyg extends Panel_Type_Config {

	const NAME = 'wysiwyg';

	const FIELD_TITLE          = 'title';
	const FIELD_DESCRIPTION    = 'description';
	const FIELD_COLUMNS        = 'columns';
	const FIELD_COLUMN_CONTENT = 'column_content';
	const FIELD_LAYOUT         = 'layout';

	const OPTION_LAYOUT_LEFT   = 'left';
	const OPTION_LAYOUT_CENTER = 'center';

	protected function panel() {

		$helper_text = '<p>' . __( 'Sometimes you need to lay out content your own way. This panel allows you to use the WordPress WYSIWYG editor to lay out text and images in a single column, two or even three columns.',
				'tribe' ) . '</p><p><strong>' . __( 'GOOD FOR:',
				'tribe' ) . '</strong> ' . __( 'Displaying text and images, embedding YouTube videos, or social media feeds.', 'tribe' ) . '</p>';
		$panel       = $this->handler->factory( self::NAME, $helper_text );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'WYSIWYG Editor', 'tribe' ) );
		$panel->set_description( __( 'Displays custom content in columns', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'wysiwyg.svg' ) );

		// Panel Layout.
		$panel->add_settings_field( new Fields\ImageSelect( [
			'name'    => self::FIELD_LAYOUT,
			'label'   => __( 'Layout', 'tribe' ),
			'options' => [
				self::OPTION_LAYOUT_CENTER => $this->handler->layout_icon_url( 'content-align-center.svg' ),
				self::OPTION_LAYOUT_LEFT   => $this->handler->layout_icon_url( 'content-align-left.svg' ),
			],
			'default' => self::OPTION_LAYOUT_CENTER,
		] ) );

		$panel->add_field( new Fields\TextArea( [
			'label'    => __( 'Description', 'tribe' ),
			'name'     => self::FIELD_DESCRIPTION,
			'richtext' => true,
		] ) );

		$group = new Fields\Repeater( [
			'label'            => __( 'Columns', 'tribe' ),
			'name'             => self::FIELD_COLUMNS,
			'min'              => 1,
			'max'              => 3,
			'new_button_label' => __( 'Add Column', 'tribe' ),
			'strings'          => [
				'label.row_index' => __( 'Column %{index} |||| Column %{index}', 'tribe' ),
				'button.delete'   => __( 'Delete Column', 'tribe' ),
			],
		] );

		$group->add_field( new Fields\TextArea( [
			'label'    => __( 'Column Content', 'tribe' ),
			'name'     => self::FIELD_COLUMN_CONTENT,
			'richtext' => true,
		] ) );

		$panel->add_field( $group );

		return $panel;
	}
}
