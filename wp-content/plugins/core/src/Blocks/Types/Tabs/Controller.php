<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Tabs;

use Tribe\Project\Blocks\Types\Tabs\Tabs as Tabs_Block;
use Tribe\Project\Controllers\Blocks\Block_Controller;
use Tribe\Project\Blocks\Types\Tabs\Support\Tabs_Section as Tabs_Section_Block;
use Tribe\Project\Templates\Components\Tabs as Tabs_Component;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Panels\Tabs as Container;
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
			Container::CONTENT           => $this->get_tabs( $this->attributes ),
		];

		$this->render_component( 'panels/tabs/Tabs.php', $args );
	}

	private function get_layout(): string {
		return $this->attributes[ Tabs_Block::LAYOUT ] ?? Tabs_Block::LAYOUT_HORIZONTAL;
	}

	private function get_container_classes(): array {
		$classes = [ 'tabs__container' ];

		return $classes;
	}

	protected function get_header(): array {
		if ( empty( $this->attributes[ Tabs_Block::TITLE ] ) && empty( $this->attributes[ Tabs_Block::DESCRIPTION ] ) ) {
			return [];
		}

		return [
			Content_Block::TAG     => 'header',
			Content_Block::CLASSES => [ 'tabs__header' ],
			Content_Block::TITLE   => $this->get_title(),
			Content_Block::TEXT    => $this->get_description(),
		];
	}

	private function get_title(): array {
		return  [
			Text::TAG     => 'h2',
			Text::CLASSES => [ 'tabs__title', 'h3' ],
			Text::TEXT    => $this->attributes[ Tabs_Block::TITLE ] ?? '',
		];
	}

	private function get_description(): array {
		return [
			Text::CLASSES => [ 'tabs__description', 't-sink', 's-sink' ],
			Text::TEXT    => $this->attributes[ Tabs_Block::DESCRIPTION ] ?? '',
		];
	}

	protected function get_tabs( array $attributes ): array {
		return [
			Tabs_Component::TABS => $this->get_tab( $attributes ),
		];
	}

	protected function get_tab( array $attributes ): array {
		$tabs = $attributes[ Tabs_Block::TABS ];

		if ( empty( $tabs ) ) {
			return [];
		}

		return array_map( function ( $tab ) {
			return [
				'content'  => implode( "\n", wp_list_pluck( $tab[ Tabs_Section_Block::CONTENT ] ?? [], 'content' ) ),
				'tab_text' => $tab[ Tabs_Section_Block::HEADER ] ?? '',
			];
		}, $tabs );
	}
}
