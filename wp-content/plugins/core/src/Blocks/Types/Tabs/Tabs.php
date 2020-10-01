<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Tabs;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Libs\ACF\Repeater;

class Tabs extends Block_Config {
	public const NAME = 'tabs';

	public const LEAD_IN     = 'leadin';
	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const CTA         = 'cta';

	public const TABS        = 'tabs';
	public const TAB_LABEL   = 'tab_label';
	public const TAB_CONTENT = 'tab_content';

	public const LAYOUT            = 'layout';
	public const LAYOUT_HORIZONTAL = 'horizontal';
	public const LAYOUT_VERTICAL   = 'vertical';

	/**
	 * Register the block
	 */
	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Tabs', 'tribe' ),
			'description' => __( 'Tab block', 'tribe' ),
			'icon'        => '<svg width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill="#fff" stroke="#000" stroke-linecap="round" stroke-linejoin="round" d="M.5.5h19v19H.5z"/><path fill="#000" d="M1 1h6v4H1z"/><path fill="#fff" stroke="#000" d="M7.5 1.5h5v3h-5zM13.5 1.5h5v3h-5zM1.5 4.5h17v14h-17z"/><path fill="#151515" d="M3 6h12v1H3zM3 8h9v1H3zM3 10h13v1H3zM3 12h6v1H3z"/></svg>',
			'keywords'    => [ __( 'tabs', 'tribe' ), __( 'display', 'tribe' ) ],
			'category'    => 'layout',
			'supports'    => [ 'align' => false ],
			'example'     => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						self::TITLE       => esc_html__( 'A Tabs Block', 'tribe' ),
						self::LEAD_IN     => esc_html__( 'Suspendisse potenti', 'tribe' ),
						self::DESCRIPTION => esc_html__(
							'Pellentesque diam diam, aliquet non mauris eu, posuere mollis urna. Nulla eget congue ligula, a aliquam lectus. Duis non diam maximus justo dictum porttitor in in risus.',
							'tribe'
						),
						self::CTA         => [
							'title' => esc_html__( 'Call to Action', 'tribe' ),
							'url'   => '#',
						],
						self::TABS        => [
							[
								self::TAB_LABEL   => esc_html__( 'Tab One', 'tribe' ),
								self::TAB_CONTENT => sprintf(
									'<p>%s</p>',
									esc_html__(
										'Sed aliquet quam posuere tellus convallis molestie. Aliquam neque tellus, viverra in augue ut, facilisis accumsan elit. Cras id convallis libero. Proin tincidunt elit quis bibendum faucibus. Pellentesque porttitor molestie eros at placerat. Morbi ac odio nec dolor commodo semper. Cras auctor euismod velit efficitur volutpat.',
										'tribe'
									)
								),
							],
							[
								self::TAB_LABEL   => esc_html__( 'Tab Two', 'tribe' ),
								self::TAB_CONTENT => sprintf(
									'<p>%s</p>',
									esc_html__(
										'Sed aliquet quam posuere tellus convallis molestie. Aliquam neque tellus, viverra in augue ut, facilisis accumsan elit. Cras id convallis libero. Proin tincidunt elit quis bibendum faucibus. Pellentesque porttitor molestie eros at placerat. Morbi ac odio nec dolor commodo semper. Cras auctor euismod velit efficitur volutpat.',
										'tribe'
									)
								),
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
	public function add_fields() {
		//==========================================
		// Content Fields
		//==========================================
		$this->add_section( new Field_Section( __( 'Content', 'tribe' ), 'accordion' ) )
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
			)->add_field( $this->get_tab_section() );

		//==========================================
		// Settings Fields
		//==========================================
		$this->add_section( new Field_Section( __( 'Settings', 'tribe' ), 'accordion' ) )
			 ->add_field( new Field( self::NAME . '_' . self::LAYOUT, [
				 'type'            => 'image_select',
				 'name'            => self::LAYOUT,
				 'choices'         => [
					 self::LAYOUT_VERTICAL   => __( 'Vertical', 'tribe' ),
					 self::LAYOUT_HORIZONTAL => __( 'Horizontal', 'tribe' ),
				 ],
				 'default_value'   => self::LAYOUT_HORIZONTAL,
				 'multiple'        => 0,
				 'image_path'      => sprintf(
					 '%sassets/img/admin/blocks/%s/',
					 trailingslashit( get_template_directory_uri() ),
					 self::NAME
				 ),
				 'image_extension' => 'svg',
			 ] ) );
	}

	/**
	 * @return Repeater
	 */
	protected function get_tab_section() {
		$group = new Repeater( self::NAME . '_' . self::TABS );
		$group->set_attributes( [
			'label'        => __( 'Tab Section', 'tribe' ),
			'name'         => self::TABS,
			'layout'       => 'block',
			'min'          => 0,
			'max'          => 10,
			'button_label' => __( 'Add Tab', 'tribe' ),
		] );
		$header = new Field( self::TAB_LABEL, [
			'label' => __( 'Tab Label', 'tribe' ),
			'name'  => self::TAB_LABEL,
			'type'  => 'text',
		] );

		$group->add_field( $header );
		$content = new Field( self::TAB_CONTENT, [
			'label' => __( 'Tab Content', 'tribe' ),
			'name'  => self::TAB_CONTENT,
			'type'  => 'wysiwyg',
			'delay' => 1,
		] );
		$group->add_field( $content );

		return $group;
	}

}
