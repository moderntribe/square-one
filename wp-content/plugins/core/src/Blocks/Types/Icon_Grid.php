<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Support\Icon_Grid_Card;

class Icon_Grid extends Block_Type_Config {
	public const NAME = 'tribe/icon-grid';

	public const HEADER      = 'header';
	public const DESCRIPTION = 'subheader';
	public const CARDS       = 'cards';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( 'Icon Grid' )
			->set_dashicon( 'menu-alt' )
			->add_content_section( $this->content_area() )
			->build();
	}

	private function content_area(): Content_Section {
		return $this->factory->content()->section()
			->add_field(
				$this->factory->content()->field()->text( self::HEADER )
					->set_label( __( 'Header', 'tribe' ) )
					->set_placeholder( __( 'Enter header...', 'tribe' ) )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->richtext( self::DESCRIPTION )
					->set_label( __( 'Description', 'tribe' ) )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->flexible_container( self::CARDS )
					->set_label( __( 'Cards', 'tribe' ) )
					->set_min_blocks( 1 )
					->set_max_blocks( 12 )
					->add_block_type( Icon_Grid_Card::NAME )
					->add_template_block( Icon_Grid_Card::NAME )
					->merge_nested_attributes( Icon_Grid_Card::NAME )
					->discard_nested_content( Icon_Grid_Card::NAME )
					->build()
			)
			->build();
	}

}
