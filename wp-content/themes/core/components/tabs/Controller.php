<?php

namespace Tribe\Project\Templates\Components\tabs;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\Deferred_Component;

class Controller extends Abstract_Controller {
	public const LAYOUT_HORIZONTAL = 'horizontal';
	public const LAYOUT_VERTICAL   = 'vertical';

	public $tabs;
	public $classes;
	public $attrs;
	public $layout;
	public $toggle_classes;
	public $tab_button_classes;
	public $tab_panel_classes;

	private $tablist_id;
	private $tab_buttons;
	private $tab_panels;

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

		$this->tablist_id = uniqid( 'c-tabs__tablist--' );
		$this->init_tabs();
	}

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

	protected function required(): array {
		return [
			'classes'            => [ 'c-tabs' ],
			'attrs'              => [ 'data-js' => 'c-tabs' ],
			'toggle_classes'     => [ 'c-tabs__tablist-toggle' ],
			'tab_button_classes' => [ 'c-tabs__tabbutton' ],
			'tab_panel_classes'  => [ 'c-tabs__tabpanel' ],
		];
	}

	private function init_tabs() {
		foreach ( $this->tabs as $index => $tab ) {
			$tab_id              = uniqid();
			$this->tab_buttons[] = $this->get_tab_button( $tab, $tab_id, $index );
			$this->tab_panels[]  = $this->get_tab_panel( $tab, $tab_id, $index );
		}
	}

	public function get_dropdown_toggle(): Deferred_Component {
		$args = [
			'content' => $this->tabs[0]['label'] ?? __( 'Tab 1', 'tribe' ),
			'classes' => $this->toggle_classes,
			'attrs'   => [
				'aria-controls' => $this->tablist_id,
				'aria-expanded' => 'false',
				'aria-haspopup' => 'true',
				'aria-label'    => __( 'Toggle the tab list menu.', 'tribe' ),
				'data-js'       => 'c-tabs__tablist-toggle',
			],
		];

		return defer_template_part( 'components/button/button', null, $args );
	}

	private function get_tab_button( array $tab, string $tab_id, int $index ): Deferred_Component {
		$args = [
			'content' => $tab['label'] ?? sprintf( __( 'Tab %d', 'tribe' ), $index + 1 ),
			'classes' => $this->tab_button_classes,
			'attrs'   => [
				'id'            => sprintf( 'c-tabs__tabbutton--%s', $tab_id ),
				'aria-controls' => sprintf( 'c-tabs__tabpanel--%s', $tab_id ),
				'aria-selected' => $index === 0 ? 'true' : 'false',
				'data-js'       => 'c-tabs__button',
				'role'          => 'tab',
			],
		];

		if ( $index !== 0 ) {
			$args['attrs']['tabindex'] = '-1';
		}

		return defer_template_part( 'components/button/button', null, $args );
	}

	private function get_tab_panel( array $tab, string $tab_id, int $index ): Deferred_Component {
		$args = [
			'content' => $tab['content'],
			'classes' => $this->tab_panel_classes,
			'attrs'   => [
				'id'              => sprintf( 'c-tabs__tabpanel--%s', $tab_id ),
				'aria-labelledby' => sprintf( 'c-tabs__tabbutton--%s', $tab_id ),
				'role'            => 'tabpanel',
				'tabindex'        => '0',
			],
		];

		if ( $index !== 0 ) {
			// This is an HTML attribute "flag" and does not have a value. Its very presence indicates `true`.
			$args['attrs']['hidden'] = '';
		}

		return defer_template_part( 'components/container/container', null, $args );
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
		return Markup_Utils::concat_attrs( [ 'id' => $this->tablist_id ] );
	}

	public function get_tablist_classes(): string {
		return Markup_Utils::class_attribute( [ 'c-tabs__tablist' ] );
	}

	public function get_tablist_attrs(): string {
		return Markup_Utils::concat_attrs( [ 'aria-orientation' => $this->layout ] );
	}

	public function get_tab_buttons(): array {
		return $this->tab_buttons;
	}

	public function get_tab_panels(): array {
		return $this->tab_panels;
	}
}
