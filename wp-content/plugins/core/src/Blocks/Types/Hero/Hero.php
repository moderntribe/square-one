<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Hero;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
use Tribe\Project\Blocks\Block_Category;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Fields\Traits\With_Cta_Field;

class Hero extends Block_Config implements Cta_Field {

	use With_Cta_Field;

	public const NAME = 'hero';

	public const LEAD_IN     = 'leadin';
	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';

	public const SECTION_BACKGROUND = 's-background';
	public const IMAGE              = 'image';

	public const SECTION_APPEARANCE = 's-appearance';
	public const LAYOUT             = 'layout';
	public const LAYOUT_LEFT        = 'left';
	public const LAYOUT_CENTER      = 'center';

	/**
	 * Register the block
	 */
	public function add_block(): void {
		$this->set_block( new Block( self::NAME, [
			'title'    => esc_html__( 'Hero', 'tribe' ),
			'icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M22.5 4.5H1.5V19.5H6.48851L13.8414 13.6626C14.0141 13.5255 14.2385 13.4713 14.4548 13.5146C14.6711 13.5578 14.8573 13.6941 14.9641 13.8871L18.0674 19.5H22.5V4.5ZM6.75711 21H6.74393H1.5C0.671573 21 0 20.3284 0 19.5V4.5C0 3.67157 0.671573 3 1.5 3H22.5C23.3284 3 24 3.67157 24 4.5V19.5C24 20.3284 23.3284 21 22.5 21H17.64C17.6305 21.0002 17.6209 21.0002 17.6114 21H6.75711ZM8.90096 19.5H16.3534L14.0801 15.3883L8.90096 19.5ZM6 6.75H18V8.25H6V6.75ZM20.25 9.75H3.75V11.25H20.25V9.75ZM9.75 14.25C9.75 15.0784 9.07843 15.75 8.25 15.75C7.42157 15.75 6.75 15.0784 6.75 14.25C6.75 13.4216 7.42157 12.75 8.25 12.75C9.07843 12.75 9.75 13.4216 9.75 14.25Z" fill="black"/></svg>',
			'keywords' => [ esc_html__( 'hero', 'tribe' ), esc_html__( 'display', 'tribe' ) ],
			'category' => Block_Category::CUSTOM_BLOCK_CATEGORY_SLUG,
			'supports' => [
				'align'  => false,
				'anchor' => true,
			],
			'example'  => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						self::LEAD_IN     => esc_html__( 'Lorem ipsum dolor sit amet.', 'tribe' ),
						self::TITLE       => esc_html__( 'The Hero Title', 'tribe' ),
						self::DESCRIPTION => esc_html__(
							'Cras ut ornare dui, sed venenatis est. Donec euismod in leo quis consequat.',
							'tribe'
						),
						self::GROUP_CTA   => [
							self::LINK => [
								'title'  => esc_html__( 'Lorem ipsum', 'tribe' ),
								'url'    => '#',
								'target' => '',
							],
						],
						//Images are output as IDs so it's sort of hard to get an image value for preview
						self::IMAGE       => 0,
					],
				],
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
		)->add_field(
			$this->get_cta_field( self::NAME )
		);

		$this->add_section( new Field_Section( self::SECTION_BACKGROUND, esc_html__( 'Background', 'tribe' ), 'accordion' ) )
			 ->add_field( new Field( self::NAME . '_' . self::IMAGE, [
					 'label'         => esc_html__( 'Image', 'tribe' ),
					 'name'          => self::IMAGE,
					 'type'          => 'image',
					 'return_format' => 'array',
					 'instructions'  => esc_html__( 'Landscape orientation recommended. Minimum 1700px wide.', 'tribe' ),
					 'wrapper'       => [
						 'class' => 'tribe-acf-hide-label',
					 ],
				 ] )
			 );

		$this->add_section( new Field_Section( self::SECTION_APPEARANCE, esc_html__( 'Appearance', 'tribe' ), 'accordion' ) )
			 ->add_field( new Field( self::NAME . '_' . self::LAYOUT, [
				 'label'         => esc_html__( 'Text Alignment', 'tribe' ),
				 'type'          => 'button_group',
				 'name'          => self::LAYOUT,
				 'choices'       => [
					 self::LAYOUT_LEFT   => esc_html__( 'Left', 'tribe' ),
					 self::LAYOUT_CENTER => esc_html__( 'Center', 'tribe' ),
				 ],
				 'default_value' => self::LAYOUT_CENTER,
			 ] ) );
	}

}
