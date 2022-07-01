<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Logos;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Libs\ACF\Repeater;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Fields\Traits\With_Cta_Field;

class Logos extends Block_Config implements Cta_Field {

	use With_Cta_Field;

	public const NAME = 'logos';

	public const LEAD_IN     = 'leadin';
	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';

	public const SECTION_LOGOS = 's-logos';
	public const LOGOS         = 'logos';
	public const LOGO_IMAGE    = 'image';
	public const LOGO_LINK     = 'link';

	public function add_block(): void {
		$this->set_block( new Block( self::NAME, [
			'title'    => esc_html__( 'Logos', 'tribe' ),
			'icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 14.6734H3.96413V13.4096H1.46382V9.09092H0V14.6734Z" fill="black"/><path d="M10.5857 11.8822C10.5857 10.2092 9.46737 9 7.84899 9C6.2397 9 5.13047 10.2092 5.13047 11.8822C5.13047 13.546 6.2397 14.7644 7.84899 14.7644C9.46737 14.7644 10.5857 13.546 10.5857 11.8822ZM9.11278 11.8822C9.11278 12.8459 8.60363 13.4824 7.84899 13.4824C7.09435 13.4824 6.60338 12.8459 6.60338 11.8822C6.60338 10.9184 7.09435 10.282 7.84899 10.282C8.60363 10.282 9.11278 10.9184 9.11278 11.8822Z" fill="black"/><path d="M15.7451 12.7823C15.6087 13.2096 15.245 13.4824 14.6904 13.4824C13.8903 13.4824 13.3993 12.8368 13.3993 11.8822C13.3993 10.9548 13.863 10.282 14.6722 10.282C15.1995 10.282 15.4996 10.5366 15.6541 10.9639H17.1452C16.9361 9.82738 16.1087 9 14.6722 9C13.072 9 11.9264 10.182 11.9264 11.8822C11.9264 13.5824 13.072 14.7644 14.6904 14.7644C16.1724 14.7644 17.1907 13.7915 17.1907 12.2459V11.6912H14.8813V12.7823H15.7451Z" fill="black"/><path d="M24 11.8822C24 10.2092 22.8817 9 21.2633 9C19.654 9 18.5448 10.2092 18.5448 11.8822C18.5448 13.546 19.654 14.7644 21.2633 14.7644C22.8817 14.7644 24 13.546 24 11.8822ZM22.5271 11.8822C22.5271 12.8459 22.0179 13.4824 21.2633 13.4824C20.5087 13.4824 20.0177 12.8459 20.0177 11.8822C20.0177 10.9184 20.5087 10.282 21.2633 10.282C22.0179 10.282 22.5271 10.9184 22.5271 11.8822Z" fill="black"/></svg>',
			'keywords' => [ esc_html__( 'logos', 'tribe' ) ],
			'category' => 'tribe-custom',
			'supports' => [
				'align'  => false,
				'anchor' => true,
			],
			'example'  => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						self::LEAD_IN     => esc_html__( 'Lorem ipsum dolor sit amet.', 'tribe' ),
						self::TITLE       => esc_html__( 'Logos', 'tribe' ),
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
						self::LOGOS       => [
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
		);

		$this->add_section( new Field_Section( self::SECTION_LOGOS, esc_html__( 'Logos', 'tribe' ), 'accordion' ) )
			->add_field( $this->get_logos_section() );
	}

	/**
	 * @return \Tribe\Libs\ACF\Repeater
	 */
	protected function get_logos_section(): Repeater {
		$group = new Repeater( self::NAME . '_' . self::LOGOS );
		$group->set_attributes( [
			'label'        => esc_html__( 'Logos', 'tribe' ),
			'name'         => self::LOGOS,
			'layout'       => 'block',
			'min'          => 0,
			'max'          => 12,
			'button_label' => esc_html__( 'Add Logo', 'tribe' ),
			'wrapper'      => [
				'class' => 'tribe-acf-hide-label',
			],
		] );

		$logo_image = new Field( self::LOGO_IMAGE, [
			'label'         => esc_html__( 'Logo Image', 'tribe' ),
			'name'          => self::LOGO_IMAGE,
			'type'          => 'image',
			'return_format' => 'array',
			'preview_size'  => 'medium',
			'instructions'  => esc_html__( 'Recommended image size: 200px tall with any aspect ratio.', 'tribe' ),
		] );

		$group->add_field( $logo_image );

		$logo_link = new Field( self::LOGO_LINK, [
			'label'       => esc_html__( 'Logo Link', 'tribe' ),
			'name'        => self::LOGO_LINK,
			'type'        => 'link',
			'return_type' => 'array',
		] );

		$group->add_field( $logo_link );

		return $group;
	}

}
