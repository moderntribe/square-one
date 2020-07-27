<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Accordion\Support;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Accordion\Accordion;

class Accordion_Section extends Block_Type_Config {

	public const NAME = 'tribe/accordion-section';

	public const HEADER  = 'header';
	public const CONTENT = 'content';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Accordion Section', 'tribe' ) )
			->set_dashicon( 'menu-alt' )
			->set_parents( Accordion::NAME )
			->add_content_section( $this->content_area() )
			->add_class( 'c-accordion__row' )
			->build();
	}

	private function content_area(): Content_Section {
		$header = $this->factory->content()->field()->text( self::HEADER )
			->add_class( 'c-accordion__header h5' );

		$content = $this->factory->content()->field()->flexible_container( self::CONTENT )
			->add_class( 'c-accordion__content-container' )
			->add_template_block( 'core/paragraph' );

		foreach ( $this->nested_block_types() as $type ) {
			$content->add_block_type( $type )->capture_nested_content( $type, 'content' );
		}

		return $this->factory->content()->section()
			->add_field( $header->build() )
			->add_field( $content->build() )
			->build();
	}

	private function nested_block_types(): array {
		return [
			'core/paragraph',
			'core/list',
			'core/heading',
			'core/image',
			'core/gallery',
			'core/quote',
		];
	}

}
