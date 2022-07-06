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

	public const QUOTE_GROUP = 'quote_group';
	public const QUOTE_TEXT  = 'quote_text';
	public const CITE_NAME   = 'cite_name';
	public const CITE_TITLE  = 'cite_title';
	public const CITE_IMAGE  = 'cite_image';

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
	public function add_block(): void {
		$this->set_block( new Block( self::NAME, [
			'title'       => esc_html__( 'Quote + Image', 'tribe' ),
			'description' => esc_html__( 'A combined quotation and associated image with several layout options.', 'tribe' ),
			'icon'        => 'testimonial',
			'keywords'    => [ esc_html__( 'quotation', 'tribe' ), esc_html__( 'display', 'tribe' ), esc_html__( 'text', 'tribe' ) ],
			'category'    => 'layout',
			'supports'    => [
				'align'  => false,
				'anchor' => true,
			],
			'example'     => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						self::QUOTE_GROUP => [
							self::QUOTE_TEXT => esc_html__(
								'Grow awareness while remembering to maximise share of voice. Leveraging agile so that as an end result, we think outside the box.',
								'tribe'
							),
							self::CITE_NAME  => esc_html__( 'John Doe', 'tribe' ),
							self::CITE_TITLE => esc_html__( 'Chief Executive', 'tribe' ),
							self::CITE_IMAGE => [],
						],
						self::IMAGE       => [],
					],
				],
			],
		] ) );
	}

	/**
	 * Register Fields for block
	 */
	public function add_fields(): void {
		$this->add_field( $this->get_citation_field_group() );

		$this->add_section( new Field_Section( self::SECTION_MEDIA, esc_html__( 'Media', 'tribe' ), 'accordion' ) )
			 ->add_field( new Field( self::NAME . '_' . self::LAYOUT, [
					 'label'         => esc_html__( 'Layout', 'tribe' ),
					 'name'          => self::LAYOUT,
					 'type'          => 'button_group',
					 'choices'       => [
						 self::MEDIA_LEFT    => esc_html__( 'Image Left', 'tribe' ),
						 self::MEDIA_OVERLAY => esc_html__( 'Overlay', 'tribe' ),
						 self::MEDIA_RIGHT   => esc_html__( 'Image Right', 'tribe' ),
					 ],
					 'default_value' => self::MEDIA_OVERLAY,
				 ] )
			 )->add_field( new Field( self::NAME . '_' . self::MEDIA_L_R_INSTRUCTIONS, [
					'label'             => esc_html__( 'Media Left/Right Instructions', 'tribe' ),
					'name'              => self::MEDIA_L_R_INSTRUCTIONS,
					'type'              => 'message',
					'message'           => esc_html__( 'Recommended image size: 1536 wide with a 4:3 aspect ratio.', 'tribe' ),
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
					'label'             => esc_html__( 'Media Overlay Instructions', 'tribe' ),
					'name'              => self::MEDIA_OVERLAY_INSTRUCTIONS,
					'type'              => 'message',
					'message'           => esc_html__(
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
					'label'         => esc_html__( 'Image', 'tribe' ),
					'name'          => self::IMAGE,
					'type'          => 'image',
					'return_format' => 'array',
					'preview_size'  => 'medium',
					'wrapper'       => [
						'class' => 'tribe-acf-hide-label',
					],
				] )
			);
	}

	protected function get_citation_field_group(): Field_Group {
		$group = new Field_Group( self::NAME . '_' . self::QUOTE_GROUP, [
			'label'  => '',
			'name'   => self::QUOTE_GROUP,
			'layout' => 'block',
		]  );

		$text = new Field( self::NAME . '_' . self::QUOTE_TEXT, [
			'label' => esc_html__( 'Quote', 'tribe' ),
			'name'  => self::QUOTE_TEXT,
			'type'  => 'text',
		] );

		$name = new Field( self::NAME . '_' . self::CITE_NAME, [
			'label' => esc_html__( 'Name', 'tribe' ),
			'name'  => self::CITE_NAME,
			'type'  => 'text',
		] );

		$title = new Field( self::NAME . '_' . self::CITE_TITLE, [
			'label' => esc_html__( 'Title/Description', 'tribe' ),
			'name'  => self::CITE_TITLE,
			'type'  => 'text',
		] );

		$image = new Field( self::NAME . '_' . self::CITE_IMAGE, [
			'label'         => esc_html__( 'Photo', 'tribe' ),
			'name'          => self::CITE_IMAGE,
			'type'          => 'image',
			'return_format' => 'array',
			'preview_size'  => Image_Sizes::SQUARE_XSMALL,
		] );

		$group->add_field( $text );
		$group->add_field( $name );
		$group->add_field( $title );
		$group->add_field( $image );

		return $group;
	}

}
