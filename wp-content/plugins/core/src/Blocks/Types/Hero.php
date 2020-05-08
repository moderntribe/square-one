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
	public const LEAD_IN          = 'lead-in';
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
			->add_class( 'l-container test-hero__content t-sink t-theme--light' )
			->add_field(
				$this->factory->content()->field()->text( self::LEAD_IN )
					->set_placeholder( 'Some Lead In Text' )
					->add_class( 'test-hero__leadin' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->text( self::TITLE )
					->set_placeholder( 'A Heroic Title' )
					->add_class( 'test-hero__title h1' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->richtext( self::DESCRIPTION )
					->add_class( 'test-hero__description' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->link( self::CTA )
					->add_class( 'test-hero__cta btn-submit' )
					->build()
			)
			->build();
	}

}
