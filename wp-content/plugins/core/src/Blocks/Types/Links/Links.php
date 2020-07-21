<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Links;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Blocks\Sections\Content_Section;
use Tribe\Gutenpanels\Blocks\Sections\Toolbar_Section;
use Tribe\Project\Blocks\Block_Type_Config;
use Tribe\Project\Blocks\Types\Links\Support\Link;

class Links extends Block_Type_Config {
	public const NAME = 'tribe/links';

	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const LINKS       = 'links';
	public const LINK_ITEM   = 'link_item';
	public const LINKS_TITLE = 'links_title';

	public const LAYOUT         = 'layout';
	public const LAYOUT_INLINE  = 'layout-inline';
	public const LAYOUT_STACKED = 'layout-stacked';

	public function build(): Block_Type_Interface {
		return $this->factory->block( self::NAME )
	         ->set_label( __( 'Links', 'tribe' ) )
	         ->set_dashicon( 'menu-alt' )
	         ->add_class( 'c-panel c-panel--links l-container' )
	         ->add_data_source( 'className-c-panel', self::LAYOUT )
	         ->add_toolbar_section( $this->layout_toolbar() )
	         ->add_content_section( $this->content_area() )
	         ->add_content_section( $this->links_area() )
	         ->build();
	}

	private function content_area(): Content_Section {
		return $this->factory->content()->section()
			->add_class( 'links__header' )
			->add_field(
				$this->factory->content()->field()->text( self::TITLE )
					->set_label( __( 'Title', 'tribe' ) )
					->add_class( 'links__title h3' )
					->build()
			)
			->add_field(
				$this->factory->content()->field()->richtext( self::DESCRIPTION )
					->set_label( __( 'Description', 'tribe' ) )
					->add_class( 'links__description t-sink s-sink' )
					->build()
			)
			->build();
	}

	private function links_area(): Content_Section {
		return $this->factory->content()->section()
			->add_class( 'links__content' )
			->add_field(
				$this->factory->content()->field()->text( self::LINKS_TITLE )
				              ->set_label( __( 'List Title', 'tribe' ) )
				              ->add_class( 'links__list-title h5' )
				              ->build()
			)
			->add_field(
				$this->factory->content()->field()->flexible_container( self::LINKS )
					->set_label( __( 'Links List', 'tribe' ) )
					->merge_nested_attributes( Link::NAME )
					->add_template_block( Link::NAME )
					->add_block_type( Link::NAME )
					->set_min_blocks( 1 )
					->build()
			)
			->build();
	}

	private function layout_toolbar(): Toolbar_Section {
		return $this->factory->toolbar()->section()
		->add_field(
			$this->factory->toolbar()->field()->icon_select( self::LAYOUT )
				->add_dashicon_option( self::LAYOUT_STACKED, __( 'Stacked', 'tribe' ), 'align-center' )
				->add_dashicon_option( self::LAYOUT_INLINE, __( 'Inline', 'tribe' ), 'align-right' )
				->set_default( self::LAYOUT_STACKED )
				->build()
		)
		->build();
	}

}
