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
class Controller extends Abstract_Controller {
	public const LAYOUT_HORIZONTAL = 'horizontal';
	public const LAYOUT_VERTICAL   = 'vertical';

	/**
	 * @var Tab_Model[] The collection of tabs to render. Each item should be a \Tribe\Project\Templates\Models\Tab object.
	 */
	private $tabs;

	/**
	 * @var array Classes applied to the component.
	 */
	private $classes;

	/**
	 * @var array Attributes applied to the component.
	 */
	private $attrs;

	/**
	 * @var string The layout applied to this instance of the component. Options are `horizontal` or `vertical`.
	 */
	private $layout;

	/**
	 * @var array Classes applied to the tabs toggle button. (Only applicable to the vertical layout.)
	 */
	private $toggle_classes;

	/**
	 * @var array Classes applied to each tab's trigger button.
	 */
	private $tab_button_classes;

	/**
	 * @var array Classes applied to each tab's tabpanel/content.
	 */
	private $tab_panel_classes;

	/**
	 * @var string Unique ID applied to this instance's tablist show/hide toggle.
	 */
	private $tablist_id;

	/**
	 * @var array The array of tab buttons/triggers to be rendered.
	 */
	private $tab_buttons = [];

	/**
	 * @var array The array of tabpanels/tab content to be rendered.
	 */
	private $tab_panels = [];

	/**
	 * Controller constructor.
	 *
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args = wp_parse_args( $args, $this->default() );

		foreach ( $this->required() as $key => $value ) {
			$args[ $key ] = array_merge( $args[ $key ], $value );
		}

		$this->tabs               = (array) $args['tabs'];
		$this->classes            = (array) $args['classes'];
		$this->attrs              = (array) $args['attrs'];
		$this->layout             = $args['layout'];
		$this->toggle_classes     = (array) $args['toggle_classes'];
		$this->tab_button_classes = (array) $args['tab_button_classes'];
		$this->tab_panel_classes  = (array) $args['tab_panel_classes'];
		$this->tablist_id         = uniqid( 'c-tabs__tablist--' );

		$this->init_tabs();
	}

	/**
	 * @return array
	 */
	protected function default(): array {
		return [
			'tabs'               => [],
			'classes'            => [],
			'attrs'              => [],
			'layout'             => self::LAYOUT_HORIZONTAL,
			'toggle_classes'     => [],
			'tab_button_classes' => [],
			'tab_panel_classes'  => [ 's-sink', 't-sink' ],
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [
			'classes'            => [ 'c-tabs' ],
			'attrs'              => [ 'data-js' => 'c-tabs' ],
			'toggle_classes'     => [ 'c-tabs__tablist-toggle' ],
			'tab_button_classes' => [ 'c-tabs__tab' ],
			'tab_panel_classes'  => [ 'c-tabs__tabpanel' ],
		];
	}

	/**
	 * Loop through the tabs provided and pre-render the tab button and tab panel components as strings.
	 */
	private function init_tabs() {
		foreach ( $this->tabs as $index => $tab ) {
			$tab_id              = uniqid();
			$this->tab_buttons[] = $this->get_tab_button( $tab, $tab_id, $index );
			$this->tab_panels[]  = $this->get_tab_panel( $tab, $tab_id, $index );
		}
	}

	/**
	 * Render the tablist drop-down toggle for the vertical tabs layout.
	 *
	 * @return string
	 */
	public function get_dropdown_toggle(): string {
		if ( $this->layout === self::LAYOUT_HORIZONTAL ) {
			return '';
		}

		$args = [
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

		return tribe_template_part( 'components/button/button', null, $args );
	}

	/**
	 * Renders and individual tab list "tab" button.
	 *
	 * @param Tab_Model $tab
	 * @param string    $tab_id
	 * @param int       $index
	 *
	 * @return string
	 */
	private function get_tab_button( Tab_Model $tab, string $tab_id, int $index ): string {
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

		return tribe_template_part( 'components/button/button', null, $args );
	}

	/**
	 * Renders an individual tab content, the "tabpanel".
	 *
	 * @param Tab_Model $tab
	 * @param string    $tab_id
	 * @param int       $index
	 *
	 * @return string
	 */
	private function get_tab_panel( Tab_Model $tab, string $tab_id, int $index ): string {
		$args = [
			'content' => $tab->content ?? '',
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

		return tribe_template_part( 'components/container/container', null, $args );
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
		return Markup_Utils::concat_attrs( [ 'id' => $this->tablist_id ] );
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
