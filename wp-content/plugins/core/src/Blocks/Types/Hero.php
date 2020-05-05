<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Gutenpanels\Blocks\Sections\Sidebar_Section;
use Tribe\Project\Blocks\Block_Type_Config;

/*
 * @TODO: THIS IS NOT COMPLETE AND WILL BE PICKED BACK UP BY RYAN
 */

class Hero extends Block_Type_Config {

	public const NAME = 'tribe/hero';

	public const BACKGROUND_IMAGE = 'bg-image';
	public const ICON             = 'icon';
	public const TITLE            = 'title';
	public const DESCRIPTION      = 'description';
	public const CTA              = 'cta';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
			->set_label( __( 'Hero', 'tribe' ) )
			->add_class( 'test-hero' )
			->set_dashicon( 'menu-alt' )
			->add_sidebar_section( $this->background_sidebar() )
			->add_content_section( $this->content_area() )
			->add_data_source( 'background-image', self::BACKGROUND_IMAGE )
			->build();
	}

	private function background_sidebar(): Sidebar_Section {
		return $this->factory->sidebar()->section()->set_label( 'Background Settings' )
			->add_field(
				$this->factory->sidebar()->field()->image( self::BACKGROUND_IMAGE )
					->set_label( __( 'Background Image', 'tribe' ) )
					->build()
			)
			->build();
	}

	private function content_area(): Content_Section {
		return $this->factory->content()
			->section()
			->add_class( 'test-hero__content content-wrap t-sink s-spa t-theme--light' )
			->add_field(
				$this->factory->content()->field()->image( self::ICON )
					->set_label( __( 'Icon', 'tribe' ) )
					->add_class( 'test-hero__icon' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->text( self::TITLE )
					->add_class( 'test-hero__title' )

					->build()
			)
			->add_field(
				$this->factory->content()->field()->richtext( self::DESCRIPTION )
					->add_class( 'test-hero__description' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->link( self::CTA )
					->build()
			)
			->build();
	}

}
