<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Logos\Support;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Logos\Logos;

class Logo extends Block_Type_Config {
	public const NAME = 'tribe/logos-logo';

	public const IMAGE = 'image';
	public const LINK  = 'link';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Logo', 'tribe' ) )
			->set_dashicon( 'menu-alt' )
			->set_parents( Logos::NAME )
			->add_content_section( $this->content_area() )
			->build();
	}

	private function content_area(): Content_Section {
		$image = $this->factory->content()->field()->image( self::IMAGE );
		$link  = $this->factory->content()->field()->link( self::LINK );


		return $this->factory->content()->section()
			->add_field( $image->build() )
			->add_field( $link->build() )
			->build();
	}

}
