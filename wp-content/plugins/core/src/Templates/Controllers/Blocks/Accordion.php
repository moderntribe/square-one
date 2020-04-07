<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Blocks;

use Tribe\Project\Blocks\Types\Accordion as Accordion_Block;
use Tribe\Project\Blocks\Types\Accordion_Section as Accordion_Section_Block;
use Tribe\Project\Templates\Components\Accordion as Accordion_Component;
use Tribe\Project\Templates\Components\Panels\Accordion as Accordion_Context;

class Accordion extends Block_Controller {

	public function render( string $path = '' ): string {
		return $this->factory->get( Accordion_Context::class, [
			Accordion_Context::LAYOUT      => $attributes[ Accordion_Block::LAYOUT ] ?? Accordion_Block::LAYOUT_STACKED,
			Accordion_Context::ACCORDION   => $this->get_accordion( $this->attributes ),
			Accordion_Context::GRID_CASSES => $this->get_grid_classes( $this->attributes ),
		] )->render();
	}

	protected function get_accordion( array $attributes ): string {
		$options = [
			Accordion_Component::ROWS => $this->get_rows( $attributes ),
		];

		$accordion = $this->factory->get( Accordion_Component::class, $options );

		return $accordion->render();
	}

	protected function get_rows( array $attributes ): array {
		$rows = $attributes[ Accordion_Block::ACCORDION ];

		if ( empty( $rows ) ) {
			return [];
		}

		return array_map( function ( $row ) {
			$header_id  = uniqid( 'accordion-header-' );
			$content_id = uniqid( 'accordion-content-' );

			return [
				'header_id'   => $header_id,
				'content_id'  => $content_id,
				'header_text' => $row[ Accordion_Section_Block::HEADER ] ?? '',
				'content'     => implode( "\n", wp_list_pluck( $row[ Accordion_Section_Block::CONTENT ] ?? [], 'content' ) ),
			];
		}, $rows );
	}


	protected function get_grid_classes( array $attributes ): array {
		$classes = [ 'g-row--vertical-center' ];

		if ( ! empty( $attributes[ Accordion_Block::LAYOUT ] ) && $attributes[ Accordion_Block::LAYOUT ] !== Accordion_Block::LAYOUT_INLINE ) {
			$classes[] = 'g-row--col-2--min-full';
		}

		return $classes;
	}
}
