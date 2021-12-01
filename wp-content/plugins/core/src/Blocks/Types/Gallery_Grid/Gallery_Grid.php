<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Gallery_Grid;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;

class Gallery_Grid extends Block_Config {

	public const NAME = 'gallerygrid';

	public const LEAD_IN        = 'lead_in';
	public const TITLE          = 'title';
	public const DESCRIPTION    = 'description';
	public const GALLERY_IMAGES = 'gallery_images';

	public const SECTION_SETTINGS = 's-settings';
	public const COLUMNS          = 'columns';
	public const COLUMNS_ONE      = 'one';
	public const COLUMNS_TWO      = 'two';
	public const COLUMNS_THREE    = 'three';
	public const COLUMNS_FOUR     = 'four';
	public const USE_SLIDESHOW    = 'use_slideshow';

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
		$this->add_field( new Field( self::NAME . '_' . self::LEAD_IN, [
				'label'       => __( 'Lead in', 'tribe' ),
				'name'        => self::LEAD_IN,
				'type'        => 'text',
				'placeholder' => __( 'Lead in (optional)', 'tribe' ),
				'wrapper'     => [
					'class' => 'tribe-acf-hide-label',
				],
			] )
		)->add_field( new Field( self::NAME . '_' . self::TITLE, [
				'label' => __( 'Title', 'tribe' ),
				'name'  => self::TITLE,
				'type'  => 'text',
			] )
		)->add_field( new Field( self::NAME . '_' . self::DESCRIPTION, [
				'label'        => __( 'Description', 'tribe' ),
				'name'         => self::DESCRIPTION,
				'type'         => 'wysiwyg',
				'toolbar'      => Classic_Editor_Formats::MINIMAL,
				'tabs'         => 'visual',
				'media_upload' => 0,
			] )
		)->add_field( new Field( self::NAME . '_' . self::GALLERY_IMAGES, [
				'label' => __( 'Gallery Images', 'tribe' ),
				'name'  => self::GALLERY_IMAGES,
				'type'  => 'gallery',
				'max'   => 12,
			] )
		);

		$this->add_section( $this->get_settings_section() );
	}

	protected function get_settings_section(): Field_Section {
		$section = new Field_Section( self::SECTION_SETTINGS, __( 'Settings', 'tribe' ), 'accordion' );

		$columns = new Field( self::NAME . '_' . self::COLUMNS, [
			'label'         => __( 'Columns', 'tribe' ),
			'name'          => self::COLUMNS,
			'type'          => 'button_group',
			'choices'       => [
				self::COLUMNS_ONE   => __( '1', 'tribe' ),
				self::COLUMNS_TWO   => __( '2', 'tribe' ),
				self::COLUMNS_THREE => __( '3', 'tribe' ),
				self::COLUMNS_FOUR  => __( '4', 'tribe' ),
			],
			'default_value' => self::COLUMNS_THREE,
		] );

		$slideshow = new Field( self::NAME . '_' . self::USE_SLIDESHOW, [
			'label'   => __( 'Use Slideshow', 'tribe' ),
			'name'    => self::USE_SLIDESHOW,
			'type'    => 'true_false',
			'message' => __( 'Use Slideshow', 'tribe' ),
			'wrapper' => [
				'class' => 'tribe-acf-hide-label',
			],
		] );

		$section->add_field( $columns );
		$section->add_field( $slideshow );

		return $section;
	}

}
