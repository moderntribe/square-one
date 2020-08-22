<?php

namespace Tribe\Project\Templates\Components\tabs;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Models\Tab as Tab_Model;

/**
 * Class Controller
 *
 * @package Tribe\Project\Templates\Components\tabs
 */
class Tabs_Controller extends Abstract_Controller {
	public const TABS               = 'tabs';
	public const CLASSES            = 'classes';
	public const ATTRS              = 'attrs';
	public const LAYOUT             = 'layout';
	public const TOGGLE_CLASSES     = 'toggle_classes';
	public const TAB_BUTTON_CLASSES = 'tab_button_classes';
	public const TAB_PANEL_CLASSES  = 'tab_panel_classes';
	public const LAYOUT_HORIZONTAL  = 'horizontal';
	public const LAYOUT_VERTICAL    = 'vertical';

	/**
	 * @var Tab_Model[] The collection of tabs to render. Each item should be a \Tribe\Project\Templates\Models\Tab object.
	 */
	private array $tabs;
	private array $classes;
	private array $attrs;
	private string $layout;
	private array $toggle_classes;
	private array $tab_button_classes;
	private array $tab_panel_classes;
	private string $tablist_id;
	private array $tab_buttons = [];
	private array $tab_panels  = [];

	/**
	 * Controller constructor.
	 *
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->tabs               = (array) $args[ self::TABS ];
		$this->classes            = (array) $args[ self::CLASSES ];
		$this->attrs              = (array) $args[ self::ATTRS ];
		$this->layout             = (string) $args[ self::LAYOUT ];
		$this->toggle_classes     = (array) $args[ self::TOGGLE_CLASSES ];
		$this->tab_button_classes = (array) $args[ self::TAB_BUTTON_CLASSES ];
		$this->tab_panel_classes  = (array) $args[ self::TAB_PANEL_CLASSES ];
		$this->tablist_id         = uniqid( 'c-tabs__tablist--' );

		$this->init_tabs();
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			self::TABS               => [],
			self::CLASSES            => [],
			self::ATTRS              => [],
			self::LAYOUT             => self::LAYOUT_HORIZONTAL,
			self::TOGGLE_CLASSES     => [],
			self::TAB_BUTTON_CLASSES => [],
			self::TAB_PANEL_CLASSES  => [ 's-sink', 't-sink' ],
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			self::CLASSES            => [ 'c-tabs' ],
			self::ATTRS              => [ 'data-js' => 'c-tabs' ],
			self::TOGGLE_CLASSES     => [ 'c-tabs__tablist-toggle' ],
			self::TAB_BUTTON_CLASSES => [ 'c-tabs__tab' ],
			self::TAB_PANEL_CLASSES  => [ 'c-tabs__tabpanel' ],
		];
	}

	/**
	 * Loop through the tabs provided setup the tab button and tab panel components arguments for each.
	 */
	private function init_tabs() {
		foreach ( $this->tabs as $index => $tab ) {
			$tab_id              = uniqid();
			$this->tab_buttons[] = $this->get_tab_button_args( $tab, $tab_id, $index );
			$this->tab_panels[]  = $this->get_tab_panel_args( $tab, $tab_id, $index );
		}
	}

	/**
	 * Return arguments for an individual tab list "tab" button.
	 *
	 * @param Tab_Model $tab
	 * @param string    $tab_id
	 * @param int       $index
	 *
	 * @return array
	 */
	private function get_tab_button_args( Tab_Model $tab, string $tab_id, int $index ): array {
		$args = [
			'content' => $tab->label ?: sprintf( __( 'Tab %d', 'tribe' ), $index + 1 ),
			'classes' => $this->tab_button_classes,
			'attrs'   => [
				'id'            => sprintf( 'c-tabs__tab--%s', $tab_id ),
				'aria-controls' => sprintf( 'c-tabs__tabpanel--%s', $tab_id ),
				'aria-selected' => $index === 0 ? 'true' : 'false',
				'data-js'       => 'c-tabs__button',
				'role'          => 'tab',
			],
		];

		if ( $index !== 0 ) {
			$args['attrs']['tabindex'] = '-1';
		}

		return $args;
	}

	/**
	 * Return arguments for an individual tab content, the "tabpanel".
	 *
	 * @param Tab_Model $tab
	 * @param string    $tab_id
	 * @param int       $index
	 *
	 * @return array
	 */
	private function get_tab_panel_args( Tab_Model $tab, string $tab_id, int $index ): array {
		$args = [
			'content' => $tab->content ?: '&nbsp;', // If the tab content is empty, the container component won't render.
			'classes' => $this->tab_panel_classes,
			'attrs'   => [
				'id'              => sprintf( 'c-tabs__tabpanel--%s', $tab_id ),
				'aria-labelledby' => sprintf( 'c-tabs__tab--%s', $tab_id ),
				'role'            => 'tabpanel',
				'tabindex'        => '0',
			],
		];

		if ( $index !== 0 ) {
			// This is an HTML attribute "flag" and does not have a value. Its very presence indicates `true`.
			$args['attrs']['hidden'] = '';
		}

		return $args;
	}

	/**
	 * Return arguments for the tablist drop-down toggle for the vertical tabs layout.
	 *
	 * @return array
	 */
	public function get_dropdown_toggle_args(): array {
		if ( $this->layout === self::LAYOUT_HORIZONTAL ) {
			return [];
		}

		return [
			'content' => $this->tabs[0]->label ?? __( 'Tab 1', 'tribe' ),
			'classes' => $this->toggle_classes,
			'attrs'   => [
				'aria-controls' => $this->tablist_id,
				'aria-expanded' => 'false',
				'aria-haspopup' => 'true',
				'aria-label'    => __( 'Toggle the tab list menu.', 'tribe' ),
				'data-js'       => 'c-tabs__tablist-toggle',
			],
		];
	}

	/**
	 * @return string
	 */
	public function get_classes(): string {
		$this->classes[] = sprintf( 'c-tabs--layout-%s', $this->layout );

		return Markup_Utils::class_attribute( $this->classes );
	}

	/**
	 * @return string
	 */
	public function get_attrs(): string {
		$this->attrs['data-layout'] = $this->layout;

		return Markup_Utils::concat_attrs( $this->attrs );
	}

	/**
	 * @return string
	 */
	public function get_dropdown_classes(): string {
		return Markup_Utils::class_attribute( [ 'c-tabs__tablist-dropdown' ] );
	}

	/**
	 * @return string
	 */
	public function get_dropdown_attrs(): string {
		$attrs = [
			'id'      => $this->tablist_id,
			'data-js' => 'c-tabs__tablist-dropdown',
		];

		return Markup_Utils::concat_attrs( $attrs );
	}

	/**
	 * @return string
	 */
	public function get_tablist_classes(): string {
		return Markup_Utils::class_attribute( [ 'c-tabs__tablist' ] );
	}

	/**
	 * @return string
	 */
	public function get_tablist_attrs(): string {
		return Markup_Utils::concat_attrs( [ 'aria-orientation' => $this->layout, 'role' => 'tablist' ] );
	}

	/**
	 * @return array
	 */
	public function get_tab_buttons(): array {
		return $this->tab_buttons;
	}

	/**
	 * @return array
	 */
	public function get_tab_panels(): array {
		return $this->tab_panels;
	}
}
