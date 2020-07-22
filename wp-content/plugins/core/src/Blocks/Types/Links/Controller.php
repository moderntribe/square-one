<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Links;

use Tribe\Project\Blocks\Types\Links\Links as Links_Block;
use Tribe\Project\Controllers\Blocks\Block_Controller;
use Tribe\Project\Blocks\Types\Links\Support\Link as Link_Block;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Panels\Links as Container;
use Tribe\Project\Templates\Components\Text;
use Tribe\Project\Templates\Components\Link;

class Controller extends Block_Controller {

	public function render( $attributes, $content, $block_type ) {
		$this->attributes = $attributes;
		$this->content    = $content;
		$this->block_type = $block_type;

		$args = [
			Container::LAYOUT            => $this->get_layout(),
			Container::CONTAINER_CLASSES => $this->get_container_classes(),
			Container::HEADER            => $this->get_header(),
			Container::LINKS             => $this->get_links( $this->attributes ),
			Container::LINKS_TITLE       => $this->get_links_title(),
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
			Content_Block::LAYOUT  => Content_Block::LAYOUT_STACKED,
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

	private function get_links_title(): array {
		if ( empty( $this->attributes[ Links_Block::LINKS_TITLE ] ) ) {
			return [];
		}

		return  [
			Text::TAG     => 'h3',
			Text::CLASSES => [ 'links__list-title', 'h5' ],
			Text::TEXT    => $this->attributes[ Links_Block::LINKS_TITLE ] ?? '',
		];
	}

	protected function get_links( array $attributes ): array {
		$rows = array_filter( $attributes[ Links_Block::LINKS ], function( $row ) {
			return array_key_exists( Link_Block::LINK, $row );
		} );

		if ( empty( $rows ) ) {
			return [];
		}

		return array_map( function ( $row ) {
			return [
				Link::URL     => $row[ Link_Block::LINK ]['url'] ?? '',
				Link::CONTENT => $row[ Link_Block::LINK ]['text'] ?? $row[ Link_Block::LINK ]['url'],
				Link::TARGET  => $row[ Link_Block::LINK ]['target'] ?? '',
				Link::CLASSES => [ 'links__list-link' ],
			];
		}, $rows );
	}
}
