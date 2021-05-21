<?php declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Icon_Grid;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Libs\ACF\Repeater;

class Icon_Grid extends Block_Config {

	public const NAME = 'icongrid';

	public const SECTION_CONTENT  = 's-content';
	public const SECTION_SETTINGS = 's-settings';

	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const LEADIN      = 'leadin';
	public const CTA         = 'cta';

	public const ICONS            = 'icons';
	public const ICON_IMAGE       = 'icon_image';
	public const ICON_TITLE       = 'icon_title';
	public const ICON_DESCRIPTION = 'icon_description';
	public const ICON_LINK        = 'icon_link';

	public function add_block(): void {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Icon Grid', 'tribe' ),
			'description' => __( 'A grid layout block with icon selections', 'tribe' ),
			'icon'        => '<svg enable-background="new 0 0 146.3 106.3" version="1.1" viewBox="0 0 146.3 106.3" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><style type="text/css">.st0{fill:#16D690;}.st1{fill:#21A6CB;}.st2{fill:#008F8F;}</style><polygon class="st0" points="145.2 106.3 72.6 42.3 26.5 1.2 0 106.3"/><polygon class="st1" points="145.2 106.3 0 106.3 72.6 42.3 118.6 1.2"/><polygon class="st2" points="72.6 42.3 145.2 106.3 0 106.3"/></svg>',
			'keywords'    => [ __( 'icon', 'grid', 'tribe' ) ],
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
		//==========================================
		// Content Fields
		//==========================================
		$this->add_section( new Field_Section( self::SECTION_CONTENT, __( 'Content', 'tribe' ), 'accordion' ) )
			 ->add_field(
				 new Field( self::NAME . '_' . self::TITLE, [
					 'label' => __( 'Title', 'tribe' ),
					 'name'  => self::TITLE,
					 'type'  => 'text',
				 ] )
			 )->add_field(
				 new Field( self::NAME . '_' . self::LEADIN, [
					'label' => __( 'Lead in', 'tribe' ),
					'name'  => self::LEADIN,
					'type'  => 'text',
				 ] )
			 )->add_field(
				 new Field( self::NAME . '_' . self::DESCRIPTION, [
					'label'        => __( 'Description', 'tribe' ),
					'name'         => self::DESCRIPTION,
					'type'         => 'wysiwyg',
					'toolbar'      => 'minimal',
					'tabs'         => 'visual',
					'media_upload' => 0,
				 ] )
			 )->add_field(
				 new Field( self::NAME . '_' . self::CTA, [
					'label' => __( 'Call to Action', 'tribe' ),
					'name'  => self::CTA,
					'type'  => 'link',
				 ] )
			 )->add_field(
				 $this->get_icon_section()
			 );
	}

	/**
	 * @return Repeater
	 */
	protected function get_icon_section(): Repeater {
		$group = new Repeater( self::NAME . '_' . self::ICONS, [
			'label'        => __( 'Icon Section', 'tribe' ),
			'name'         => self::ICONS,
			'layout'       => 'block',
			'min'          => 0,
			'max'          => 12,
			'button_label' => __( 'Add Icon Section', 'tribe' ),
		] );

		$group->add_field(
			new Field( self::ICON_IMAGE, [
				'label'         => __( 'Icon Image', 'tribe' ),
				'name'          => self::ICON_IMAGE,
				'type'          => 'image',
				'return_format' => 'id',
				'preview_size'  => 'medium',
				'instructions'  => __( 'Recommended image size: 100px wide with any aspect ratio.', 'tribe' ),
			] )
		)->add_field(
			new Field( self::ICON_TITLE, [
				'label' => __( 'Icon Title', 'tribe' ),
				'name'  => self::ICON_TITLE,
				'type'  => 'text',
			] )
		)->add_field(
			new Field( self::ICON_DESCRIPTION, [
				'label'        => __( 'Icon Description', 'tribe' ),
				'name'         => self::ICON_DESCRIPTION,
				'type'         => 'wysiwyg',
				'toolbar'      => 'minimal',
				'tabs'         => 'visual',
				'media_upload' => 0,
			] )
		)->add_field(
			new Field( self::ICON_LINK, [
				'label' => __( 'Icon Section Link', 'tribe' ),
				'name'  => self::ICON_LINK,
				'type'  => 'link',
			] )
		);

		return $group;
	}
}
