<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Support;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Card_Grid;

class Card_Grid_Query extends Block_Type_Config {
	public const NAME = Card_Grid::NAME . '--query';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Query', 'tribe' ) )
			->set_dashicon( 'menu-alt' )
			->set_parents( Card_Grid::NAME )
			// TODO: Posts Query field
			->build();
	}
}
