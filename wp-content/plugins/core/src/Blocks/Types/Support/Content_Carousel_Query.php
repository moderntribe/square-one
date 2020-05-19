<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Support;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Content_Carousel;

class Content_Carousel_Query extends Block_Type_Config {
	public const NAME = Content_Carousel::NAME . '--query';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Query', 'tribe' ) )
			->set_dashicon( 'menu-alt' )
			->set_parents( Content_Carousel::NAME )
			// TODO: Posts Query field
			->build();
	}
}
