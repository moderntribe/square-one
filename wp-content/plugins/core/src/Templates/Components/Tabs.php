<?php

namespace Tribe\Project\Templates\Components;

class Tabs extends Component {

	const TEMPLATE_NAME = 'components/tabs.twig';

	const TABS                       = 'tabs';
	const TABLIST_BUTTONS            = 'tablist_buttons';
	const TABS_ID                    = 'tab_id';
	const CONTAINER_CLASSES          = 'container_classes';
	const CONTAINER_ATTRS            = 'container_attrs';
	const TAB_LIST_CLASSES           = 'tab_list_classes';
	const TAB_LIST_ATTRS             = 'tab_list_attrs';
	const TAB_BUTTON_CLASSES         = 'tab_button_classes';
	const TAB_BUTTON_ACTIVE_CLASS    = 'tab_button_active_class';
	const TAB_BUTTON_ATTRS           = 'tab_button_attrs';
	const TAB_BUTTON_OPTIONS         = 'tab_button_options';
	const TAB_CONTENT_CLASSES        = 'tab_content_classes';
	const TAB_CONTENT_ATTRS          = 'tab_content_attrs';
	const TAB_CONTENT_ACTIVE_CLASS   = 'tab_content_active_class';
	const TAB_CONTENT_INNER_CLASSES  = 'tab_content_inner_classes';
	const TAB_CONTENT_INNER_ATTRS    = 'tab_content_inner_attrs';

	public function parse_options( array $options ): array {
		// wp_parse_args works for one level down
		$defaults = [
			self::TABS                       => [],
			self::TABLIST_BUTTONS            => [],
			self::TABS_ID                    => uniqid('tab-'),
			self::CONTAINER_CLASSES          => [ 'c-tabs' ],
			self::CONTAINER_ATTRS            => [],
			self::TAB_LIST_CLASSES           => [ 'c-tab__list' ],
			self::TAB_LIST_ATTRS             => [],
			self::TAB_BUTTON_CLASSES         => [ 'c-tab__button' ],
			self::TAB_BUTTON_ACTIVE_CLASS    => 'c-tab__button--active',
			self::TAB_BUTTON_ATTRS           => [],
			self::TAB_BUTTON_OPTIONS         => [],
			self::TAB_CONTENT_CLASSES        => [ 'c-tab__content', 't-content' ],
			self::TAB_CONTENT_ATTRS          => [],
			self::TAB_CONTENT_ACTIVE_CLASS   => 'c-tab__content--active',
			self::TAB_CONTENT_INNER_CLASSES  => [ 'c-tab__content-inner' ],
			self::TAB_CONTENT_INNER_ATTRS    => [],
		];
		return wp_parse_args( $options, $defaults );
	}

	protected function get_tablist_buttons(): array {
		$tabs = $this->options[self::TABS];
		$buttons = [];
		foreach ( $tabs as $key => $tab ) {
			$button_attributes_default = [
				'role'           => 'tab',
				'data-js'        => 'c-tab__button',
				'id'             => esc_attr( $tab[ 'tab_id' ] ),
				'aria-controls'  => esc_attr( $tab[ 'content_id' ] ),
				'aria-selected'  => ( $key === 0 ) ? 'true' : 'false',
				'data-row-index' => $key
			];
			$button_attributes = $this->merge_attrs( $button_attributes_default, $this->options[self::TAB_BUTTON_ATTRS] );
			if ( isset( $tab[ 'btn_attrs' ] ) ) {
				$button_attributes = $this->merge_attrs( $button_attributes, $tab[ 'btn_attrs' ] );
			}
			$btn_classes = $this->options[self::TAB_BUTTON_CLASSES];
			if ($key === 0) {
				$btn_classes = wp_parse_args( $btn_classes, [ $this->options[self::TAB_BUTTON_ACTIVE_CLASS] ] );
			}
			$options = [
				Button::LABEL   => $tab[ 'tab_text' ],
				Button::CLASSES => $btn_classes,
				Button::ATTRS   => $button_attributes,
			];
			$options = wp_parse_args( $options, $tab[ 'btn_options' ] );
			$btn_obj = Button::factory( $options );
			$buttons[]  = $btn_obj->render();
		}
		return $buttons;
	}

	public function get_data(): array {
		$data = [
			self::TABS                       => $this->options[self::TABS],
			self::TABS_ID                    => $this->options[self::TABS_ID],
			self::CONTAINER_ATTRS            => $this->merge_attrs(
				[
					'data-js'                   => 'c-tabs',
					'data-button-active-class'  => $this->options[ self::TAB_BUTTON_ACTIVE_CLASS ],
					'data-content-active-class' => $this->options[ self::TAB_CONTENT_ACTIVE_CLASS ]
				],
				$this->options[self::CONTAINER_ATTRS],
				true
			),
			self::CONTAINER_CLASSES          => $this->merge_classes( [], $this->options[self::CONTAINER_CLASSES], true ),
			self::TAB_LIST_CLASSES           => $this->merge_classes( [], $this->options[self::TAB_LIST_CLASSES], true ),
			self::TAB_LIST_ATTRS             => $this->merge_attrs(
				[
					'role' => 'tablist',
					'data-js' => 'c-tablist',
				],
				$this->options[self::TAB_LIST_ATTRS],
				true
			),
			self::TAB_CONTENT_CLASSES        => $this->merge_classes( [], $this->options[self::TAB_CONTENT_CLASSES], true ),
			self::TAB_CONTENT_ATTRS          => $this->merge_attrs(
				[
					'role' => 'tabpanel',
					'tabindex' => '0'
				],
				$this->options[self::TAB_CONTENT_ATTRS],
				true
			),
			self::TAB_CONTENT_ACTIVE_CLASS   => $this->options[ self::TAB_CONTENT_ACTIVE_CLASS ],
			self::TAB_CONTENT_INNER_CLASSES  => $this->merge_classes( [], $this->options[ self::TAB_CONTENT_INNER_CLASSES ], true ),
			self::TAB_CONTENT_INNER_ATTRS    => $this->merge_attrs( [], $this->options[ self::TAB_CONTENT_INNER_ATTRS ], true ),
		];
		$data[ self::TABLIST_BUTTONS ] = $this->get_tablist_buttons();

		return $data;
	}
}
