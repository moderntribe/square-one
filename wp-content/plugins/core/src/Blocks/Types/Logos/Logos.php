<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Logos;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Logos\Support\Logo;

class Logos extends Block_Type_Config {
	public const NAME = 'tribe/logos';

	public const TITLE         = 'title';
	public const DESCRIPTION   = 'description';
	public const CTA           = 'cta';
	public const LOGOS         = 'logos';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Logos', 'tribe' ) )
			->add_class( 'c-block b-logos' )
			->set_dashicon( 'menu-alt' )
			->add_content_section( $this->content_area() )
			->build();
	}

	private function content_area(): Content_Section {
		return $this->factory->content()->section()
			->add_field(
				$this->factory->content()->field()->text( self::TITLE )
					->set_label( __( 'Headline', 'tribe' ) )
					->set_placeholder( 'Headline' )
					->add_class( 'b-logos__title h1' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->richtext( self::DESCRIPTION )
					->set_label( __( 'Description', 'tribe' ) )
					->add_class( 'b-logos__description t-sink s-sink' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->link( self::CTA )
					->set_label( __( 'Call to Action', 'tribe' ) )
					->add_class( 'b-logos__cta a-btn' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->flexible_container( self::LOGOS )
					->set_label( __( 'Logos', 'tribe' ) )
					->set_min_blocks( 1 )
					->set_max_blocks( 12 )
					->add_block_type( Logo::NAME )
					->add_template_block( Logo::NAME )
					->merge_nested_attributes( Logo::NAME )
					->discard_nested_content( Logo::NAME )
					->build()
			)
			->build();
	}

}
