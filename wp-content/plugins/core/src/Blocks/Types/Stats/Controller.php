<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Stats;

use Tribe\Project\Blocks\Types\Stats\Stats as Stats_Block;
use Tribe\Project\Blocks\Types\Stats\Support\Statistic as Statistic_Block;
use Tribe\Project\Controllers\Blocks\Block_Controller;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Statistic;
use Tribe\Project\Templates\Components\Blocks\Stats as Container;
use Tribe\Project\Templates\Components\Text;

class Controller extends Block_Controller {

	public function render( $attributes, $content, $block_type ) {
		$this->attributes = $attributes;
		$this->content    = $content;
		$this->block_type = $block_type;

		$args = [
			Container::LAYOUT            => $this->get_layout(),
			Container::CONTAINER_CLASSES => $this->get_container_classes(),
			Container::HEADER            => $this->get_header(),
			Container::STATS             => $this->get_stats( $this->attributes ),
		];

		$this->render_component( 'blocks/stats/Stats.php', $args );
	}

	private function get_layout(): string {
		return $this->attributes[ Stats_Block::LAYOUT ] ?? Stats_Block::LAYOUT_INLINE;
	}

	private function get_container_classes(): array {
		$classes = [
			'b-stats__container',
			'l-container',
		];

		return $classes;
	}

	protected function get_header(): array {
		if ( empty( $this->attributes[ Stats_Block::TITLE ] ) && empty( $this->attributes[ Stats_Block::DESCRIPTION ] ) ) {
			return [];
		}

		return [
			Content_Block::TAG     => 'header',
			Content_Block::CLASSES => [ 'b-stats__header' ],
			Content_Block::TITLE   => $this->get_title(),
			Content_Block::TEXT    => $this->get_description(),
			Content_Block::LAYOUT  => Content_Block::LAYOUT_STACKED,
		];
	}

	private function get_title(): array {
		return [
			Text::TAG     => 'h2',
			Text::CLASSES => [ 'b-stats__title', 'h3' ],
			Text::TEXT    => $this->attributes[ Stats_Block::TITLE ] ?? '',
		];
	}

	private function get_description(): array {
		return [
			Text::CLASSES => [ 'b-stats__description', 't-sink', 's-sink' ],
			Text::TEXT    => $this->attributes[ Stats_Block::DESCRIPTION ] ?? '',
		];
	}

	protected function get_stats( array $attributes ): array {
		$rows = array_filter( $attributes[ Stats_Block::STATS ], function ( $row ) {
			return array_key_exists( Statistic_Block::VALUE, $row );
		} );

		if ( empty( $rows ) ) {
			return [];
		}

		return array_map( function ( $row ) {
			return [
				Statistic::VALUE => $this->get_statistic_value( $row ),
				Statistic::LABEL => $this->get_statistic_label( $row ),
			];
		}, $rows );
	}

	private function get_statistic_value( $row ): array {
		return [
			Text::TEXT => $row[ Statistic_Block::VALUE ] ?? '',
		];
	}

	private function get_statistic_label( $row ): array {
		return [
			Text::TEXT => $row[ Statistic_Block::LABEL ] ?? '',
		];
	}
}
