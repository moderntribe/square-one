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
			'title'    => esc_html__( 'Interstitial', 'tribe' ),
			'icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.5 4.5H22.5V19.3782L19.4641 13.8871C19.3573 13.6941 19.1711 13.5578 18.9548 13.5146C18.7385 13.4713 18.5141 13.5255 18.3414 13.6626L10.9885 19.5H1.5V4.5ZM11.2439 21C11.2483 21 11.2527 21 11.2571 21H22.1114C22.1209 21.0002 22.1305 21.0002 22.14 21H22.5C23.3284 21 24 20.3284 24 19.5V4.5C24 3.67157 23.3284 3 22.5 3H1.5C0.671573 3 0 3.67157 0 4.5V19.5C0 20.3284 0.671573 21 1.5 21H11.2439ZM20.8534 19.5H13.401L18.5801 15.3883L20.8534 19.5ZM11.25 6.75H3.75V8.25H11.25V6.75ZM3.75 9.75H12.75V11.25H3.75V9.75ZM8.25 12.75H3.75V14.25H8.25V12.75ZM12.75 15.75C13.5784 15.75 14.25 15.0784 14.25 14.25C14.25 13.4216 13.5784 12.75 12.75 12.75C11.9216 12.75 11.25 13.4216 11.25 14.25C11.25 15.0784 11.9216 15.75 12.75 15.75Z" fill="black"/></svg>',
			'keywords' => [ esc_html__( 'interstitial', 'tribe' ), esc_html__( 'display', 'tribe' ) ],
			'category' => 'tribe-custom',
			'supports' => [
				'align'  => false,
				'anchor' => true,
			],
			'example'  => [
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
				'label' => esc_html__( 'Overline', 'tribe' ),
				'name'  => self::LEADIN,
				'type'  => 'text',
			] )
		)->add_field( new Field( self::NAME . '_' . self::TITLE, [
				'label'        => esc_html__( 'Description', 'tribe' ),
				'name'         => self::TITLE,
				'type'         => 'wysiwyg',
				'toolbar'      => Classic_Editor_Formats::MINIMAL,
				'tabs'         => 'visual',
				'media_upload' => 0,
			] )
		)->add_field(
			$this->get_cta_field( self::NAME )
		);

		$this->add_section( new Field_Section( self::SECTION_MEDIA, esc_html__( 'Media', 'tribe' ), 'accordion' ) )
			 ->add_field( new Field( self::NAME . '_' . self::IMAGE, [
				 'label'         => esc_html__( 'Background Image', 'tribe' ),
				 'name'          => self::IMAGE,
				 'type'          => 'image',
				 'return_format' => 'array',
			 ] ) );

		$this->add_section( new Field_Section( self::SECTION_APPEARANCE, esc_html__( 'Appearance', 'tribe' ), 'accordion' ) )
			 ->add_field( new Field( self::NAME . '_' . self::LAYOUT, [
				 'label'         => esc_html__( 'Text Alignment', 'tribe' ),
				 'type'          => 'button_group',
				 'name'          => self::LAYOUT,
				 'choices'       => [
					 self::LAYOUT_LEFT   => esc_html__( 'Left', 'tribe' ),
					 self::LAYOUT_CENTER => esc_html__( 'Center', 'tribe' ),
				 ],
				 'default_value' => self::LAYOUT_LEFT,
			 ] ) );
	}

}
