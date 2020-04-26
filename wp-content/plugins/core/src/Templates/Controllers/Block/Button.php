<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Block;

use Tribe\Project\Blocks\Types\Button as Button_Block;
use Tribe\Project\Templates\Components\Link;

class Button extends Block_Controller {

	public function render( string $path = '' ): string {
		$link = $this->get_link( $this->attributes );

		return $this->factory->get( Link::class, [
			Link::URL        => $link['url'],
			Link::CONTENT    => $link['text'],
			Link::TARGET     => $link['target'],
			Link::ARIA_LABEL => $this->attributes[ Button_Block::ARIA ] ?? '',
		] )->render( $path );
	}

	private function get_link( array $attributes ): array {
		$link = $attributes[ Button_Block::LINK ] ?? [];

		return wp_parse_args( (array) $link, [
			'url'    => '',
			'text'   => '',
			'target' => '',
		] );
	}

}
