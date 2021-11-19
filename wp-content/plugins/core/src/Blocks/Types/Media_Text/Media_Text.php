<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Media_Text;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Fields\Traits\With_Cta_Field;

class Media_Text extends Block_Config implements Cta_Field {

	use With_Cta_Field;

	public const NAME = 'mediatext';

	public const WIDTH       = 'width';
	public const WIDTH_GRID  = 'grid';
	public const WIDTH_FULL  = 'full';
	public const LEAD_IN     = 'leadin';
	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';

	public const SECTION_MEDIA = 's-media';
	public const MEDIA_TYPE    = 'media_type';
	public const IMAGE         = 'image';
	public const EMBED         = 'embed';
	public const LAYOUT        = 'layout';
	public const MEDIA_LEFT    = 'left';
	public const MEDIA_RIGHT   = 'right';
	public const MEDIA_CENTER  = 'center';

	/**
	 * 	 * Register the block
	 *
	 * @return void
	 */
	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Media + Text', 'tribe' ),
			'description' => __( 'An image or video plus text content with several layout options.', 'tribe' ),
			'icon'        => '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false"><path d="M13 17h8v-2h-8v2zM3 19h8V5H3v14zM13 9h8V7h-8v2zm0 4h8v-2h-8v2z"></path></svg>',
			'keywords'    => [
				__( 'image', 'tribe' ),
				__( 'video', 'tribe' ),
				__( 'display', 'tribe' ),
				__( 'text', 'tribe' ),
			],
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
						self::TITLE       => esc_html__( 'A Media and Text Block', 'tribe' ),
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
	 * 	 * Register Fields for block
	 *
	 * @return void
	 */
	public function add_fields() {
		$this->add_field( new Field( self::NAME . '_' . self::WIDTH, [
				'label'         => __( 'Block Width', 'tribe' ),
				'name'          => self::WIDTH,
				'type'          => 'button_group',
				'choices'       => [
					self::WIDTH_GRID => __( 'Wide', 'tribe' ),
					self::WIDTH_FULL => __( 'Full-Width', 'tribe' ),
				],
				'default_value' => self::WIDTH_GRID,
			] )
		)->add_field( new Field( self::NAME . '_' . self::LEAD_IN, [
				'label'       => __( 'Lead in', 'tribe' ),
				'name'        => self::LEAD_IN,
				'type'        => 'text',
				'wrapper'     => [
					'class' => 'tribe-acf-hide-label',
				],
				'placeholder' => 'Leadin (optional)',
			] )
		)->add_field( new Field( self::NAME . '_' . self::TITLE, [
				'label' => __( 'Title', 'tribe' ),
				'name'  => self::TITLE,
				'type'  => 'text',
			] )
		)->add_field( new Field( self::NAME . '_' . self::DESCRIPTION, [
				'label'        => __( 'Content', 'tribe' ),
				'name'         => self::DESCRIPTION,
				'type'         => 'wysiwyg',
				'toolbar'      => Classic_Editor_Formats::MINIMAL,
				'tabs'         => 'visual',
				'media_upload' => 0,
			] )
		)->add_field(
			$this->get_cta_field( self::NAME )
		);

		$this->add_section( new Field_Section( self::SECTION_MEDIA, __( 'Media', 'tribe' ), 'accordion' ) )
			 ->add_field( new Field( self::NAME . '_' . self::MEDIA_TYPE, [
					 'label'         => __( 'Media Type', 'tribe' ),
					 'name'          => self::MEDIA_TYPE,
					 'type'          => 'button_group',
					 'choices'       => [
						 self::IMAGE => __( 'Image', 'tribe' ),
						 self::EMBED => __( 'Video', 'tribe' ),
					 ],
					 'default_value' => [
						 self::IMAGE,
					 ],
				 ] )
			 )->add_field( new Field( self::NAME . '_' . self::IMAGE, [
					'label'             => __( 'Image', 'tribe' ),
					'name'              => self::IMAGE,
					'type'              => 'image',
					'return_format'     => 'id',
					'preview_size'      => 'medium',
					'conditional_logic' => [
						[
							[
								'field'    => 'field_' . self::NAME . '_' . self::MEDIA_TYPE,
								'operator' => '==',
								'value'    => self::IMAGE,
							],
						],
					],
				] )
			)->add_field( new Field( self::NAME . '_' . self::EMBED, [
					'label'             => __( 'Video', 'tribe' ),
					'name'              => self::EMBED,
					'type'              => 'oembed',
					'conditional_logic' => [
						[
							[
								'field'    => 'field_' . self::NAME . '_' . self::MEDIA_TYPE,
								'operator' => '==',
								'value'    => self::EMBED,
							],
						],
					],
				] )
			)->add_field( new Field( self::NAME . '_' . self::LAYOUT, [
				'label'         => __( 'Layout', 'tribe' ),
				'name'          => self::LAYOUT,
				'type'          => 'button_group',
				'choices'       => [
					self::MEDIA_LEFT   => __( 'Left', 'tribe' ),
					self::MEDIA_RIGHT  => __( 'Right', 'tribe' ),
					self::MEDIA_CENTER => __( 'Stacked', 'tribe' ),
				],
				'default_value' => self::MEDIA_LEFT,
			] ) );
	}

}
