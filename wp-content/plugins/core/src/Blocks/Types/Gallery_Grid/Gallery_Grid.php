<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Gallery_Grid;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;

class Gallery_Grid extends Block_Config {
	public const NAME = 'gallerygrid';

	public const SECTION_CONTENT  = 's-content';
	public const SECTION_SETTINGS = 's-settings';

	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const CTA         = 'cta';
	public const GALLERY     = 'gallery';

	public const GRID_LAYOUT = 'grid_layout';
	public const ONE         = 'one';
	public const TWO         = 'two';
	public const THREE       = 'three';
	public const FOUR        = 'four';
	
	public const SLIDESHOW   = 'slideshow';

	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Gallery Grid', 'tribe' ),
			'description' => __( 'A custom block by Modern Tribe', 'tribe' ), // TODO: describe the block
			'icon'        => '<svg enable-background="new 0 0 146.3 106.3" version="1.1" viewBox="0 0 146.3 106.3" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><style type="text/css">.st0{fill:#16D690;}.st1{fill:#21A6CB;}.st2{fill:#008F8F;}</style><polygon class="st0" points="145.2 106.3 72.6 42.3 26.5 1.2 0 106.3"/><polygon class="st1" points="145.2 106.3 0 106.3 72.6 42.3 118.6 1.2"/><polygon class="st2" points="72.6 42.3 145.2 106.3 0 106.3"/></svg>', // TODO: set SVG icon
			'keywords'    => [ __( 'gallery', 'grid', 'image', 'tribe' ) ], // TODO: select appropriate keywords
			'category'    => 'common', // core categories: common, formatting, layout, widgets, embed
			'supports'    => [
				'align'  => false,
				'anchor' => true,
				'html'   => false,
			],
		] ) );
	}

	/**
	 * Register Fields for block
	 */
	public function add_fields() {
		//==========================================
		// Content Fields
		//==========================================
		$this->add_section( new Field_Section( self::SECTION_CONTENT, __( 'Content', 'tribe' ), 'accordion' ) )
			->add_field( new Field( self::NAME . '_' . self::TITLE, [
					'label' => __( 'Title', 'tribe' ),
					'name'  => self::TITLE,
					'type'  => 'text',
				] )
			)->add_field( new Field( self::NAME . '_' . self::DESCRIPTION, [
					'label'        => __( 'Description', 'tribe' ),
					'name'         => self::DESCRIPTION,
					'type'         => 'wysiwyg',
					'toolbar'      => 'basic',
					'media_upload' => 0,
				] )
			)->add_field( new Field( self::NAME . '_' . self::GALLERY, [
					'label' => __( 'Gallery', 'tribe' ),
					'name'  => self::GALLERY,
					'type'  => 'gallery',
					'max'   => 12,
				] )
			);

		//==========================================
		// Setting Fields
		//==========================================
		$this->add_section( new Field_Section( self::SECTION_SETTINGS, __( 'Settings', 'tribe' ), 'accordion' ) )
		->add_field( new Field( self::NAME . '_' . self::GRID_LAYOUT, [
			'label' => __( 'Grid Columns', 'tribe' ),
			'name'  => self::GRID_LAYOUT,
			'type'  => 'select',
			'choices'       => [
				self::ONE   => __( '1', 'tribe' ),
				self::TWO   => __( '2', 'tribe' ),
				self::THREE => __( '3', 'tribe' ),
				self::FOUR  => __( '4', 'tribe' ),
			],
			'default_value' => self::THREE,
			] )
		)->add_field( new Field( self::NAME . '_' . self::SLIDESHOW, [
			'label' => __( 'Use Slideshow?', 'tribe' ),
			'name'  => self::SLIDESHOW,
			'type'  => 'true_false',
			] )
		);
	}
}
