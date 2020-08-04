<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Media_Text;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;

class Media_Text extends Block_Config {

	public const NAME = 'media-text';

	public const LAYOUT       = 'layout';
	public const MEDIA_LEFT   = 'left';
	public const MEDIA_RIGHT  = 'right';
	public const MEDIA_CENTER = 'center';
	public const WIDTH        = 'width';
	public const WIDTH_GRID   = 'grid';
	public const WIDTH_FULL   = 'full';

	public const TITLE   = 'title';
	public const CONTENT = 'content';
	public const CTA     = 'cta';

	public const MEDIA_TYPE = 'media_type';
	public const IMAGE      = 'image';
	public const EMBED      = 'embed';

	/**
	 * Register the block
	 */
	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Media + Text', 'tribe' ),
			'description' => __( 'An image or video plus text content with several layout options.', 'tribe' ),
			'icon'        => '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false"><path d="M13 17h8v-2h-8v2zM3 19h8V5H3v14zM13 9h8V7h-8v2zm0 4h8v-2h-8v2z"></path></svg>',
			'keywords'    => [ __( 'image', 'tribe' ), __( 'video', 'tribe' ), __( 'display', 'tribe' ), __( 'text', 'tribe' ) ],
			'category'    => 'layout',
			'supports'    => [ 'align' => false ],
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
		)->add_field( new Field( self::NAME . '_' . self::CONTENT, [
				'label'        => __( 'Description', 'tribe' ),
				'name'         => self::CONTENT,
				'type'         => 'wysiwyg',
				'toolbar'      => 'basic',
				'media_upload' => 0,
			] )
		)->add_field( new Field( self::NAME . '_' . self::CTA, [
				'label' => __( 'Call to Action', 'tribe' ),
				'name'  => self::CTA,
				'type'  => 'link',
			] )
		)->add_field( new Field( self::NAME . '_' . self::MEDIA_TYPE, [
				'label'   => __( 'Media Type', 'tribe' ),
				'name'    => self::MEDIA_TYPE,
				'type'    => 'radio',
				'choices' => [
					self::IMAGE => __( 'Image', 'tribe' ),
					self::EMBED => __( 'Video oEmbed', 'tribe' ),
				],
				'default_value'   => [
					self::IMAGE,
				],
			] )
		)->add_field( new Field( self::NAME . '_' . self::IMAGE, [
				'label'             => __( 'Image', 'tribe' ),
				'name'              => self::IMAGE,
				'type'              => 'image',
				'preview_size'      => 'medium',
				'instructions'      => __( 'Recommended image size by layout:<br>Center: 1920px wide with a 16:9 aspect ratio.<br>Left/Right: 1536px wide with a 4:3 aspect ratio.', 'tribe' ),
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
		);
	}

	/**
	 * Register Settings for Block
	 */
	public function add_settings() {
		$this->add_setting( new Field( self::NAME . '_' . self::LAYOUT, [
			'label'           => __( 'Layout', 'tribe' ),
			'type'            => 'image_select',
			'choices'         => [
				self::MEDIA_LEFT   => __( 'Media Left', 'tribe' ),
				self::MEDIA_CENTER => __( 'Media Stacked', 'tribe' ),
				self::MEDIA_RIGHT  => __( 'Media Right', 'tribe' ),
			],
			'default_value'   => [
				self::MEDIA_CENTER,
			],
			'multiple'        => 0,
			'image_path'      => sprintf(
				'%sassets/img/admin/blocks/%s/',
				trailingslashit( get_template_directory_uri() ),
				self::NAME
			),
			'image_extension' => 'svg',
		] ) )->add_setting( new Field( self::NAME . '_' . self::WIDTH, [
			'label'           => __( 'Width', 'tribe' ),
			'type'            => 'image_select',
			'choices'         => [
				self::WIDTH_GRID => __( 'Grid', 'tribe' ),
				self::WIDTH_FULL => __( 'Full', 'tribe' ),
			],
			'default_value'   => [
				self::WIDTH_GRID,
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
