<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Accordion;

use Tribe\Project\Blocks\Types\Accordion\Accordion as Accordion_Block;
use Tribe\Project\Controllers\Blocks\Block_Controller;
use Tribe\Project\Blocks\Types\Accordion\Support\Accordion_Section as Accordion_Section_Block;
use Tribe\Project\Templates\Components\Accordion as Accordion_Component;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Blocks\Accordion as Container;
use Tribe\Project\Templates\Components\Controller;

class Controller extends Block_Controller {

	public function render( $attributes, $content, $block_type ) {
		$this->attributes = $attributes;
		$this->content    = $content;
		$this->block_type = $block_type;

		$args = [
			Container::LAYOUT            => $this->get_layout(),
			Container::CONTAINER_CLASSES => $this->get_container_classes(),
			Container::HEADER            => $this->get_header(),
			Container::CONTENT           => $this->get_accordion( $this->attributes ),
		];

		$this->render_component( 'blocks/accordion/Accordion.php', $args );
	}

	private function get_layout(): string {
		return $this->attributes[ Accordion_Block::LAYOUT ] ?? Accordion_Block::LAYOUT_STACKED;
	}

	private function get_container_classes(): array {
		$classes = [
			'b-accordion__container',
			'l-container'
		];

		if ( $this->get_layout() === Accordion_Block::LAYOUT_STACKED ) {
			$classes = [
				'l-sink',
				'l-sink--double'
			];
		}

		return $classes;
	}

	protected function get_header(): array {
		if ( empty( $this->attributes[ Accordion_Block::TITLE ] ) && empty( $this->attributes[ Accordion_Block::DESCRIPTION ] ) ) {
			return [];
		}

		return [
			Content_Block::TAG     => 'header',
			Content_Block::CLASSES => [ 'b-accordion__header' ],
			Content_Block::TITLE   => $this->get_title(),
			Content_Block::TEXT    => $this->get_description(),
		];
	}

	private function get_title(): array {
		return  [
			Controller::TAG     => 'h2',
			Controller::CLASSES => [ 'b-accordion__title', 'h3' ],
			Controller::TEXT    => $this->attributes[ Accordion_Block::TITLE ] ?? '',
		];
	}

	private function get_description(): array {
		return [
			Controller::CLASSES => [ 'b-accordion__description', 't-sink', 's-sink' ],
			Controller::TEXT    => $this->attributes[ Accordion_Block::DESCRIPTION ] ?? '',
		];
	}

	protected function get_accordion( array $attributes ): array {
		return [
			Accordion_Component::ROWS => $this->get_rows( $attributes ),
		];
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
}
