<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Hero;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
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
			'title'       => esc_html__( 'Hero', 'tribe' ),
			'description' => esc_html__( 'Hero block', 'tribe' ),
			'icon'        => '<svg width="28" height="19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.254 5.968H19.85v1.054H8.254V5.968zm1.462 1.57h8.568v1.053H9.716V7.538z" fill="#000"/><path d="M2.82 2.437v14.126H25.2V2.437H2.82zm21.482 13.176H3.866V3.366h20.436v12.247z" fill="#000"/><path d="M10.092 16.15H2.884l10.614-3.242 1.693 1.012 5.433-3.243 4.409 2.623v2.85h-14.94z" fill="#000"/></svg>',
			'keywords'    => [ esc_html__( 'hero', 'tribe' ), esc_html__( 'display', 'tribe' ) ],
			'category'    => 'layout',
			'supports'    => [
				'align'  => false,
				'anchor' => true,
			],
			'example'     => [
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
				'label'       => esc_html__( 'Lead in', 'tribe' ),
				'name'        => self::LEAD_IN,
				'type'        => 'text',
				'placeholder' => esc_html__( 'Lead in (optional)', 'tribe' ),
				'wrapper'     => [
					'class' => 'tribe-acf-hide-label',
				],
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
