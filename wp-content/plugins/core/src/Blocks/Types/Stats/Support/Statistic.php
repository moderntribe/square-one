<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Stats\Support;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Stats\Stats;

class Statistic extends Block_Type_Config {
	public const NAME = 'tribe/statistic';

	public const VALUE = 'value';
	public const LABEL = 'label';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Statistic', 'tribe' ) )
			->set_dashicon( 'menu-alt' )
			->set_parents( Stats::NAME )
			->add_content_section( $this->content_area() )
			->add_class( 'b-stats__list-item' )
			->build();
	}

	private function content_area(): Content_Section {
		return $this->factory->content()->section()
			->add_class( 'c-statistic' )
			->add_field(
				$this->factory->content()->field()->text( self::VALUE )
					->set_label( __( 'Title', 'tribe' ) )
					->add_class( 'c-statistic__value h5' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->richtext( self::LABEL )
					->set_label( __( 'Description', 'tribe' ) )
					->add_class( 'c-statistic__label' )
					->build()
			)
			->build();
	}
}
