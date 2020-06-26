<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Block;

use Tribe\Project\Blocks\Types\Accordion as Accordion_Block;
use Tribe\Project\Blocks\Types\Support\Accordion_Section as Accordion_Section_Block;
use Tribe\Project\Templates\Components\Accordion as Accordion_Component;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Panels\Accordion as Container;
use Tribe\Project\Templates\Components\Text;

class Accordion extends Block_Controller {

	public function render( string $path = '' ): string {
		return $this->factory->get( Container::class, [
			Container::LAYOUT            => $this->get_layout(),
			Container::CONTAINER_CLASSES => $this->get_container_classes(),
			Container::HEADER            => $this->get_header(),
			Container::CONTENT           => $this->get_accordion( $this->attributes ),
		] )->render();
	}

	private function get_layout(): string {
		return $this->attributes[ Accordion_Block::LAYOUT ] ?? Accordion_Block::LAYOUT_STACKED;
	}

	private function get_container_classes(): array {
		$classes = [];

		if ( $this->get_layout() === Accordion_Block::LAYOUT_STACKED ) {
			$classes = [
				'l-sink',
				'l-sink--double'
			];
		}

		return $classes;
	}

	protected function get_header(): string {
		$title       = $this->get_title();
		$description = $this->get_description();

		if ( empty( $title ) && empty( $description ) ) {
			return '';
		}

		return $this->factory->get( Content_Block::class, [
			Content_Block::TAG     => 'header',
			Content_Block::CLASSES => [ 'accordion__header' ],
			Content_Block::TITLE   => $title,
			Content_Block::TEXT    => $description,
		] )->render();
	}

	private function get_title(): string {
		if ( empty($this->attributes[ Accordion_Block::TITLE ] ) ) {
			return '';
		}

		return $this->factory->get( Text::class, [
			Text::TAG     => 'h2',
			Text::CLASSES => [ 'accordion__title', 'h3' ],
			Text::TEXT    => $this->attributes[ Accordion_Block::TITLE ],
		] )->render();
	}

	private function get_description(): string {
		if ( empty($this->attributes[ Accordion_Block::DESCRIPTION ] ) ) {
			return '';
		}

		return $this->factory->get( Text::class, [
			Text::CLASSES => [ 'accordion__description', 't-sink', 's-sink' ],
			Text::TEXT    => $this->attributes[ Accordion_Block::DESCRIPTION ],
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
}
