<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Templates\Component_Factory;
use Twig\Environment;

/**
 * Class Tabs
 *
 * @property array[]  $tabs An array of structured tabs, each with the following keys:
 *                          - content - string - The tab content
 *                          - content_id  - string- Unique id for the content
 *                          - tab_id - string - Unique id for the tab
 *                          - tab_text - string - Text for the tab
 *                          - btn_attrs - string[] - Additional attributes for the button, passed
 *                                                   through to the button component
 *                          - content_attrs - string[] - Additional tab inner content attributes
 * @property string   $wrapper_id
 * @property string[] $container_classes
 * @property string[] $container_attrs
 * @property string[] $tab_list_classes
 * @property string[] $tab_list_attrs
 * @property string[] $tab_button_classes
 * @property string   $tab_button_active_class
 * @property string[] $tab_button_attrs
 * @property string[] $tab_button_options
 * @property string[] $tab_content_classes
 * @property string[] $tab_content_attrs
 * @property string   $tab_content_active_class
 * @property string[] $tab_content_inner_classes
 * @property string[] $tab_content_inner_attrs
 */
class Tabs extends Context {
	public const TABS                      = 'tabs';
	public const WRAPPER_ID                = 'wrapper_id';
	public const CONTAINER_CLASSES         = 'container_classes';
	public const CONTAINER_ATTRS           = 'container_attrs';
	public const TAB_LIST_CLASSES          = 'tab_list_classes';
	public const TAB_LIST_ATTRS            = 'tab_list_attrs';
	public const TAB_BUTTON_CLASSES        = 'tab_button_classes';
	public const TAB_BUTTON_ACTIVE_CLASS   = 'tab_button_active_class';
	public const TAB_BUTTON_ATTRS          = 'tab_button_attrs';
	public const TAB_BUTTON_OPTIONS        = 'tab_button_options';
	public const TAB_CONTENT_CLASSES       = 'tab_content_classes';
	public const TAB_CONTENT_ATTRS         = 'tab_content_attrs';
	public const TAB_CONTENT_ACTIVE_CLASS  = 'tab_content_active_class';
	public const TAB_CONTENT_INNER_CLASSES = 'tab_content_inner_classes';
	public const TAB_CONTENT_INNER_ATTRS   = 'tab_content_inner_attrs';

	private const TABLIST_BUTTONS           = 'tablist_buttons';

	protected $path = __DIR__ . '/tabs.twig';

	protected $properties = [
		self::TABS                      => [
			self::DEFAULT => [],
		],
		self::WRAPPER_ID                => [
			self::DEFAULT => '',
		],
		self::CONTAINER_CLASSES         => [
			self::DEFAULT       => [ 'c-tabs' ],
			self::MERGE_CLASSES => [],
		],
		self::CONTAINER_ATTRS           => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [ 'data-js' => 'c-tabs' ],
		],
		self::TAB_LIST_CLASSES          => [
			self::DEFAULT       => [ 'c-tab__list' ],
			self::MERGE_CLASSES => [],
		],
		self::TAB_LIST_ATTRS            => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [ 'role' => 'tablist', 'data-js' => 'c-tablist' ],
		],
		self::TAB_BUTTON_CLASSES        => [
			self::DEFAULT => [ 'c-tab__button' ],
		],
		self::TAB_BUTTON_ACTIVE_CLASS   => [
			self::DEFAULT => 'c-tab__button--active',
		],
		self::TAB_BUTTON_ATTRS          => [
			self::DEFAULT => [],
		],
		self::TAB_BUTTON_OPTIONS        => [
			self::DEFAULT => [],
		],
		self::TAB_CONTENT_CLASSES       => [
			self::DEFAULT       => [ 'c-tab__content', 't-content' ],
			self::MERGE_CLASSES => [],
		],
		self::TAB_CONTENT_ATTRS         => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [ 'role' => 'tabpanel', 'tabindex' => 0 ],
		],
		self::TAB_CONTENT_ACTIVE_CLASS  => [
			self::DEFAULT => 'c-tab__content--active',
		],
		self::TAB_CONTENT_INNER_CLASSES => [
			self::DEFAULT       => [ 'c-tab__content-inner' ],
			self::MERGE_CLASSES => [],
		],
		self::TAB_CONTENT_INNER_ATTRS   => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
	];

	public function __construct( Environment $twig, Component_Factory $factory, array $properties = [] ) {
		if ( empty( $properties[ self::WRAPPER_ID ] ) ) {
			$properties[ self::WRAPPER_ID ] = uniqid( 'tab-', false );
		}
		parent::__construct( $twig, $factory, $properties );
	}

	public function get_data(): array {
		// add attributes to support active tab
		$this->properties[ self::CONTAINER_ATTRS ][ self::MERGE_ATTRIBUTES ]['data-button-active-class']  = $this->tab_button_active_class;
		$this->properties[ self::CONTAINER_ATTRS ][ self::MERGE_ATTRIBUTES ]['data-content-active-class'] = $this->tab_content_active_class;

		$data = parent::get_data();

		$data[ self::TABLIST_BUTTONS ] = $this->get_tablist_buttons();

		return $data;
	}

	/**
	 * Build the buttons for the tabs, delegating to the button component
	 *
	 * @return string[]
	 */
	protected function get_tablist_buttons(): array {
		$tabs    = $this->tabs;
		$buttons = [];
		foreach ( $tabs as $index => $tab ) {
			$tab                       = wp_parse_args( $tab, [
				'content'       => '',
				'content_id'    => '',
				'tab_id'        => '',
				'tab_text'      => '',
				'btn_attrs'     => [],
				'content_attrs' => [],
			] );
			$button_attributes_default = [
				'role'           => 'tab',
				'data-js'        => 'c-tab__button',
				'id'             => esc_attr( $tab['tab_id'] ),
				'aria-controls'  => esc_attr( $tab['content_id'] ),
				'aria-selected'  => ( $index === 0 ) ? 'true' : 'false',
				'data-row-index' => $index,
			];
			$button_attributes         = array_merge( $button_attributes_default, $this->tab_button_attrs, $tab['btn_attrs'] );
			$btn_classes               = $this->tab_button_classes;
			if ( $index === 0 ) {
				$btn_classes[] = $this->tab_button_active_class;
			}
			$options   = [
				Button::TEXT    => $tab['tab_text'],
				Button::CLASSES => $btn_classes,
				Button::ATTRS   => $button_attributes,
			];
			$options   = wp_parse_args( $options, $tab['btn_options'] );
			$buttons[] = $this->factory->get( Button::class, $options )->render();
		}

		return $buttons;
	}
}
