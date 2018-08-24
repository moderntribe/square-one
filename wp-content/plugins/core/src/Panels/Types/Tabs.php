<?php

namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class Tabs extends Panel_Type_Config {

	const NAME = 'tabs';

	const FIELD_DESCRIPTION   = 'description';
	const FIELD_TITLE         = 'title';
	const FIELD_TABS          = 'tabs';
	const FIELD_TABS_TITLE    = 'row_header';
	const FIELD_TABS_CONTENT  = 'row_content';

	protected function panel() {

		$panel = $this->handler->factory( self::NAME );
		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'Tabs', 'tribe' ) );
		$panel->set_description( __( 'Display a series of tabs.', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'tabs.svg' ) );

		$panel->add_field(
			new Fields\TextArea(
				[
					'name'     => self::FIELD_DESCRIPTION,
					'label'    => __( 'Description', 'tribe' ),
					'richtext' => true,
				]
			)
		);

		$repeater = new Fields\Repeater(
			[
				'name'    => self::FIELD_TABS,
				'label'   => __( 'Tabs Rows', 'tribe' ),
				'min'     => 1,
				'max'     => 10,
				'strings' => [
					'button.new'      => __( 'Add Tab Row', 'tribe' ),
					'button.delete'   => __( 'Delete Tab Row', 'tribe' ),
					'label.row_index' => __( 'Tab Row %{index} |||| Tab Row %{index}', 'tribe' ),
				],
			]
		);

		$repeater->add_field(
			new Fields\Text(
				[
					'name'  => self::FIELD_TABS_TITLE,
					'label' => __( 'Tab Title', 'tribe' ),
				]
			)
		);

		$repeater->add_field(
			new Fields\TextArea(
				[
					'name'     => self::FIELD_TABS_CONTENT,
					'label'    => __( 'Tab Content', 'tribe' ),
					'richtext' => true,
				]
			)
		);

		$panel->add_field( $repeater );

		return $panel;
	}
}
