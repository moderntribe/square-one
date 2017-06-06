<?php


namespace Tribe\Project\Templates\Content\Panels;


use Tribe\Project\Twig\Twig_Template;

class Panel extends Twig_Template {
	public function get_data(): array {
		$panel = get_the_panel();
		$data  = [
			'panel'    => get_panel_vars(),
			'depth'    => $panel->get_depth(),
			'type'     => $panel->get_type_object()->get_id(),
			'children' => $this->get_children( $panel ),
			'object'   => $panel,
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