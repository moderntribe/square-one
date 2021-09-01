<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Quote;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Group;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Project\Theme\Config\Image_Sizes;

class Quote extends Block_Config {

	public const NAME = 'quote';

	public const QUOTE      = 'text';
	public const GROUP_CITE = 'g-cite';
	public const CITE_NAME  = 'cite_name';
	public const CITE_TITLE = 'cite_title';
	public const CITE_IMAGE = 'cite_image';

	public const SECTION_MEDIA              = 's-media';
	public const LAYOUT                     = 'layout';
	public const MEDIA_LEFT                 = 'left';
	public const MEDIA_RIGHT                = 'right';
	public const MEDIA_OVERLAY              = 'overlay';
	public const MEDIA_L_R_INSTRUCTIONS     = 'left-right-instructions';
	public const MEDIA_OVERLAY_INSTRUCTIONS = 'overlay-instructions';
	public const IMAGE                      = 'image';

	/**
	 * Register the block
	 */
	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Quote + Image', 'tribe' ),
			'description' => __( 'A combined quotation and associated image with several layout options.', 'tribe' ),
			'icon'        => 'testimonial',
			'keywords'    => [ __( 'quotation', 'tribe' ), __( 'display', 'tribe' ), __( 'text', 'tribe' ) ],
			'category'    => 'layout',
			'supports'    => [
				'align'  => false,
				'anchor' => true,
			],
			'example'     => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						self::QUOTE      => esc_html__(
							'Grow awareness while remembering to maximise share of voice. Leveraging agile so that as an end result, we think outside the box.',
							'tribe'
						),
						self::GROUP_CITE => [
							self::CITE_NAME  => esc_html__( 'John Doe', 'tribe' ),
							self::CITE_TITLE => esc_html__( 'Chief Executive', 'tribe' ),
							self::IMAGE      => 0,
						],
					],
				],
			],
		] ) );
	}

	/**
	 * Register Fields for block
	 */
	public function add_fields() {
		$this->add_field( new Field( self::NAME . '_' . self::QUOTE, [
				'label' => __( 'Quote Text', 'tribe' ),
				'name'  => self::QUOTE,
				'type'  => 'textarea',
				'rows'  => 4,
			] )
		)->add_field(
			$this->get_citation_field_group()
		);


		$this->add_section( new Field_Section( self::SECTION_MEDIA, __( 'Media', 'tribe' ), 'accordion' ) )
			 ->add_field( new Field( self::NAME . '_' . self::LAYOUT, [
					 'label'         => __( 'Layout', 'tribe' ),
					 'name'          => self::LAYOUT,
					 'type'          => 'button_group',
					 'choices'       => [
						 self::MEDIA_LEFT    => __( 'Image Left', 'tribe' ),
						 self::MEDIA_OVERLAY => __( 'Overlay', 'tribe' ),
						 self::MEDIA_RIGHT   => __( 'Image Right', 'tribe' ),
					 ],
					 'default_value' => self::MEDIA_OVERLAY,
				 ] )
			 )->add_field( new Field( self::NAME . '_' . self::MEDIA_L_R_INSTRUCTIONS, [
					'label'             => __( 'Media Left/Right Instructions', 'tribe' ),
					'name'              => self::MEDIA_L_R_INSTRUCTIONS,
					'type'              => 'message',
					'message'           => __( 'Recommended image size: 1536 wide with a 4:3 aspect ratio.', 'tribe' ),
					'wrapper'           => [
						'class' => 'tribe-acf-hide-label',
					],
					'conditional_logic' => [
						[
							[
								'field'    => 'field_' . self::NAME . '_' . self::LAYOUT,
								'operator' => '==',
								'value'    => self::MEDIA_LEFT,
							],
						],
						[
							[
								'field'    => 'field_' . self::NAME . '_' . self::LAYOUT,
								'operator' => '==',
								'value'    => self::MEDIA_RIGHT,
							],
						],
					],
				] )
			)->add_field( new Field( self::NAME . '_' . self::MEDIA_OVERLAY_INSTRUCTIONS, [
					'label'             => __( 'Media Overlay Instructions', 'tribe' ),
					'name'              => self::MEDIA_OVERLAY_INSTRUCTIONS,
					'type'              => 'message',
					'message'           => __(
						'Recommended image size: 1920px wide with a 16:9 aspect ratio.',
						'tribe'
					),
					'wrapper'           => [
						'class' => 'tribe-acf-hide-label',
					],
					'conditional_logic' => [
						[
							[
								'field'    => 'field_' . self::NAME . '_' . self::LAYOUT,
								'operator' => '==',
								'value'    => self::MEDIA_OVERLAY,
							],
						],
					],
				] )
			)->add_field( new Field( self::NAME . '_' . self::IMAGE, [
					'label'         => __( 'Image', 'tribe' ),
					'name'          => self::IMAGE,
					'type'          => 'image',
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'wrapper'       => [
						'class' => 'tribe-acf-hide-label',
					],
				] )
			);
	}

	/**
	 * @return \Tribe\Libs\ACF\Field_Group
	 */
	protected function get_citation_field_group(): Field_Group {
		$group = new Field_Group( self::NAME . '_' . self::GROUP_CITE );
		$group->set_attributes( [
			'label'  => __( 'Citation', 'tribe' ),
			'name'   => self::GROUP_CITE,
			'layout' => 'block',
		] );

		$name = new Field( self::NAME . '_' . self::CITE_NAME, [
			'label' => __( 'Name', 'tribe' ),
			'name'  => self::CITE_NAME,
			'type'  => 'text',
		] );

		$title = new Field( self::NAME . '_' . self::CITE_TITLE, [
			'label' => __( 'Title/Description', 'tribe' ),
			'name'  => self::CITE_TITLE,
			'type'  => 'text',
		] );

		$image = new Field( self::NAME . '_' . self::CITE_IMAGE, [
			'label'         => __( 'Photo', 'tribe' ),
			'name'          => self::CITE_IMAGE,
			'type'          => 'image',
			'return_format' => 'id',
			'preview_size'  => Image_Sizes::SQUARE_XSMALL,
		] );

		$group->add_field( $name );
		$group->add_field( $title );
		$group->add_field( $image );

		return $group;
	}

}
