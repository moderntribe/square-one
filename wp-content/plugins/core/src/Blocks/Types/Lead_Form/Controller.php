<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Lead_Form;

use Tribe\Project\Blocks\Types\Lead_Form\Lead_Form as Lead_Form_Block;
use Tribe\Project\Controllers\Blocks\Block_Controller;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Panels\Lead_Form as Container;
use Tribe\Project\Templates\Components\Text;

class Controller extends Block_Controller {

	public function render( $attributes, $content, $block_type ) {
		$this->attributes = $attributes;
		$this->content    = $content;
		$this->block_type = $block_type;

		$args = [
			Container::WIDTH   => $this->get_width(),
			Container::LAYOUT  => $this->get_layout(),
			Container::CONTENT => $this->get_content(),
			// Container::FORM    => false,
		];

		$this->render_component( 'panels/lead-form/Lead_Form.php', $args );
	}

	private function get_layout(): string {
		return $this->attributes[ Lead_Form_Block::LAYOUT ] ?? Lead_Form_Block::LAYOUT_CENTER;
	}

	private function get_width(): string {
		return $this->attributes[ Lead_Form_Block::WIDTH ] ?? Lead_Form_Block::WIDTH_GRID;
	}

	protected function get_content(): array {
		if ( empty( $this->attributes[ Lead_Form_Block::TITLE ] ) && empty( $this->attributes[ Lead_Form_Block::DESCRIPTION ] ) ) {
			return [];
		}

		return [
			Content_Block::TAG     => 'header',
			Content_Block::CLASSES => [ 'lead-form__content' ],
			Content_Block::TITLE   => $this->get_title(),
			Content_Block::TEXT    => $this->get_description(),
		];
	}

	private function get_title(): array {
		return  [
			Text::TAG     => 'h2',
			Text::CLASSES => [ 'lead-form__title', 'h3' ],
			Text::TEXT    => $this->attributes[ Lead_Form_Block::TITLE ] ?? '',
		];
	}

	private function get_description(): array {
		return [
			Text::CLASSES => [ 'lead-form__description', 't-sink', 's-sink' ],
			Text::TEXT    => $this->attributes[ Lead_Form_Block::DESCRIPTION ] ?? '',
		];
	}
}
