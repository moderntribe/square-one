<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Hero;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;

class Hero extends Block_Config {
	public const NAME = 'hero';

	public const IMAGE = 'image';

	public const LEAD_IN     = 'leadin';
	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const CTA         = 'cta';

	public const LAYOUT        = 'layout';
	public const LAYOUT_LEFT   = 'left';
	public const LAYOUT_CENTER = 'center';

	/**
	 * Register the block
	 */
	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Hero', 'tribe' ),
			'description' => __( 'Hero block', 'tribe' ),
			'icon'        => '<svg width="28" height="19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.254 5.968H19.85v1.054H8.254V5.968zm1.462 1.57h8.568v1.053H9.716V7.538z" fill="#000"/><path d="M2.82 2.437v14.126H25.2V2.437H2.82zm21.482 13.176H3.866V3.366h20.436v12.247z" fill="#000"/><path d="M10.092 16.15H2.884l10.614-3.242 1.693 1.012 5.433-3.243 4.409 2.623v2.85h-14.94z" fill="#000"/></svg>',
			'keywords'    => [ __( 'hero', 'tribe' ), __( 'display', 'tribe' ) ],
			'category'    => 'layout',
			'supports'    => [ 'align' => false ],
			'example'     => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						self::LEAD_IN       => esc_html__( 'Lorem ipsum dolor sit amet.', 'tribe' ),
						self::TITLE         => esc_html__( 'The Accordion Title', 'tribe' ),
						self::DESCRIPTION   => esc_html__(
							'Cras ut ornare dui, sed venenatis est. Donec euismod in leo quis consequat.',
							'tribe'
						),
						self::CTA    => [
							'title'  => esc_html__( 'Lorem ipsum', 'tribe' ),
							'url'    => '#',
							'target' => '',
						],
						//Images are output as IDs so it's sort of hard to get an image value for preview
						self::IMAGE  => 0,
					],
				],
			],
		] ) );
	}

	/**
	 * Register Fields for block
	 */
	public function add_fields() {
		$this->add_field( new Field( self::NAME . '_' . self::TITLE, [
				'label' => __( 'Title', 'tribe' ),
				'name'  => self::TITLE,
				'type'  => 'text',
			] )
		)->add_field( new Field( self::NAME . '_' . self::LEAD_IN, [
				'label' => __( 'Lead in', 'tribe' ),
				'name'  => self::LEAD_IN,
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
		)->add_field( new Field( self::NAME . '_' . self::IMAGE, [
				'label'        => __( 'Image', 'tribe' ),
				'name'         => self::IMAGE,
				'type'         => 'image',
				'return_format' => 'id',
				'instructions' => __( 'Recommended image size: 1440px wide and 720px high', 'tribe' ),
			] )
		);
	}

	/**
	 * Register Settings for Block
	 */
	public function add_settings() {
		$this->add_setting( new Field( self::NAME . '_' . self::LAYOUT, [
			'type'            => 'image_select',
			'name'            => self::LAYOUT,
			'choices'         => [
				self::LAYOUT_LEFT   => __( 'Align Left', 'tribe' ),
				self::LAYOUT_CENTER => __( 'Align Center', 'tribe' ),
			],
			'default_value'   => [
				self::LAYOUT_CENTER,
			],
			'multiple'        => 0,
			'image_path'      => sprintf(
				'%sassets/img/admin/blocks/%s/',
				trailingslashit( get_template_directory_uri() ),
				self::NAME
			),
			'image_extension' => 'svg',
		] ) );
	}

}
