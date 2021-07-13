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

	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => esc_html__( 'Logos', 'tribe' ),
			'description' => esc_html__( 'A collection of logos.', 'tribe' ),
			'icon'        => 'screenoptions',
			'keywords'    => [ esc_html__( 'logos', 'tribe' ) ],
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

	public function add_fields() {
		$this->add_field( new Field( self::NAME . '_' . self::LEAD_IN, [
				'label'       => esc_html__( 'Lead in', 'tribe' ),
				'name'        => self::LEAD_IN,
				'type'        => 'text',
				'placeholder' => esc_html__( 'Leadin (optional)', 'tribe' ),
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

}
