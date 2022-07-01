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
			'title'    => esc_html__( 'Quote + Image', 'tribe' ),
			'icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.42104 2.27678C8.27217 1.93539 7.87751 1.79336 7.54814 1.92281C6.91876 2.17069 6.42875 2.57121 6.10095 3.12272C5.776 3.66942 5.625 4.33931 5.625 5.10734V7.96449C5.625 8.34666 5.94479 8.6252 6.3 8.6252H8.7C9.05521 8.6252 9.375 8.34666 9.375 7.96449V5.10734C9.375 4.72542 9.05533 4.44663 8.7 4.44663H7.03123C7.08486 4.16251 7.17537 3.93143 7.29607 3.7447C7.46613 3.48161 7.7113 3.2831 8.05236 3.14882C8.39048 3.01539 8.57407 2.62968 8.42134 2.27748L8.42104 2.27678ZM6.60864 4.82163C6.60864 4.82164 6.60864 4.82164 6.60864 4.82165H8.7C8.84496 4.82165 8.96592 4.91958 8.99391 5.04976C8.96593 4.91957 8.84497 4.82163 8.7 4.82163H6.60864ZM6.04712 4.42789C6.2042 3.36268 6.75331 2.63891 7.6853 2.27185C7.83794 2.21186 8.01372 2.28091 8.0773 2.4267C8.08916 2.45405 8.09624 2.48215 8.09891 2.51014C8.09624 2.48214 8.08916 2.45404 8.0773 2.42668C8.01372 2.28089 7.83794 2.21183 7.6853 2.27182C6.7533 2.63889 6.20419 3.36267 6.04712 4.42789ZM3.17103 2.27678C3.02216 1.93539 2.62809 1.7935 2.29848 1.92267C1.6691 2.17056 1.17875 2.57121 0.850946 3.12272C0.526004 3.66942 0.375 4.33931 0.375 5.10734V7.96449C0.375 8.34666 0.69479 8.6252 1.05 8.6252H3.45C3.80521 8.6252 4.125 8.34666 4.125 7.96449V5.10734C4.125 4.72542 3.80533 4.44663 3.45 4.44663H1.78123C1.83486 4.16252 1.92537 3.93143 2.04607 3.7447C2.21613 3.48161 2.4613 3.2831 2.80236 3.14883C3.14048 3.01539 3.32407 2.62968 3.17134 2.27748L3.17103 2.27678ZM1.35864 4.82163V4.82165H3.45C3.61567 4.82165 3.75 4.94958 3.75 5.10737V5.10734C3.75 4.94956 3.61568 4.82163 3.45 4.82163H1.35864ZM0.785498 4.51302C0.925455 3.40117 1.47869 2.64861 2.4353 2.27185C2.58838 2.21186 2.76372 2.28091 2.8273 2.4267C2.84013 2.4563 2.84737 2.48677 2.84947 2.51704C2.84737 2.48676 2.84014 2.45629 2.8273 2.42668C2.76372 2.28089 2.58838 2.21183 2.4353 2.27182C1.47869 2.64859 0.92545 3.40115 0.785498 4.51302ZM11.25 7.50022L22.5 7.50022V19.5002H7.5V10.5002H6V19.5002C6 20.3287 6.67157 21.0002 7.5 21.0002H22.5C23.3284 21.0002 24 20.3286 24 19.5002V7.50022C24 6.6718 23.3284 6.00022 22.5 6.00022H11.25V7.50022ZM17.3919 10.5138C17.621 10.5579 17.8165 10.7061 17.9209 10.9148L20.9209 16.9148C21.0371 17.1473 21.0247 17.4234 20.888 17.6445C20.7514 17.8656 20.51 18.0002 20.25 18.0002H10.5C10.1886 18.0002 9.90965 17.8078 9.79899 17.5168C9.68833 17.2257 9.76903 16.8965 10.0018 16.6897L16.7518 10.6897C16.9261 10.5347 17.1627 10.4696 17.3919 10.5138ZM12.4727 16.5002H19.0365L17.0169 12.4609L12.4727 16.5002ZM13.5 11.2502C13.5 12.0787 12.8284 12.7502 12 12.7502C11.1716 12.7502 10.5 12.0787 10.5 11.2502C10.5 10.4218 11.1716 9.75022 12 9.75022C12.8284 9.75022 13.5 10.4218 13.5 11.2502Z" fill="black"/></svg>',
			'keywords' => [ esc_html__( 'quotation', 'tribe' ), esc_html__( 'display', 'tribe' ), esc_html__( 'text', 'tribe' ) ],
			'category' => 'tribe-custom',
			'supports' => [
				'align'  => false,
				'anchor' => true,
			],
			'example'  => [
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
