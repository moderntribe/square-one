<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Gallery_Grid;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
use Tribe\Project\Blocks\Block_Category;

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

	public function add_block(): void {
		$this->set_block( new Block( self::NAME, [
			'title'    => esc_html__( 'Gallery Grid', 'tribe' ),
			'icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M0.75 2.38636C0.75 1.48262 1.48263 0.75 2.38636 0.75H9.61364C10.5174 0.75 11.25 1.48263 11.25 2.38636V9.61364C11.25 10.5174 10.5174 11.25 9.61364 11.25H2.38636C1.48262 11.25 0.75 10.5174 0.75 9.61364V2.38636ZM2.38636 2.25C2.31105 2.25 2.25 2.31105 2.25 2.38636V9.61364C2.25 9.68895 2.31105 9.75 2.38636 9.75H9.61364C9.68895 9.75 9.75 9.68895 9.75 9.61364V2.38636C9.75 2.31105 9.68895 2.25 9.61364 2.25H2.38636ZM14.3864 0.75C13.4826 0.75 12.75 1.48262 12.75 2.38636V9.61364C12.75 10.5174 13.4826 11.25 14.3864 11.25H21.6136C22.5174 11.25 23.25 10.5174 23.25 9.61364V2.38636C23.25 1.48263 22.5174 0.75 21.6136 0.75H14.3864ZM14.25 2.38636C14.25 2.31105 14.3111 2.25 14.3864 2.25H21.6136C21.6889 2.25 21.75 2.31105 21.75 2.38636V9.61364C21.75 9.68895 21.6889 9.75 21.6136 9.75H14.3864C14.3111 9.75 14.25 9.68895 14.25 9.61364V2.38636ZM19.3578 4.79669C19.3021 4.70129 19.1978 4.63352 19.0756 4.61335C18.9535 4.59318 18.8273 4.62291 18.7343 4.69377L15.1343 7.43662C15.0101 7.53119 14.9671 7.68167 15.0261 7.81472C15.0851 7.94778 15.2339 8.03573 15.4 8.03573H20.6C20.7386 8.03573 20.8674 7.97421 20.9403 7.87313C21.0131 7.77205 21.0198 7.64583 20.9578 7.53955L19.3578 4.79669ZM15.8571 5.46429C16.3305 5.46429 16.7143 5.08053 16.7143 4.60714C16.7143 4.13376 16.3305 3.75 15.8571 3.75C15.3838 3.75 15 4.13376 15 4.60714C15 5.08053 15.3838 5.46429 15.8571 5.46429ZM14.3864 12.75C13.4826 12.75 12.75 13.4826 12.75 14.3864V21.6136C12.75 22.5174 13.4826 23.25 14.3864 23.25H21.6136C22.5174 23.25 23.25 22.5174 23.25 21.6136V14.3864C23.25 13.4826 22.5174 12.75 21.6136 12.75H14.3864ZM14.25 14.3864C14.25 14.3111 14.3111 14.25 14.3864 14.25H21.6136C21.6889 14.25 21.75 14.3111 21.75 14.3864V21.6136C21.75 21.6889 21.6889 21.75 21.6136 21.75H14.3864C14.3111 21.75 14.25 21.6889 14.25 21.6136V14.3864ZM19.0756 16.6134C19.1978 16.6335 19.3021 16.7013 19.3578 16.7967L20.9578 19.5395C21.0198 19.6458 21.0131 19.772 20.9403 19.8731C20.8674 19.9742 20.7386 20.0357 20.6 20.0357H15.4C15.2339 20.0357 15.0851 19.9478 15.0261 19.8147C14.9671 19.6817 15.0101 19.5312 15.1343 19.4366L18.7343 16.6938C18.8273 16.6229 18.9535 16.5932 19.0756 16.6134ZM16.7143 16.6071C16.7143 17.0805 16.3305 17.4643 15.8571 17.4643C15.3838 17.4643 15 17.0805 15 16.6071C15 16.1338 15.3838 15.75 15.8571 15.75C16.3305 15.75 16.7143 16.1338 16.7143 16.6071ZM7.35778 4.79669C7.30212 4.70129 7.19784 4.63352 7.07565 4.61335C6.95345 4.59318 6.82727 4.62291 6.73426 4.69377L3.13427 7.43662C3.01015 7.53119 2.96711 7.68167 3.02613 7.81472C3.08515 7.94778 3.23394 8.03573 3.40002 8.03573H8.6C8.73863 8.03573 8.86738 7.97421 8.94026 7.87313C9.01314 7.77205 9.01977 7.64583 8.95777 7.53955L7.35778 4.79669ZM3.85714 5.46429C4.33053 5.46429 4.71429 5.08053 4.71429 4.60714C4.71429 4.13376 4.33053 3.75 3.85714 3.75C3.38376 3.75 3 4.13376 3 4.60714C3 5.08053 3.38376 5.46429 3.85714 5.46429ZM2.38636 12.75C1.48263 12.75 0.75 13.4826 0.75 14.3864V21.6136C0.75 22.5174 1.48262 23.25 2.38636 23.25H9.61364C10.5174 23.25 11.25 22.5174 11.25 21.6136V14.3864C11.25 13.4826 10.5174 12.75 9.61364 12.75H2.38636ZM2.25 14.3864C2.25 14.3111 2.31105 14.25 2.38636 14.25H9.61364C9.68895 14.25 9.75 14.3111 9.75 14.3864V21.6136C9.75 21.6889 9.68895 21.75 9.61364 21.75H2.38636C2.31105 21.75 2.25 21.6889 2.25 21.6136V14.3864ZM7.07565 16.6134C7.19784 16.6335 7.30212 16.7013 7.35778 16.7967L8.95777 19.5395C9.01977 19.6458 9.01314 19.772 8.94026 19.8731C8.86738 19.9742 8.73863 20.0357 8.6 20.0357H3.40002C3.23394 20.0357 3.08515 19.9478 3.02613 19.8147C2.96711 19.6817 3.01015 19.5312 3.13427 19.4366L6.73426 16.6938C6.82727 16.6229 6.95345 16.5932 7.07565 16.6134ZM4.71429 16.6071C4.71429 17.0805 4.33053 17.4643 3.85714 17.4643C3.38376 17.4643 3 17.0805 3 16.6071C3 16.1338 3.38376 15.75 3.85714 15.75C4.33053 15.75 4.71429 16.1338 4.71429 16.6071Z" fill="black"/></svg>',
			'keywords' => [ esc_html__( 'gallery', 'tribe' ), esc_html__( 'grid', 'tribe' ), esc_html__( 'image', 'tribe' ) ],
			'category' => Block_Category::CUSTOM_BLOCK_CATEGORY_SLUG,
			'supports' => [
				'align'  => false,
				'anchor' => true,
				'html'   => false,
			],
		] ) );
	}

	/**
	 * Register Fields for block
	 */
	public function add_fields(): void {
		$this->add_field( new Field( self::NAME . '_' . self::LEAD_IN, [
				'label' => esc_html__( 'Overline', 'tribe' ),
				'name'  => self::LEAD_IN,
				'type'  => 'text',
			] )
		)->add_field( new Field( self::NAME . '_' . self::TITLE, [
				'label' => esc_html__( 'Title', 'tribe' ),
				'name'  => self::TITLE,
				'type'  => 'text',
			] )
		)->add_field( new Field( self::NAME . '_' . self::DESCRIPTION, [
				'label'        => esc_html__( 'Description', 'tribe' ),
				'name'         => self::DESCRIPTION,
				'type'         => 'wysiwyg',
				'toolbar'      => Classic_Editor_Formats::MINIMAL,
				'tabs'         => 'visual',
				'media_upload' => 0,
			] )
		)->add_field( new Field( self::NAME . '_' . self::GALLERY_IMAGES, [
				'label' => esc_html__( 'Gallery Images', 'tribe' ),
				'name'  => self::GALLERY_IMAGES,
				'type'  => 'gallery',
				'max'   => 12,
			] )
		);

		$this->add_section( $this->get_settings_section() );
	}

	protected function get_settings_section(): Field_Section {
		$section = new Field_Section( self::SECTION_SETTINGS, esc_html__( 'Settings', 'tribe' ), 'accordion' );

		$columns = new Field( self::NAME . '_' . self::COLUMNS, [
			'label'         => esc_html__( 'Columns', 'tribe' ),
			'name'          => self::COLUMNS,
			'type'          => 'button_group',
			'choices'       => [
				self::COLUMNS_ONE   => esc_html__( '1', 'tribe' ),
				self::COLUMNS_TWO   => esc_html__( '2', 'tribe' ),
				self::COLUMNS_THREE => esc_html__( '3', 'tribe' ),
				self::COLUMNS_FOUR  => esc_html__( '4', 'tribe' ),
			],
			'default_value' => self::COLUMNS_THREE,
		] );

		$slideshow = new Field( self::NAME . '_' . self::USE_SLIDESHOW, [
			'label'   => esc_html__( 'Use Slideshow', 'tribe' ),
			'name'    => self::USE_SLIDESHOW,
			'type'    => 'true_false',
			'message' => esc_html__( 'Use Slideshow', 'tribe' ),
			'wrapper' => [
				'class' => 'tribe-acf-hide-label',
			],
		] );

		$section->add_field( $columns );
		$section->add_field( $slideshow );

		return $section;
	}

}
