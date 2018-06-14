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
			self::CONTAINER_CLASSES          => [ 'c-tabs' ],
			self::CONTAINER_ATTRS            => [ 'data-js' => 'c-tabs' ],
			self::TAB_LIST_CLASSES           => [ 'c-tab__list' ],
			self::TAB_BUTTON_CLASSES         => [ 'c-tab__button' ],
			self::TAB_BUTTON_ACTIVE_CLASSES  => [ 'c-tab__button--active' ],
			self::TAB_CONTENT_CLASSES        => [ 'c-tab__content', 't-content' ],
			self::TAB_CONTENT_ACTIVE_CLASSES => [ 'c-tab__content--active' ],
			self::TAB_CONTENT_INNER_CLASSES  => [ 'c-tab__content-inner' ],
			self::TAB_BUTTON_NAME            => 'title',
			self::TAB_CONTENT_NAME           => 'tab_content',
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			self::TABS                       => $this->options[self::TABS],
			self::TABS_ID                    => $this->options[self::TABS_ID],
			self::CONTAINER_ATTRS            => $this->merge_attrs( [], $this->options[self::CONTAINER_ATTRS], true ),
			self::CONTAINER_CLASSES          => $this->merge_classes( [], $this->options[self::CONTAINER_CLASSES], true ),
			self::TAB_LIST_CLASSES           => $this->merge_classes( [], $this->options[self::TAB_LIST_CLASSES], true ),
			self::TAB_BUTTON_CLASSES         => $this->merge_classes( [], $this->options[self::TAB_BUTTON_CLASSES], true ),
			self::TAB_BUTTON_ACTIVE_CLASSES  => $this->merge_classes( [], $this->options[self::TAB_BUTTON_ACTIVE_CLASSES], true ),
			self::TAB_CONTENT_CLASSES        => $this->merge_classes( [], $this->options[self::TAB_CONTENT_CLASSES], true ),
			self::TAB_CONTENT_ACTIVE_CLASSES => $this->merge_classes( [], $this->options[self::TAB_CONTENT_ACTIVE_CLASSES], true ),
			self::TAB_CONTENT_INNER_CLASSES  => $this->merge_classes( [], $this->options[self::TAB_CONTENT_INNER_CLASSES], true ),
			self::TAB_BUTTON_NAME            => $this->options[self::TAB_BUTTON_NAME],
			self::TAB_CONTENT_NAME           => $this->options[self::TAB_CONTENT_NAME],
		];

		return $data;
	}
}