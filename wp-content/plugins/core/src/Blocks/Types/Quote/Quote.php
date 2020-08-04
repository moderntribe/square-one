<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Quote;

use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Project\Theme\Config\Image_Sizes;

class Quote extends Block_Config {

	public const NAME = 'quote';

	public const IMAGE         = 'image';
	public const QUOTE         = 'text';
	public const CITE_NAME     = 'cite_name';
	public const CITE_TITLE    = 'cite_title';
	public const CITE_IMAGE    = 'cite_image';
	public const LAYOUT        = 'layout';
	public const MEDIA_LEFT    = 'left';
	public const MEDIA_RIGHT   = 'right';
	public const MEDIA_OVERLAY = 'overlay';

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
			'supports'    => [ 'align' => false ],
		] ) );
	}

	/**
	 * Register Fields for block
	 */
	public function add_fields() {
		$this->add_field( new Field( self::NAME . '_' . self::IMAGE, [
				'label'        => __( 'Image', 'tribe' ),
				'name'         => self::IMAGE,
				'type'         => 'image',
				'preview_size' => 'medium',
				'instructions' => __( 'Recommended image size by layout:<br>Overlay: 1920px wide with a 16:9 aspect ratio.<br>Left/Right: 1536px wide with a 4:3 aspect ratio.', 'tribe' ),
			] )
		)->add_field( new Field( self::NAME . '_' . self::QUOTE, [
				'label'        => __( 'Quotation', 'tribe' ),
				'name'         => self::QUOTE,
				'type'         => 'textarea',
				'rows'         => 4,
				'maxlength'    => 150,
				'instructions' => __( 'Limited to 150 characters.', 'tribe' ),
			] )
		)->add_field( new Field( self::NAME . '_' . self::CITE_NAME, [
				'label'        => __( 'Citation Line 1', 'tribe' ),
				'name'         => self::CITE_NAME,
				'type'         => 'text',
				'instructions' => __( 'Generally, the name of the person being quoted.', 'tribe' ),
			] )
		)->add_field( new Field( self::NAME . '_' . self::CITE_TITLE, [
				'label'        => __( 'Citation Line 2', 'tribe' ),
				'name'         => self::CITE_TITLE,
				'type'         => 'text',
				'instructions' => __( 'Generally, the professional title of the person being quoted.', 'tribe' ),
			] )
		)->add_field( new Field( self::NAME . '_' . self::CITE_IMAGE, [
				'label'        => __( 'Citation Image', 'tribe' ),
				'name'         => self::CITE_IMAGE,
				'type'         => 'image',
				'preview_size' => Image_Sizes::SQUARE_XSMALL,
				'instructions' => __( 'Generally, a profile image of the person being quoted.<br>Recommended image size: 150px wide with a 1:1 aspect ratio.', 'tribe' ),
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
				self::MEDIA_LEFT    => __( 'Image Left', 'tribe' ),
				self::MEDIA_OVERLAY => __( 'Image Overlay', 'tribe' ),
				self::MEDIA_RIGHT   => __( 'Image Right', 'tribe' ),
			],
			'default_value'   => [
				self::MEDIA_OVERLAY,
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
