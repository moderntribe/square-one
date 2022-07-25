<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Gallery_Slider;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;

class Gallery_Slider extends Block_Config {

	public const NAME = 'galleryslider';

	public const SECTION_CONTENT  = 's-content';
	public const SECTION_SETTINGS = 's-settings';

	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const CTA         = 'cta';
	public const GALLERY     = 'gallery';

	public const IMAGE_RATIO = 'image_ratio';
	public const FIXED       = 'fixed';
	public const VARIABLE    = 'variable';

	public const CAPTION_DISPLAY      = 'caption_display';
	public const CAPTION_DISPLAY_SHOW = 'caption_display_show';
	public const CAPTION_DISPLAY_HIDE = 'caption_display_hide';

	public const SLIDESHOW = 'slideshow';

	public function add_block(): void {
		$this->set_block( new Block( self::NAME, [
			'title'       => esc_html__( 'Gallery Slider', 'tribe' ),
			'description' => esc_html__( 'A custom block by Modern Tribe', 'tribe' ), // TODO: describe the block
			'icon'        => '<svg enable-background="new 0 0 146.3 106.3" version="1.1" viewBox="0 0 146.3 106.3" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><style type="text/css">.st0{fill:#16D690;}.st1{fill:#21A6CB;}.st2{fill:#008F8F;}</style><polygon class="st0" points="145.2 106.3 72.6 42.3 26.5 1.2 0 106.3"/><polygon class="st1" points="145.2 106.3 0 106.3 72.6 42.3 118.6 1.2"/><polygon class="st2" points="72.6 42.3 145.2 106.3 0 106.3"/></svg>', // TODO: set SVG icon
			'keywords'    => [ esc_html__( 'gallery', 'tribe' ), esc_html__( 'slider', 'tribe' ), esc_html__( 'carousel', 'tribe' ), esc_html__( 'image', 'tribe' ) ], // TODO: select appropriate keywords
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
	public function add_fields(): void {
		//==========================================
		// Content Fields
		//==========================================
		$this->add_section( new Field_Section( self::SECTION_CONTENT, esc_html__( 'Content', 'tribe' ), 'accordion' ) )
			->add_field( new Field( self::NAME . '_' . self::TITLE, [
					'label' => esc_html__( 'Title', 'tribe' ),
					'name'  => self::TITLE,
					'type'  => 'text',
				] )
			)->add_field( new Field( self::NAME . '_' . self::DESCRIPTION, [
					'label'        => esc_html__( 'Description', 'tribe' ),
					'name'         => self::DESCRIPTION,
					'type'         => 'wysiwyg',
					'toolbar'      => 'basic',
					'media_upload' => 0,
				] )
			)->add_field( new Field( self::NAME . '_' . self::CTA, [
					'label' => esc_html__( 'Call to Action', 'tribe' ),
					'name'  => self::CTA,
					'type'  => 'link',
				] )
			)->add_field( new Field( self::NAME . '_' . self::GALLERY, [
					'label'        => esc_html__( 'Gallery', 'tribe' ),
					'name'         => self::GALLERY,
					'type'         => 'gallery',
					'max'          => 12,
					'instructions' => esc_html__( 'Recommended image size by layout:', 'tribe' ) . '<br>' .
									  esc_html__( 'Fixed: 1920px wide with a 16:9 aspect ratio.', 'tribe' ) . '<br>' .
									  esc_html__( 'Variable: 900px height minimum with any aspect ratio.', 'tribe' ),
				] )
			);

		//==========================================
		// Setting Fields
		//==========================================
		$this->add_section( new Field_Section( self::SECTION_SETTINGS, esc_html__( 'Settings', 'tribe' ), 'accordion' ) )
		->add_field( new Field( self::NAME . '_' . self::IMAGE_RATIO, [
			'label'         => esc_html__( 'Image Ratio', 'tribe' ),
			'name'          => self::IMAGE_RATIO,
			'type'          => 'button_group',
			'choices'       => [
				self::FIXED    => esc_html__( 'Fixed', 'tribe' ),
				self::VARIABLE => esc_html__( 'Variable', 'tribe' ),
			],
			'default_value' => self::FIXED,
			] )
		)->add_field( new Field( self::NAME . '_' . self::CAPTION_DISPLAY, [
			'label'         => esc_html__( 'Show Image Captions?', 'tribe' ),
			'name'          => self::CAPTION_DISPLAY,
			'type'          => 'button_group',
			'choices'       => [
				self::CAPTION_DISPLAY_SHOW => esc_html__( 'Yes', 'tribe' ),
				self::CAPTION_DISPLAY_HIDE => esc_html__( 'No', 'tribe' ),
			],
			'default_value' => self::CAPTION_DISPLAY_SHOW,
			] )
		);
	}

}
