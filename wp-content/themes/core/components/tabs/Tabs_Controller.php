<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\tabs;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Models\Collections\Tab_Collection;
use Tribe\Project\Templates\Models\Tab;

class Tabs_Controller extends Abstract_Controller {

	public const ATTRS              = 'attrs';
	public const CLASSES            = 'classes';
	public const LAYOUT             = 'layout';
	public const LAYOUT_HORIZONTAL  = 'horizontal';
	public const LAYOUT_VERTICAL    = 'vertical';
	public const TABS               = 'tabs';
	public const TAB_BUTTON_CLASSES = 'tab_button_classes';
	public const TAB_PANEL_CLASSES  = 'tab_panel_classes';
	public const TOGGLE_CLASSES     = 'toggle_classes';

	/**
	 * @var string[]
	 */
	private array $attrs;

	/**
	 * @var string[]
	 */
	private array $classes;

	/**
	 * @var string[]
	 */
	private array $tab_button_classes;

	/**
	 * @var mixed[]
	 */
	private array $tab_buttons = [];

	/**
	 * @var string[]
	 */
	private array $tab_panel_classes;

	/**
	 * @var mixed[]
	 */
	private array $tab_panels = [];

	/**
	 * The collection of tabs to render.
	 */
	private Tab_Collection $tabs;

	/**
	 * @var string[]
	 */
	private array $toggle_classes;
	private string $layout;
	private string $tablist_id;

	/**
	 * Controller constructor.
	 *
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs              = (array) $args[ self::ATTRS ];
		$this->classes            = (array) $args[ self::CLASSES ];
		$this->layout             = (string) $args[ self::LAYOUT ];
		$this->tab_button_classes = (array) $args[ self::TAB_BUTTON_CLASSES ];
		$this->tab_panel_classes  = (array) $args[ self::TAB_PANEL_CLASSES ];
		$this->tablist_id         = uniqid( 'c-tabs__tablist--' );
		$this->tabs               = $args[ self::TABS ];
		$this->toggle_classes     = (array) $args[ self::TOGGLE_CLASSES ];

		$this->init_tabs();
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

	public function get_classes(): string {
		$this->classes[] = sprintf( 'c-tabs--layout-%s', $this->layout );

		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		$this->attrs['data-layout'] = $this->layout;

		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_dropdown_classes(): string {
		return Markup_Utils::class_attribute( [ 'c-tabs__tablist-dropdown' ] );
	}

	public function get_dropdown_attrs(): string {
		$attrs = [
			'id'      => $this->tablist_id,
			'data-js' => 'c-tabs__tablist-dropdown',
		];

		return Markup_Utils::concat_attrs( $attrs );
	}

	public function get_tablist_classes(): string {
		return Markup_Utils::class_attribute( [ 'c-tabs__tablist' ] );
	}

	public function get_tablist_attrs(): string {
		return Markup_Utils::concat_attrs( [ 'aria-orientation' => $this->layout, 'role' => 'tablist' ] );
	}

	public function get_tab_buttons(): array {
		return $this->tab_buttons;
	}

	public function get_tab_panels(): array {
		return $this->tab_panels;
	}

	protected function defaults(): array {
		return [
			self::ATTRS              => [],
			self::CLASSES            => [],
			self::LAYOUT             => self::LAYOUT_HORIZONTAL,
			self::TABS               => new Tab_Collection(),
			self::TAB_BUTTON_CLASSES => [],
			self::TOGGLE_CLASSES     => [],
		];
	}

	protected function required(): array {
		return [
			self::ATTRS              => [ 'data-js' => 'c-tabs' ],
			self::CLASSES            => [ 'c-tabs' ],
			self::TAB_BUTTON_CLASSES => [ 'c-tabs__tab' ],
			self::TAB_PANEL_CLASSES  => [ 'c-tabs__tabpanel' ],
			self::TOGGLE_CLASSES     => [ 'c-tabs__tablist-toggle' ],
		];
	}

	/**
	 * Loop through the tabs provided & set up the tab button and
	 * tab panel components arguments for each.
	 */
	private function init_tabs(): void {
		foreach ( $this->tabs as $index => $tab ) {
			$tab_id              = uniqid();
			$this->tab_buttons[] = $this->get_tab_button_args( $tab, $tab_id, $index );
			$this->tab_panels[]  = $this->get_tab_panel_args( $tab, $tab_id, $index );
		}
	}

	/**
	 * Return arguments for an individual tab list "tab" button.
	 *
	 * @param \Tribe\Project\Templates\Models\Tab $tab
	 * @param string    $tab_id
	 * @param int       $index
	 *
	 * @return array
	 */
	private function get_tab_button_args( Tab $tab, string $tab_id, int $index ): array {
		$args = [
			'content' => $tab->tab_label ?: sprintf( __( 'Tab %d', 'tribe' ), $index + 1 ),
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
	 * @param \Tribe\Project\Templates\Models\Tab $tab
	 * @param string    $tab_id
	 * @param int       $index
	 *
	 * @return array
	 */
	private function get_tab_panel_args( Tab $tab, string $tab_id, int $index ): array {
		$args = [
			'content' => $tab->tab_content ?: '&nbsp;', // If the tab content is empty, the container component won't render.
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

}
