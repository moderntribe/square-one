<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Links;

// use Tribe\Project\Blocks\Types\Accordion\Accordion as Accordion_Block;
use Tribe\Project\Blocks\Types\Links\Links as Links_Block;
use Tribe\Project\Controllers\Blocks\Block_Controller;
use Tribe\Project\Blocks\Types\Accordion\Support\Accordion_Section as Accordion_Section_Block;
use Tribe\Project\Blocks\Types\Button as Button_Block;
use Tribe\Project\Templates\Components\Accordion as Accordion_Component; // Now Container
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Panels\Links as Container;
use Tribe\Project\Templates\Components\Text;
use Tribe\Project\Templates\Components\Button;

class Controller extends Block_Controller {

	public function render( $attributes, $content, $block_type ) {
		$this->attributes = $attributes;
		$this->content    = $content;
		$this->block_type = $block_type;

		$args = [
			Container::LAYOUT            => $this->get_layout(),
			Container::CONTAINER_CLASSES => $this->get_container_classes(),
			Container::HEADER            => $this->get_header(),
			// Container::CONTENT           => $this->get_links( $this->attributes ),
		];

		$this->render_component( 'panels/links/Links.php', $args );
	}

	private function get_layout(): string {
		return $this->attributes[ Links_Block::LAYOUT ] ?? Links_Block::LAYOUT_STACKED;
	}

	private function get_container_classes(): array {
		$classes = [
			'links__container',
			'l-container'
		];

		if ( $this->get_layout() === Links_Block::LAYOUT_STACKED ) {
			$classes = [
				'l-sink',
				'l-sink--double'
			];
		}

		return $classes;
	}

	protected function get_header(): array {
		if ( empty( $this->attributes[ Links_Block::TITLE ] ) && empty( $this->attributes[ Links_Block::DESCRIPTION ] ) ) {
			return [];
		}

		return [
			Content_Block::TAG     => 'header',
			Content_Block::CLASSES => [ 'links__header' ],
			Content_Block::TITLE   => $this->get_title(),
			Content_Block::TEXT    => $this->get_description(),
		];
	}

	private function get_title(): array {
		return  [
			Text::TAG     => 'h2',
			Text::CLASSES => [ 'links__title', 'h3' ],
			Text::TEXT    => $this->attributes[ Links_Block::TITLE ] ?? '',
		];
	}

	private function get_description(): array {
		return [
			Text::CLASSES => [ 'links__description', 't-sink', 's-sink' ],
			Text::TEXT    => $this->attributes[ Links_Block::DESCRIPTION ] ?? '',
		];
	}

//	protected function get_links( array $attributes ): array {
//		return [
//			Container::ROWS => $this->get_rows( $attributes ),
//		];
//	}
//
//	protected function get_rows( array $attributes ): array {
//		$rows = $attributes[ Links_Block::LINKS ];
//
//		if ( empty( $rows ) ) {
//			return [];
//		}
//
//		return array_map( function ( $row ) {
//			return [
//				// Button::LINK => $row[ Button_Block::LINK ] ?? '',
//				Button::CLASSES => [ 'c-btn', 'c-btn-primary' ],
//				Button::ARIA_LABEL => $row[ Button_Block::ARIA ] ?? '',
//			];
//		}, $rows );
//	}
}
