<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Icon_Grid;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Libs\ACF\Repeater;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
use Tribe\Project\Blocks\Block_Category;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Fields\Traits\With_Cta_Field;

class Icon_Grid extends Block_Config implements Cta_Field {

	use With_Cta_Field;

	public const NAME = 'icongrid';

	public const LAYOUT        = 'layout';
	public const LAYOUT_INLINE = 'inline';
	public const LAYOUT_LIST   = 'list';

	public const SECTION_ICONS = 's-icons';

	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const LEADIN      = 'leadin';

	public const ICONS            = 'icons';
	public const ICON_IMAGE       = 'icon_image';
	public const ICON_TITLE       = 'icon_title';
	public const ICON_DESCRIPTION = 'icon_description';

	public function add_block(): void {
		$this->set_block( new Block( self::NAME, [
			'title'    => esc_html__( 'Icon Grid', 'tribe' ),
			'icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.5 6.5C9.5 8.15685 8.15685 9.5 6.5 9.5C4.84315 9.5 3.5 8.15685 3.5 6.5C3.5 4.84315 4.84315 3.5 6.5 3.5C8.15685 3.5 9.5 4.84315 9.5 6.5ZM11 6.5C11 8.98528 8.98528 11 6.5 11C4.01472 11 2 8.98528 2 6.5C2 4.01472 4.01472 2 6.5 2C8.98528 2 11 4.01472 11 6.5ZM20.5 17.5C20.5 19.1569 19.1569 20.5 17.5 20.5C15.8431 20.5 14.5 19.1569 14.5 17.5C14.5 15.8431 15.8431 14.5 17.5 14.5C19.1569 14.5 20.5 15.8431 20.5 17.5ZM22 17.5C22 19.9853 19.9853 22 17.5 22C15.0147 22 13 19.9853 13 17.5C13 15.0147 15.0147 13 17.5 13C19.9853 13 22 15.0147 22 17.5ZM4 15.5V20.5H9V15.5H4ZM3.5 14C2.94772 14 2.5 14.4477 2.5 15V21C2.5 21.5523 2.94772 22 3.5 22H9.5C10.0523 22 10.5 21.5523 10.5 21V15C10.5 14.4477 10.0523 14 9.5 14H3.5ZM14.9019 8.75L17.5 4.25L20.0981 8.75H14.9019ZM16.634 2.75C17.0189 2.08333 17.9811 2.08333 18.366 2.75L21.8301 8.75C22.215 9.41667 21.7339 10.25 20.9641 10.25H14.0359C13.2661 10.25 12.785 9.41667 13.1699 8.75L16.634 2.75Z" fill="black"/></svg>',
			'keywords' => [ esc_html__( 'icon', 'tribe' ), esc_html__( 'grid', 'tribe' ) ],
			'category' => Block_Category::CUSTOM_BLOCK_CATEGORY_SLUG,
			'supports' => [
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
			new Field( self::NAME . '_' . self::LEADIN, [
					'label' => esc_html__( 'Overline', 'tribe' ),
					'name'  => self::LEADIN,
					'type'  => 'text',
				 ] )
		)->add_field(
			new Field( self::NAME . '_' . self::TITLE, [
					 'label' => esc_html__( 'Title', 'tribe' ),
					 'name'  => self::TITLE,
					 'type'  => 'text',
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
		)->add_field(
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
			'label'        => esc_html__( 'Section', 'tribe' ),
			'name'         => self::ICONS,
			'layout'       => 'block',
			'min'          => 0,
			'max'          => 12,
			'button_label' => esc_html__( 'Add Item', 'tribe' ),
			'wrapper'      => [
				'class' => 'tribe-acf-hide-label',
			],
		] );

		$group->add_field(
			new Field( self::ICON_IMAGE, [
				'label'         => esc_html__( 'Icon', 'tribe' ),
				'name'          => self::ICON_IMAGE,
				'type'          => 'image',
				'return_format' => 'array',
				'preview_size'  => 'medium',
				'instructions'  => esc_html__( 'Recommended image size: 100px wide with any aspect ratio.', 'tribe' ),
			] )
		)->add_field(
			new Field( self::ICON_TITLE, [
				'label' => esc_html__( 'Title', 'tribe' ),
				'name'  => self::ICON_TITLE,
				'type'  => 'text',
			] )
		)->add_field(
			new Field( self::ICON_DESCRIPTION, [
				'label'        => esc_html__( 'Description', 'tribe' ),
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
