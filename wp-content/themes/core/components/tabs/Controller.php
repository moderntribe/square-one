<?php

namespace Tribe\Project\Templates\Components\tabs;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Controller extends Abstract_Controller {
	public $tabs;
	public $classes;
	public $attrs;
	public $layout;
	public $toggle_classes;
	public $button_classes;
	public $content_classes;

	private $tablist_id;
	private $buttons;
	private $tabpanels;

	public function __construct( array $args = [] ) {
		$args = wp_parse_args( $args, $this->default() );

		foreach ( $this->required() as $key => $value ) {
			$args[$key] = array_merge( $args[$key], $value );
		}

		$this->tabs            = (array) $args['tabs'];
		$this->classes         = (array) $args['classes'];
		$this->attrs           = (array) $args['attrs'];
		$this->layout          = $args['layout'];
		$this->toggle_classes  = (array) $args['toggle_classes'];
		$this->button_classes  = (array) $args['button_classes'];
		$this->content_classes = (array) $args['content_classes'];

		$this->tablist_id = uniqid( 'c-tabs__tablist-' );
		$this->init_tabs();
	}

	protected function default(): array {
		return [
			'tabs'            => [],
			'classes'         => [],
			'attrs'           => [],
			'layout'          => 'horizontal',
			'toggle_classes'  => [],
			'button_classes'  => [],
			'content_classes' => [ 's-sink', 't-sink' ],
		];
	}

	protected function required(): array {
		return [
			'classes'         => [ 'c-tabs' ],
			'attrs'           => [ 'data-js' => 'c-tabs' ],
			'toggle_classes'  => [ 'c-tabs__tablist-toggle' ],
			'button_classes'  => [ 'c-tabs__button' ],
			'content_classes' => [ 'c-tabs__content' ],
		];
	}

	private function init_tabs() {
		foreach ( $this->tabs as $index => $tab ) {
			$tab_id            = uniqid( 'c-tabs-' );
			$this->buttons[]   = $this->get_tab_button( $tab, $tab_id, $index );
			$this->tabpanels[] = $this->get_tab_panel( $tab, $tab_id, $index );
		}
	}

	private function get_tab_panel(array $tab, string $tab_id, int $index): array {
		$args = [
			'content' => $tab['content'],
			'classes' => $this->content_classes,
			'attrs'   => [
				'aria-labelledby' => sprintf( '%s__tab', $tab_id ),
				'id'              => sprintf( sprintf( '%s__tabpanel', $tab_id ) ),
				'tabindex'        => '0',
			],
		];

		if ( $index !== 0 ) {
			$args['attrs']['hidden'] = ''; // This is an HTML attribute "flag" and does not have a value. It's very presence indicates "true".
		}

		return $args;
	}

	private function get_tab_button(array $tab, string $tab_id, int $index): array {
		$args = [
			'content' => $tab['label'] ?? sprintf( __( 'Tab %d', 'tribe'), $index + 1 ),
			'classes' => $this->button_classes,
			'attrs'   => [
				'aria-controls' => sprintf( sprintf( '%s__tabpanel', $tab_id ) ),
				'aria-selected' => $index === 0 ? 'true' : 'false',
				'data-js'       => 'c-tabs__button',
				'id'            => sprintf( '%s__tab', $tab_id ),
				'role'          => 'tab',
			],
		];

		if ( $index !== 0 ) {
			$args['attrs']['tabindex'] = '-1';
		}

		return $args;
	}

	public function get_toggle(): string {
		if ( $this->layout === 'horizontal' ) {
			return '';
		}

		$args = [
			'content' => $this->tabs[0]['label'] ?? __('Tab 1', 'tribe'),
			'classes' => $this->toggle_classes,
			'attrs'   => [
				'aria-controls' => $this->tablist_id,
				'aria-expanded' => 'false',
				'aria-haspopup' => 'true',
				'aria-label'    => __('Toggle the tab list menu.', 'tribe'),
				'data-js'       => 'c-tabs__tablist-toggle',
			],
		];

		// TODO: Convert to defer_template_part()
		ob_start();
		get_template_part( 'components/button/button', null, $args );
		return ob_get_clean();
	}

	public function get_classes(): string {
		$this->classes[] = sprintf( 'c-tabs--layout-%s', $this->layout );
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_dropdown_classes(): string {
		return Markup_Utils::class_attribute( ['c-tabs__tablist-dropdown'] );
	}

	public function get_dropdown_attrs(): string {
		return Markup_Utils::class_attribute( ['id' => $this->tablist_id ] );
	}

	public function get_tablist_classes(): string {
		return Markup_Utils::class_attribute( ['c-tabs__tablist'] );
	}

	public function get_tablist_attrs(): string {
		return Markup_Utils::class_attribute( ['aria-orientation' => $this->layout ] );
	}

	public function get_buttons(): array {
		return $this->buttons;
	}

	public function get_tabs(): array {
		return $this->tabpanels;
	}
}
