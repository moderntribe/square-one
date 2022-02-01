<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Interstitial;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Fields\Traits\With_Cta_Field;

class Interstitial extends Block_Config implements Cta_Field {

	use With_Cta_Field;

	public const NAME = 'interstitial';

	public const LEADIN = 'leadin';
	public const TITLE  = 'title';

	public const SECTION_MEDIA = 's-media';
	public const IMAGE         = 'image';

	public const SECTION_APPEARANCE = 's-appearance';
	public const LAYOUT             = 'layout';
	public const LAYOUT_LEFT        = 'left';
	public const LAYOUT_CENTER      = 'center';

	public function add_block(): void {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Interstitial', 'tribe' ),
			'description' => __( 'Interstitial block', 'tribe' ),
			'icon'        => '<svg width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill="#fff" stroke="#000" stroke-linecap="round" stroke-linejoin="round" d="M.5.5h19v19H.5z"/><path d="M13.6 12.7H6.4v3.6h7.2v-3.6zM3.7 4.6h12.6v1.8H3.7zM6.4 7.3h7.2v1.8H6.4z" fill="#000"/><path d="M10.517 14.305H8.732v.17h1.785v-.17zM10.517 14.393v-.567l.298.284.299.283-.299.283-.298.283v-.566z" fill="#fff"/></svg>',
			'keywords'    => [ __( 'interstitial', 'tribe' ), __( 'display', 'tribe' ) ],
			'category'    => 'layout',
			'supports'    => [
				'align'  => false,
				'anchor' => true,
			],
			'example'     => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						self::TITLE     => esc_html__( 'The Interstitial Content', 'tribe' ),
						self::GROUP_CTA => [
							self::LINK => [
								'title'  => esc_html__( 'Lorem ipsum', 'tribe' ),
								'url'    => '#',
								'target' => '',
							],
						],
						//Images are output as IDs so it's sort of hard to get an image value for preview
						self::IMAGE     => 0,
					],
				],
			],
		] ) );
	}

	protected function add_fields(): void {
		$this->add_field( new Field( self::NAME . '_' . self::LEADIN, [
				'label'       => __( 'Leadin', 'tribe' ),
				'name'        => self::LEADIN,
				'type'        => 'text',
				'placeholder' => __( 'Leadin (optional)', 'tribe' ),
				'wrapper'     => [
					'class' => 'tribe-acf-hide-label',
				],
			] )
		)->add_field( new Field( self::NAME . '_' . self::TITLE, [
				'label'        => __( 'Content', 'tribe' ),
				'name'         => self::TITLE,
				'type'         => 'wysiwyg',
				'toolbar'      => Classic_Editor_Formats::MINIMAL,
				'tabs'         => 'visual',
				'media_upload' => 0,
			] )
		)->add_field(
			$this->get_cta_field( self::NAME )
		);

		$this->add_section( new Field_Section( self::SECTION_MEDIA, __( 'Media', 'tribe' ), 'accordion' ) )
			 ->add_field( new Field( self::NAME . '_' . self::IMAGE, [
				 'label'         => __( 'Background Image', 'tribe' ),
				 'name'          => self::IMAGE,
				 'type'          => 'image',
				 'return_format' => 'id',
			 ] ) );

		$this->add_section( new Field_Section( self::SECTION_APPEARANCE, __( 'Appearance', 'tribe' ), 'accordion' ) )
			 ->add_field( new Field( self::NAME . '_' . self::LAYOUT, [
				 'label'         => __( 'Text Alignment', 'tribe' ),
				 'type'          => 'button_group',
				 'name'          => self::LAYOUT,
				 'choices'       => [
					 self::LAYOUT_LEFT   => __( 'Left', 'tribe' ),
					 self::LAYOUT_CENTER => __( 'Center', 'tribe' ),
				 ],
				 'default_value' => self::LAYOUT_LEFT,
			 ] ) );
	}

}
