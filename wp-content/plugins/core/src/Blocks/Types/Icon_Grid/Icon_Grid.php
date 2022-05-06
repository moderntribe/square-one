<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Icon_Grid;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Libs\ACF\Repeater;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Fields\Traits\With_Cta_Field;

class Icon_Grid extends Block_Config implements Cta_Field {

	use With_Cta_Field;

	public const NAME = 'icongrid';

	public const LAYOUT        = 'layout';
	public const LAYOUT_INLINE = 'inline';
	public const LAYOUT_LIST   = 'list';

	public const SECTION_ICONS    = 's-icons';
	public const SECTION_SETTINGS = 's-settings';

	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const LEADIN      = 'leadin';

	public const ICONS            = 'icons';
	public const ICON_IMAGE       = 'icon_image';
	public const ICON_TITLE       = 'icon_title';
	public const ICON_DESCRIPTION = 'icon_description';

	public function add_block(): void {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Icon Grid', 'tribe' ),
			'description' => __( 'A grid layout block with icon selections', 'tribe' ),
			'icon'        => '<svg enable-background="new 0 0 146.3 106.3" version="1.1" viewBox="0 0 146.3 106.3" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><style type="text/css">.st0{fill:#16D690;}.st1{fill:#21A6CB;}.st2{fill:#008F8F;}</style><polygon class="st0" points="145.2 106.3 72.6 42.3 26.5 1.2 0 106.3"/><polygon class="st1" points="145.2 106.3 0 106.3 72.6 42.3 118.6 1.2"/><polygon class="st2" points="72.6 42.3 145.2 106.3 0 106.3"/></svg>',
			'keywords'    => [ __( 'icon', 'tribe' ), __( 'grid', 'tribe' ) ],
			'category'    => 'common', // core categories: common, formatting, layout, widgets, embed
			'supports'    => [
				'align'  => false,
				'anchor' => true,
				'html'   => false,
			],
		] ) );
	}

	/**
	 * Register Fields for block
	 */
	public function add_fields(): void {
		$this->add_field(
			new Field( self::NAME . '_' . self::LAYOUT, [
				'label'         => esc_html__( 'Layout', 'tribe' ),
				'name'          => self::LAYOUT,
				'type'          => 'button_group',
				'choices'       => [
					self::LAYOUT_INLINE => esc_html__( 'Inline', 'tribe' ),
					self::LAYOUT_LIST   => esc_html__( 'List', 'tribe' ),
				],
				'default_value' => self::LAYOUT_INLINE,
				] )
		)->add_field(
			new Field( self::NAME . '_' . self::TITLE, [
					 'label' => esc_html__( 'Title', 'tribe' ),
					 'name'  => self::TITLE,
					 'type'  => 'text',
				 ] )
		)->add_field(
			new Field( self::NAME . '_' . self::LEADIN, [
					'label'   => esc_html__( 'Lead in', 'tribe' ),
					'name'    => self::LEADIN,
					'type'    => 'text',
					'wrapper' => [
						'class' => 'tribe-acf-hide-label',
					],
				 ] )
		)->add_field(
			new Field( self::NAME . '_' . self::DESCRIPTION, [
					'label'        => esc_html__( 'Description', 'tribe' ),
					'name'         => self::DESCRIPTION,
					'type'         => 'wysiwyg',
					'toolbar'      => Classic_Editor_Formats::MINIMAL,
					'tabs'         => 'visual',
					'media_upload' => false,
				 ] )
		)->add_field(
			$this->get_cta_field( self::NAME )
		);

		$this->add_section( new Field_Section( self::SECTION_ICONS, esc_html__( 'Icon Items', 'tribe' ), 'accordion' )
			 )->add_field(
				 $this->get_icon_section()
			 );
	}

	/**
	 * @return \Tribe\Libs\ACF\Repeater
	 */
	protected function get_icon_section(): Repeater {
		$group = new Repeater( self::NAME . '_' . self::ICONS, [
			'label'        => esc_html__( 'Icon Section', 'tribe' ),
			'name'         => self::ICONS,
			'layout'       => 'block',
			'min'          => 0,
			'max'          => 12,
			'button_label' => esc_html__( 'Add Icon Section', 'tribe' ),
			'wrapper'      => [
				'class' => 'tribe-acf-hide-label',
			],
		] );

		$group->add_field(
			new Field( self::ICON_IMAGE, [
				'label'         => esc_html__( 'Icon Image', 'tribe' ),
				'name'          => self::ICON_IMAGE,
				'type'          => 'image',
				'return_format' => 'id',
				'preview_size'  => 'medium',
				'instructions'  => esc_html__( 'Recommended image size: 100px wide with any aspect ratio.', 'tribe' ),
			] )
		)->add_field(
			new Field( self::ICON_TITLE, [
				'label' => esc_html__( 'Icon Title', 'tribe' ),
				'name'  => self::ICON_TITLE,
				'type'  => 'text',
			] )
		)->add_field(
			new Field( self::ICON_DESCRIPTION, [
				'label'        => esc_html__( 'Icon Description', 'tribe' ),
				'name'         => self::ICON_DESCRIPTION,
				'type'         => 'wysiwyg',
				'toolbar'      => Classic_Editor_Formats::MINIMAL,
				'tabs'         => 'visual',
				'media_upload' => false,
			] )
		)->add_field(
			$this->get_cta_field( self::NAME )
		);

		return $group;
	}

}
