<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Panel;

use Tribe\Project\Templates\Abstract_Controller;
use Tribe\Project\Templates\Components\Panels\Default_Panel;
use Tribe\Project\Templates\Components\Panels\Panel as Panel_Context;

class Panel extends Abstract_Controller {
	public function render( string $path = '' ): string {
		$panel      = get_the_panel();
		$panel_vars = get_panel_vars();

		return $this->factory->get( Panel_Context::class, $this->get_panel_context_options( $panel, $panel_vars ) )->render( $path );
	}

	protected function get_panel_context_options( \ModularContent\Panel $panel, $panel_vars ): array {
		return [
			Panel_Context::DEPTH       => $this->get_depth( $panel ),
			Panel_Context::TYPE        => $this->get_type( $panel ),
			Panel_Context::INDEX       => get_nest_index(),
			Panel_Context::CHILDREN    => $this->get_children( $panel ),
			Panel_Context::CLASSES     => $this->get_classes( $panel ),
			Panel_Context::TITLE       => $this->get_title( $panel_vars ),
			Panel_Context::DESCRIPTION => $this->get_description( $panel_vars ),
			Panel_Context::WRAPPER     => $this->use_wrapper( $panel ),
			Panel_Context::CONTENT     => $this->render_content( $panel, $panel_vars ),
		];
	}

	protected function get_depth( \ModularContent\Panel $panel ): int {
		return (int) $panel->get_depth();
	}

	protected function get_type( \ModularContent\Panel $panel ): string {
		return $panel->get_type_object()->get_id();
	}

	protected function get_children( \ModularContent\Panel $panel ): array {
		return array_map( function ( \ModularContent\Panel $child ) {
			return $child->render();
		}, $panel->get_children() );
	}

	protected function get_classes( \ModularContent\Panel $panel ): array {
		return [ sprintf( 'site-panel--%s', $panel->get_type_object()->get_id() ) ];
	}

	protected function get_title( array $panel_vars ): string {
		if ( ! array_key_exists( 'title', $panel_vars ) ) {
			return '';
		}
		ob_start();

		the_panel_title(
			esc_html( $panel_vars['title'] ),
			[
				'classes'       => implode( ' ', $this->get_title_classes( $panel_vars ) ),
				'data_name'     => 'title',
				'data_livetext' => true,
			]
		);

		return ob_get_clean();
	}

	protected function get_title_classes( array $panel_vars ): array {
		return [ 's-title', 'h2' ];
	}

	protected function get_description( array $panel_vars ): string {
		if ( ! array_key_exists( 'description', $panel_vars ) ) {
			return '';
		}

		ob_start();

		the_panel_description(
			$panel_vars['description'],
			[
				'classes'       => implode( ' ', $this->get_description_classes( $panel_vars ) ),
				'data_name'     => 'description',
				'data_livetext' => true,
				'data_autop'    => true,
			]
		);

		return ob_get_clean();
	}

	protected function get_description_classes( array $panel_vars ): array {
		return [ 's-desc', 's-sink t-sink' ];
	}

	protected function use_wrapper( \ModularContent\Panel $panel ): bool {
		return true;
	}

	protected function render_content( \ModularContent\Panel $panel, array $panel_vars ): string {
		return $this->factory->get( Default_Panel::class, [
			Default_Panel::PANEL => $panel_vars,
		] )->render();
	}

}
