<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Logos;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Repeater;

class Logos extends Block_Config {
	public const NAME = 'logos';

	public const LEAD_IN     = 'leadin';
	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const CTA         = 'cta';

	public const LOGOS      = 'logos';
	public const LOGO_IMAGE = 'image';
	public const LOGO_LINK  = 'link';

	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Logos', 'tribe' ),
			'description' => __( 'A collection of logos.', 'tribe' ),
			'icon'        => 'screenoptions',
			'keywords'    => [ __( 'logos', 'tribe' ) ],
			'category'    => 'layout',
			'supports'    => [ 'align' => false ],
			'example' => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						self::LEAD_IN     => esc_html__( 'Lorem ipsum dolor sit amet.', 'tribe' ),
						self::TITLE       => esc_html__( 'Logos', 'tribe' ),
						self::DESCRIPTION => esc_html__(
							'Cras ut ornare dui, sed venenatis est. Donec euismod in leo quis consequat.',
							'tribe'
						),
						self::CTA => [
							'title'  => esc_html__( 'Lorem ipsum', 'tribe' ),
							'url'    => '#',
							'target' => '',
						],
						self::LOGOS => [
							[
								//Images are output as IDs so it's sort of hard to get an image value for preview
								self::LOGO_IMAGE => 0,
							],
							[
								//Images are output as IDs so it's sort of hard to get an image value for preview
								self::LOGO_IMAGE => 0,
							],
						],
					],
				],
			],
		] ) );
	}

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
		)->add_field(
			$this->get_logos_section()
		);
	}

	/**
	 * @return Repeater
	 */
	protected function get_logos_section() {
		$group = new Repeater( self::NAME . '_' . self::LOGOS );
		$group->set_attributes( [
			'label'        => __( 'Logos', 'tribe' ),
			'name'         => self::LOGOS,
			'layout'       => 'block',
			'min'          => 0,
			'max'          => 12,
			'button_label' => 'Add Logo',
		] );

		$logo_image = new Field( self::LOGO_IMAGE, [
			'label'         => __( 'Logo Image', 'tribe' ),
			'name'          => self::LOGO_IMAGE,
			'type'          => 'image',
			'return_format' => 'id',
			'preview_size'  => 'medium',
			'instructions'  => __( 'Recommended image size: 200px tall with any aspect ratio.', 'tribe' ),
		] );
		$group->add_field( $logo_image );

		$logo_link = new Field( self::LOGO_LINK, [
			'label' => __( 'Logo Link', 'tribe' ),
			'name'  => self::LOGO_LINK,
			'type'  => 'link',
		] );
		$group->add_field( $logo_link );

		return $group;
	}

	protected function add_settings() {
		// No settings.
	}

}
