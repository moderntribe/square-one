<?php


namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Theme\Util;
use Tribe\Project\Twig\Twig_Template;

class Panel extends Twig_Template {

	protected $panel;
	protected $panel_vars;

	public function get_data(): array {
		$this->panel      = get_the_panel();
		$this->panel_vars = get_panel_vars();
		$data  = [
			'panel'       => $this->panel_vars,
			'depth'       => $this->panel->get_depth(),
			'type'        => $this->panel->get_type_object()->get_id(),
			'index'       => get_nest_index(),
			'children'    => $this->get_children( $this->panel ),
			'object'      => $this->panel,
			'classes'     => $this->get_classes(),
			'title'       => isset( $this->panel_vars['title'] ) ? $this->get_title( $this->panel_vars['title'] ) : false,
			'description' => isset( $this->panel_vars['description'] ) ? $this->get_description( $this->panel_vars['description'] ) : false,
		];

		return $data;
	}

	protected function get_children( \ModularContent\Panel $panel ) {
		$children = array_map( function( \ModularContent\Panel $child ) {
			return $child->render();
		}, $panel->get_children() );

		return $children;
	}

	protected function get_classes(): string {
		$classes = [
			'panel',
			's-wrapper',
			'site-panel',
			sprintf( 'site-panel--%s', $this->panel->get_type_object()->get_id() ),
		];

		return Util::class_attribute( $classes );
	}

	protected function get_title( $field, $classes = [] ) {
		ob_start();

		the_panel_title(
			esc_html( $field ),
			[
				'classes'       => implode( ' ', $classes ),
				'data_name'     => 'title',
				'data_livetext' => true,
			]
		);

		return ob_get_clean();
	}

	protected function get_description( $field, $classes = [ 's-desc', 't-content' ], $data_name = 'description' ) {
		ob_start();

		the_panel_description(
			$field,
			[
				'classes'       => implode( ' ', $classes ),
				'data_name'     => $data_name,
				'data_livetext' => true,
				'data_autop'    => true,
			]
		);

		return ob_get_clean();
	}

	public static function instance() {
		return tribe_project()->container()[ 'twig.templates.content/panels/panel' ];
	}

}
