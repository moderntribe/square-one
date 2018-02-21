<?php

namespace Tribe\Project\Templates\Components;

class Tabs extends Component {

	const TEMPLATE_NAME = 'components/tabs.twig';

	const TABS                       = 'tabs';
	const TABS_ID                    = 'tab_id';
	const CONTAINER_CLASSES          = 'container_classes';
	const CONTAINER_ATTRS            = 'container_attrs';
	const TAB_LIST_CLASSES           = 'tab_list_classes';
	const TAB_BUTTON_CLASSES         = 'tab_button_classes';
	const TAB_BUTTON_ACTIVE_CLASSES  = 'tab_button_active_classes';
	const TAB_CONTENT_CLASSES        = 'tab_content_classes';
	const TAB_CONTENT_ACTIVE_CLASSES = 'tab_content_active_classes';
	const TAB_CONTENT_INNER_CLASSES  = 'tab_content_inner_classes';
	const TAB_BUTTON_NAME            = 'tab_button_name';
	const TAB_CONTENT_NAME           = 'tab_content_name';

	public function parse_options( array $options ): array {
		$defaults = [
			self::TABS                       => [],
			self::TABS_ID                    => uniqid('tab-'),
			self::CONTAINER_CLASSES          => [],
			self::CONTAINER_ATTRS            => [],
			self::TAB_LIST_CLASSES           => [],
			self::TAB_BUTTON_CLASSES         => [],
			self::TAB_BUTTON_ACTIVE_CLASSES  => [],
			self::TAB_CONTENT_CLASSES        => [],
			self::TAB_CONTENT_ACTIVE_CLASSES => [],
			self::TAB_CONTENT_INNER_CLASSES  => [],
			self::TAB_BUTTON_NAME            => 'title',
			self::TAB_CONTENT_NAME           => 'tab_content',
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			self::TABS                       => $this->options[self::TABS],
			self::TABS_ID                    => $this->options[self::TABS_ID],
			self::CONTAINER_ATTRS            => $this->merge_attrs( [ 'data-js' => 'c-tabs' ], $this->options[self::CONTAINER_ATTRS], true ),
			self::CONTAINER_CLASSES          => $this->merge_classes( [ 'c-tabs	' ], $this->options[self::CONTAINER_CLASSES], true ),
			self::TAB_LIST_CLASSES           => $this->merge_classes( [ 'c-tab__list' ], $this->options[self::TAB_LIST_CLASSES], true ),
			self::TAB_BUTTON_CLASSES         => $this->merge_classes( [ 'c-tab__button' ], $this->options[self::TAB_BUTTON_CLASSES], true ),
			self::TAB_BUTTON_ACTIVE_CLASSES  => $this->merge_classes( [ 'c-tab__button--active' ], $this->options[self::TAB_BUTTON_ACTIVE_CLASSES], true ),
			self::TAB_CONTENT_CLASSES        => $this->merge_classes( [ 'c-tab__content' ], $this->options[self::TAB_CONTENT_CLASSES], true ),
			self::TAB_CONTENT_ACTIVE_CLASSES => $this->merge_classes( [ 'c-tab__content--active' ], $this->options[self::TAB_CONTENT_ACTIVE_CLASSES], true ),
			self::TAB_CONTENT_INNER_CLASSES  => $this->merge_classes( [ 'c-tab__content-inner' ], $this->options[self::TAB_CONTENT_INNER_CLASSES], true ),
			self::TAB_BUTTON_NAME            => $this->options[self::TAB_BUTTON_NAME],
			self::TAB_CONTENT_NAME           => $this->options[self::TAB_CONTENT_NAME],
		];

		return $data;
	}
}