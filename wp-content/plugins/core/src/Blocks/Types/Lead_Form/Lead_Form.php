<?php declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Lead_Form;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;

class Lead_Form extends Block_Config {
	public const NAME = 'leadform';

	public const SECTION_CONTENT = 's-content';
	public const LEAD_IN         = 'leadin';
	public const TITLE           = 'title';
	public const DESCRIPTION     = 'description';
	public const CTA             = 'cta';

	public const SECTION_SETTINGS = 's-settings';
	public const LAYOUT           = 'layout';
	public const LAYOUT_LEFT      = 'left';
	public const LAYOUT_RIGHT     = 'right';
	public const LAYOUT_BOTTOM    = 'bottom';

	public const WIDTH      = 'width';
	public const WIDTH_GRID = 'grid';
	public const WIDTH_FULL = 'full';

	public const PAGE_THEME_OVERRIDE = 'page_theme_override';
	public const COLOR_THEME         = 'color_theme';
	public const COLOR_THEME_DEFAULT = '#FFF'; // transparent.
	public const COLOR_THEME_CHOICES = [
		'#FFF'                    => 'Light',
		'#000'                    => 'Dark',
	];

	public const FORM_FIELDS  = 'form_fields';
	public const FORM_STACKED = 'form_stacked';
	public const FORM_INLINE  = 'form_inline';

	/**
	 * Register the block
	 */
	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Lead Form', 'tribe' ),
			'description' => __( 'A block with a form selector for a newsletter/lead form.', 'tribe' ),
			'icon'        => '<svg width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill="#fff" stroke="#000" stroke-linecap="round" stroke-linejoin="round" d="M.5.5h19v19H.5z"/><path fill="#000" d="M3 4h14v2H3zM5 7h10v2H5zM14 12h4v4h-4z"/><path fill="#fff" stroke="#000" d="M2.5 12.5h11v3h-11z"/></svg>',
			'keywords'    => [ __( 'form', 'tribe' ), __( 'display', 'tribe' ) ],
			'category'    => 'layout',
			'supports'    => [
				'align'  => false,
				'anchor' => true,
				'jsx'    => true,
			],
			'example'     => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						self::LEAD_IN     => esc_html__( 'Lorem ipsum dolor sit amet.', 'tribe' ),
						self::TITLE       => esc_html__( 'The Lead Form Title', 'tribe' ),
						self::DESCRIPTION => esc_html__(
							'Cras ut ornare dui, sed venenatis est. Donec euismod in leo quis consequat.',
							'tribe'
						),
						self::CTA         => [
							'title'  => esc_html__( 'Lorem ipsum', 'tribe' ),
							'url'    => '#',
							'target' => '',
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
		//==========================================
		// Content Fields
		//==========================================
		$this->add_section( new Field_Section( self::SECTION_CONTENT, __( 'Content', 'tribe' ), 'accordion' ) )
			->add_field( new Field( self::NAME . '_' . self::LEAD_IN, [
					'label' => __( 'Lead in', 'tribe' ),
					'name'  => self::LEAD_IN,
					'type'  => 'text',
				] )
			)->add_field( new Field( self::NAME . '_' . self::TITLE, [
					'label' => __( 'Title', 'tribe' ),
					'name'  => self::TITLE,
					'type'  => 'text',
				] )
			)->add_field( new Field( self::NAME . '_' . self::DESCRIPTION, [
					'label'        => __( 'Description', 'tribe' ),
					'name'         => self::DESCRIPTION,
					'type'         => 'wysiwyg',
					'toolbar'      => 'basic',
					'media_upload' => 0,
				] )
			)->add_field( new Field( self::NAME . '_' . self::CTA, [
					'label' => __( 'Call to Action', 'tribe' ),
					'name'  => self::CTA,
					'type'  => 'link',
				] )
			);

		//==========================================
		// Setting Fields
		//==========================================
		$this->add_section( new Field_Section( self::SECTION_SETTINGS, __( 'Settings', 'tribe' ), 'accordion' ) )
			->add_field(
				new Field( self::NAME . '_' . self::WIDTH, [
				'type'            => 'radio',
				'name'            => self::WIDTH,
				'label'           => __( 'Container Width', 'tribe' ),
				'choices'         => [
					self::WIDTH_GRID => __( 'Grid', 'tribe' ),
					self::WIDTH_FULL => __( 'Full', 'tribe' ),
				],
				'default_value'   => self::WIDTH_GRID,
				] )
			)->add_field(
				new Field( self::NAME . '_' . self::PAGE_THEME_OVERRIDE, [
					'type'  => 'true_false',
					'name'  => self::PAGE_THEME_OVERRIDE,
					'label' => __( 'Override Page Theme', 'tribe' ),
					'ui'    => true,
				] )
			)->add_field(
				new Field( self::NAME . '_' . self::COLOR_THEME, [
					'type'              => 'swatch',
					'name'              => self::COLOR_THEME,
					'label'             => __( 'Color Theme', 'tribe' ),
					'choices'           => self::COLOR_THEME_CHOICES,
					'allow_null'        => false,
					'allow_other'       => true,
					'default_value'     => self::COLOR_THEME_DEFAULT,
					'conditional_logic' => [
						[
							[
								'field'    => 'field_' . self::NAME . '_' . self::PAGE_THEME_OVERRIDE,
								'operator' => '==',
								'value'    => '1',
							]
						]
					],
				] )
			)->add_field(
				new Field( self::NAME . '_' . self::LAYOUT, [
					'type'            => 'image_select',
					'name'            => self::LAYOUT,
					'label'           => __( 'Form Layout', 'tribe' ),
					'choices'         => [
						self::LAYOUT_LEFT   => __( 'Form Left', 'tribe' ),
						self::LAYOUT_RIGHT  => __( 'Form Right', 'tribe' ),
						self::LAYOUT_BOTTOM => __( 'Form Bottom', 'tribe' ),
					],
					'default_value'   => self::LAYOUT_BOTTOM,
					'multiple'        => 0,
					'image_path'      => sprintf(
						'%sassets/img/admin/blocks/%s/',
						trailingslashit( get_template_directory_uri() ),
						self::NAME
					),
					'image_extension' => 'svg',
				] )
			)->add_field(
				new Field( self::NAME . '_' . self::FORM_FIELDS, [
					'type'            => 'image_select',
					'name'            => self::FORM_FIELDS,
					'label'           => __( 'Form Field Position', 'tribe' ),
					'choices'         => [
						self::FORM_STACKED   => __( 'Stacked', 'tribe' ),
						self::FORM_INLINE    => __( 'Inline', 'tribe' ),
					],
					'default_value'   => self::FORM_STACKED,
					'multiple'        => 0,
					'image_path'      => sprintf(
						'%sassets/img/admin/blocks/%s/',
						trailingslashit( get_template_directory_uri() ),
						self::NAME
					),
					'image_extension' => 'svg',
				] )
			);
	}

}
