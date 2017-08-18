<?php


namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Twig\Noop_Lazy_Strings;
use Tribe\Project\Twig\Twig_Template;

class Panel extends Twig_Template {

	protected $panel;
	protected $panel_vars;

	public function get_data(): array {
		$this->panel      = get_the_panel();
		$this->panel_vars = get_panel_vars();
		$data  = [
			'panel'    => $this->panel_vars,
			'depth'    => $this->panel->get_depth(),
			'type'     => $this->panel->get_type_object()->get_id(),
			'index'    => get_nest_index(),
			'children' => $this->get_children( $this->panel ),
			'object'   => $this->panel,
			'lang'     => new Noop_Lazy_Strings( 'tribe' ),
			'title'    => isset( $this->panel_vars['title'] ) ? the_panel_title( esc_html( $this->panel_vars['title'] ), 'site-section__title', 'title', true, 0, 0 ) : false,
		];

		return $data;
	}

	protected function get_children( \ModularContent\Panel $panel ) {
		$children = array_map( function( \ModularContent\Panel $child ) {
			return $child->render();
		}, $panel->get_children() );

		return $children;
	}

	public static function instance() {
		return tribe_project()->container()[ 'twig.templates.content/panels/panel' ];
	}

}