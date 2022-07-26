<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Gallery_Slider;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Project\Blocks\Block_Category;

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
			'title'    => esc_html__( 'Gallery Slider', 'tribe' ),
			'icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M7.5 3C7.08579 3 6.75 3.33579 6.75 3.75C6.75 4.16421 7.08579 4.5 7.5 4.5H16.5C16.9142 4.5 17.25 4.16421 17.25 3.75C17.25 3.33579 16.9142 3 16.5 3H7.5ZM5.25 6C4.00736 6 3 7.00736 3 8.25V18.75C3 19.9926 4.00736 21 5.25 21H18.75C19.9926 21 21 19.9926 21 18.75V8.25C21 7.00736 19.9926 6 18.75 6H5.25ZM4.5 8.25C4.5 7.83579 4.83579 7.5 5.25 7.5H18.75C19.1642 7.5 19.5 7.83579 19.5 8.25V18.75C19.5 19.1642 19.1642 19.5 18.75 19.5H5.25C4.83579 19.5 4.5 19.1642 4.5 18.75V8.25ZM14.3919 10.5135C14.621 10.5577 14.8165 10.7059 14.9209 10.9146L17.9209 16.9146C18.0371 17.1471 18.0247 17.4232 17.888 17.6443C17.7514 17.8654 17.51 18 17.25 18H7.50003C7.18865 18 6.90965 17.8076 6.79899 17.5165C6.68833 17.2255 6.76903 16.8963 7.00176 16.6894L13.7518 10.6894C13.9261 10.5344 14.1627 10.4694 14.3919 10.5135ZM9.47268 16.5H16.0365L14.0169 12.4607L9.47268 16.5ZM9.75 11.25C9.75 12.0784 9.07843 12.75 8.25 12.75C7.42157 12.75 6.75 12.0784 6.75 11.25C6.75 10.4216 7.42157 9.75 8.25 9.75C9.07843 9.75 9.75 10.4216 9.75 11.25Z" fill="black"/></svg>',
			'keywords' => [ esc_html__( 'gallery', 'tribe' ), esc_html__( 'slider', 'tribe' ), esc_html__( 'carousel', 'tribe' ), esc_html__( 'image', 'tribe' ) ],
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
