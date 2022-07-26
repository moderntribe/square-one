<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Lead_Form;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
use Tribe\Project\Blocks\Block_Category;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Fields\Traits\With_Cta_Field;

class Lead_Form extends Block_Config implements Cta_Field {

	use With_Cta_Field;

	public const NAME = 'leadform';

	public const WIDTH       = 'width';
	public const WIDTH_GRID  = 'grid';
	public const WIDTH_FULL  = 'full';
	public const LEAD_IN     = 'leadin';
	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';

	public const SECTION_APPEARANCE = 's-appearance';
	public const LAYOUT             = 'layout';
	public const LAYOUT_LEFT        = 'left';
	public const LAYOUT_RIGHT       = 'right';
	public const LAYOUT_BOTTOM      = 'bottom';
	public const FORM_FIELDS        = 'form_fields';
	public const FORM_STACKED       = 'form_stacked';
	public const FORM_INLINE        = 'form_inline';
	public const BACKGROUND         = 'background';
	public const BACKGROUND_LIGHT   = 'background_light';
	public const BACKGROUND_DARK    = 'background_dark';

	/**
	 * Register the block
	 */
	public function add_block(): void {
		$this->set_block( new Block( self::NAME, [
			'title'    => esc_html__( 'Lead Form', 'tribe' ),
			'icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M2 7H22V17H2V7ZM0 7C0 5.89543 0.895431 5 2 5H22C23.1046 5 24 5.89543 24 7V17C24 18.1046 23.1046 19 22 19H2C0.895431 19 0 18.1046 0 17V7ZM6.5 8.25H3.25V9.5H4.25L4.25 14.75H3.25V16H6.5V14.75H5.5V9.5H6.5V8.25Z" fill="black"/></svg>',
			'keywords' => [ esc_html__( 'form', 'tribe' ), esc_html__( 'display', 'tribe' ) ],
			'category' => Block_Category::CUSTOM_BLOCK_CATEGORY_SLUG,
			'supports' => [
				'align'  => false,
				'anchor' => true,
				'jsx'    => true,
			],
			'example'  => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						self::LEAD_IN     => esc_html__( 'Lorem ipsum dolor sit amet.', 'tribe' ),
						self::TITLE       => esc_html__( 'The Lead Form Title', 'tribe' ),
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
		)->add_field(
			new Field( self::NAME . '_' . self::WIDTH, [
				'type'          => 'button_group',
				'name'          => self::WIDTH,
				'label'         => esc_html__( 'Container Width', 'tribe' ),
				'choices'       => [
					self::WIDTH_GRID => esc_html__( 'Grid', 'tribe' ),
					self::WIDTH_FULL => esc_html__( 'Full', 'tribe' ),
				],
				'default_value' => self::WIDTH_GRID,
			] )
		);

		$this->add_section( new Field_Section( self::SECTION_APPEARANCE, esc_html__( 'Appearance', 'tribe' ), 'accordion' ) )
			 ->add_field(
				 new Field( self::NAME . '_' . self::LAYOUT, [
					 'type'          => 'button_group',
					 'name'          => self::LAYOUT,
					 'label'         => esc_html__( 'Form Layout', 'tribe' ),
					 'choices'       => [
						 self::LAYOUT_LEFT   => esc_html__( 'Left', 'tribe' ),
						 self::LAYOUT_RIGHT  => esc_html__( 'Right', 'tribe' ),
						 self::LAYOUT_BOTTOM => esc_html__( 'Bottom', 'tribe' ),
					 ],
					 'default_value' => self::LAYOUT_LEFT,
				 ] )
			 )->add_field(
				 new Field( self::NAME . '_' . self::FORM_FIELDS, [
					'type'          => 'button_group',
					'name'          => self::FORM_FIELDS,
					'label'         => esc_html__( 'Form Field Position', 'tribe' ),
					'choices'       => [
						self::FORM_STACKED => esc_html__( 'Stacked', 'tribe' ),
						self::FORM_INLINE  => esc_html__( 'Inline', 'tribe' ),
					],
					'default_value' => self::FORM_STACKED,
				 ] )
			 )->add_field(
				 new Field( self::NAME . '_' . self::BACKGROUND, [
					'type'          => 'button_group',
					'name'          => self::BACKGROUND,
					'label'         => esc_html__( 'Background Color', 'tribe' ),
					'choices'       => [
						self::BACKGROUND_LIGHT => esc_html__( 'Light', 'tribe' ),
						self::BACKGROUND_DARK  => esc_html__( 'Dark', 'tribe' ),
					],
					'default_value' => self::BACKGROUND_LIGHT,
				 ] )
			 );
	}

}
