<?php
declare( strict_types=1 );

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

	public const FORM = 'form';

	public const SECTION_SETTINGS = 's-settings';
	public const LAYOUT           = 'layout';
	public const LAYOUT_CENTER    = 'center';
	public const LAYOUT_LEFT      = 'left';

	public const WIDTH      = 'width';
	public const WIDTH_GRID = 'grid';
	public const WIDTH_FULL = 'full';

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
						self::FORM        => 1 //Will work if a site has a form added. Be blank if not.
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
			)->add_field( new Field( self::NAME . '_' . self::FORM, [
					'label'   => __( 'Form', 'tribe' ),
					'name'    => self::FORM,
					'type'    => 'select',
					'choices' => $this->get_form_options(),
				] )
			);

		//==========================================
		// Setting Fields
		//==========================================
		$this->add_section( new Field_Section( self::SECTION_SETTINGS, __( 'Settings', 'tribe' ), 'accordion' ) )
			 ->add_field(
				 new Field( self::NAME . '_' . self::LAYOUT, [
					 'type'            => 'image_select',
					 'name'            => self::LAYOUT,
					 'choices'         => [
						 self::LAYOUT_LEFT   => __( 'Content Left', 'tribe' ),
						 self::LAYOUT_CENTER => __( 'Content Center', 'tribe' ),
					 ],
					 'default_value'   => self::LAYOUT_CENTER,
					 'multiple'        => 0,
					 'image_path'      => sprintf(
						 '%sassets/img/admin/blocks/%s/',
						 trailingslashit( get_template_directory_uri() ),
						 self::NAME
					 ),
					 'image_extension' => 'svg',
				 ] )
			 )->add_field(
				 new Field( self::NAME . '_' . self::WIDTH, [
					'type'            => 'image_select',
					'name'            => self::WIDTH,
					'choices'         => [
						self::WIDTH_GRID => __( 'Grid', 'tribe' ),
						self::WIDTH_FULL => __( 'Full', 'tribe' ),
					],
					'default_value'   => self::WIDTH_GRID,
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

	/**
	 * @return array
	 */
	protected function get_form_options() {
		if ( ! class_exists( 'GFFormsModel' ) ) {
			return [];
		}
		$choices   = [];
		$choices[] = __( 'Select One', 'tribe' );
		foreach ( \GFFormsModel::get_forms() as $form ) {
			$choices[ $form->id ] = $form->title;
		}

		return $choices;
	}


}
