<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Support;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Media_Text;

class Media_Text_Text extends Block_Type_Config {
	public const NAME = 'tribe/media-text--text';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( 'Text' )
			->set_dashicon( 'menu-alt' )
			->set_parents( Media_Text::NAME )
			->add_content_section( $this->content_area() )
			->build();
	}

	private function content_area(): Content_Section {
		$header = $this->factory->content()->field()->text( Media_Text::TITLE )
			->add_class( 'h2' );

		$content = $this->factory->content()->field()->flexible_container( Media_Text::CONTENT )
			->add_template_block( 'core/paragraph' );

		foreach ( $this->nested_block_types() as $type ) {
			$content->add_block_type( $type )->capture_nested_content( $type, 'content' );
		}

		$cta = $this->factory->content()->field()->link( Media_Text::CTA )
			->set_label( __( 'Call to Action', 'tribe' ) );

		return $this->factory->content()->section()
			->add_field( $header->build() )
			->add_field( $content->build() )
			->add_field( $cta->build() )
			->build();
	}

	private function nested_block_types(): array {
		return [
			'core/paragraph',
			'core/list',
			'core/heading',
			'core/quote',
		];
	}

}
