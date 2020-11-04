<?php

namespace Tribe\Project\Blocks\Types\Content_Columns;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Libs\ACF\Repeater;

class Content_Columns extends Block_Config {
	public const NAME = 'contentcolumns';

	public const SECTION_CONTENT = 's-content';
	public const LEADIN          = 'leadin';
	public const TITLE           = 'title';
	public const DESCRIPTION     = 'description';
	public const CTA             = 'cta';
	public const COLUMNS         = 'columns';
	public const COLUMN_TITLE    = 'col_title';
	public const COLUMN_CONTENT  = 'col_content';
	public const COLUMN_CTA      = 'col_cta';

	public const SECTION_SETTINGS     = 's-settings';
	public const CONTENT_ALIGN        = 'content-align';
	public const CONTENT_ALIGN_LEFT   = 'left';
	public const CONTENT_ALIGN_CENTER = 'center';

	/**
	 * Add our block
	 */
	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Content Columns', 'tribe' ),
			'description' => __( 'Blocks of content arranged in a grid', 'tribe' ),
			'icon'        => '<svg width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill="#fff" stroke="#000" stroke-linecap="round" stroke-linejoin="round" d="M.5.5h19v19H.5z"/><path fill="#151515" d="M2.44 3.52H8.2v.72H2.44zM10.72 3.52h5.76v.72h-5.76zM10.72 11.08h5.76v.72h-5.76zM2.08 11.08h5.76v.72H2.08zM2.44 4.96h4.32v.72H2.44zM10.72 4.96h4.32v.72h-4.32zM10.72 12.52h4.32v.72h-4.32zM2.08 12.52H6.4v.72H2.08zM2.44 6.4h6.48v.72H2.44zM10.72 6.4h6.48v.72h-6.48zM10.72 13.96h6.48v.72h-6.48zM2.08 13.96h6.48v.72H2.08zM2.44 7.84h2.88v.72H2.44zM10.72 7.84h2.88v.72h-2.88zM10.72 15.4h2.88v.72h-2.88zM2.08 15.4h2.88v.72H2.08z"/></svg>',
			'keywords'    => [ __( 'content', 'tribe' ), __( 'display', 'tribe' ) ],
			'category'    => 'layout',
			'supports'    => [ 'align' => false ],
			'example'     => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						self::COLUMNS => [
							[
								self::COLUMN_TITLE   => esc_html__( 'Lorem ipsum dolor sit amet.', 'tribe' ),
								self::COLUMN_CONTENT => esc_html__(
									'Cras ut ornare dui, sed venenatis est. Donec euismod in leo quis consequat.',
									'tribe'
								),
								self::COLUMN_CTA     => [
									'title'  => esc_html__( 'Lorem ipsum', 'tribe' ),
									'url'    => '#',
									'target' => '',
								],
							],
						],

					],
				],
			],
		] ) );
	}

	/**
	 * Add Fields
	 */
	protected function add_fields() {
		//==========================================
		// Content Fields
		//==========================================
		$this->add_section( new Field_Section( self::SECTION_CONTENT, __( 'Content', 'tribe' ), 'accordion' ) )
			 ->add_field( new Field( self::NAME . '_' . self::TITLE, [
					 'label' => __( 'Title', 'tribe' ),
					 'name'  => self::TITLE,
					 'type'  => 'text',
				 ] )
			 )->add_field( new Field( self::NAME . '_' . self::LEADIN, [
					'label' => __( 'Lead in', 'tribe' ),
					'name'  => self::LEADIN,
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
			] ) )->add_field( $this->get_links_section() );

		//==========================================
		// Setting Fields
		//==========================================
		$this->add_section( new Field_Section( self::SECTION_SETTINGS, __( 'Settings', 'tribe' ), 'accordion' ) )
			 ->add_field( new Field( self::NAME . '_' . self::CONTENT_ALIGN, [
				 'label'           => __( 'Content Alignment', 'tribe' ),
				 'type'            => 'image_select',
				 'name'            => self::CONTENT_ALIGN,
				 'choices'         => [
					 self::CONTENT_ALIGN_CENTER => __( 'Center', 'tribe' ),
					 self::CONTENT_ALIGN_LEFT   => __( 'Left', 'tribe' ),
				 ],
				 'default_value'   => self::CONTENT_ALIGN_CENTER,
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
	protected function get_links_section() {
		$group = new Repeater( self::NAME . '_' . self::COLUMNS );
		$group->set_attributes( [
			'label'  => __( 'Columns', 'tribe' ),
			'name'   => self::COLUMNS,
			'layout' => 'block',
			'min'    => 1,
			'max'    => 3,
		] );

		$text = new Field( self::COLUMN_TITLE, [
			'label' => __( 'Title', 'tribe' ),
			'name'  => self::COLUMN_TITLE,
			'type'  => 'text',
		] );

		$content = new Field( self::COLUMN_CONTENT, [
			'label'        => __( 'Content', 'tribe' ),
			'name'         => self::COLUMN_CONTENT,
			'type'         => 'wysiwyg',
			'toolbar'      => 'basic',
			'media_upload' => 0,
		] );

		$cta = new Field( self::COLUMN_CTA, [
			'label' => __( 'Call to Action', 'tribe' ),
			'name'  => self::COLUMN_CTA,
			'type'  => 'link',
		] );

		$group->add_field( $text );
		$group->add_field( $content );
		$group->add_field( $cta );

		return $group;
	}
}
