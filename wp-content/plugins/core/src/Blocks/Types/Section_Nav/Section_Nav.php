<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Section_Nav;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;

class Section_Nav extends Block_Config {

	public const NAME = 'sectionnav';

	public const SECTION_CONTENT  = 's-content';
	public const SECTION_SETTINGS = 's-settings';

	public const MENU          = 'menu';
	public const DESKTOP_LABEL = 'desktop_label';
	public const MOBILE_LABEL  = 'mobile_label';
	public const MORE_LABEL    = 'more_label';

	public const STICKY = 'sticky';

	public const MOBILE_INIT        = 'mobile_init';
	public const MOBILE_INIT_OPEN   = 'mobile_init_open';
	public const MOBILE_INIT_CLOSED = 'mobile_init_closed';

	public function add_block(): void {
		$this->set_block( new Block( self::NAME, [
			'title'       => esc_html__( 'Section Nav', 'tribe' ),
			'description' => esc_html__( 'A block to insert a nav menu within a page with several layout options.', 'tribe' ),
			'icon'        => '<svg width="28" height="19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.254 5.968H19.85v1.054H8.254V5.968zm1.462 1.57h8.568v1.053H9.716V7.538z" fill="#000"/><path d="M2.82 2.437v14.126H25.2V2.437H2.82zm21.482 13.176H3.866V3.366h20.436v12.247z" fill="#000"/><path d="M10.092 16.15H2.884l10.614-3.242 1.693 1.012 5.433-3.243 4.409 2.623v2.85h-14.94z" fill="#000"/></svg>',
			'keywords'    => [ esc_html__( 'navigation', 'tribe' ), esc_html__( 'menu', 'tribe' ) ],
			'category'    => 'layout',
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
		$this->add_section( new Field_Section( self::SECTION_CONTENT, esc_html__( 'Content', 'tribe' ), 'accordion' ) )
			->add_field( new Field( self::NAME . '_' . self::MENU, [
					'label' => esc_html__( 'Menu', 'tribe' ),
					'name'  => self::MENU,
					'type'  => 'menu-chooser',
				] )
			)->add_field( new Field( self::NAME . '_' . self::MOBILE_LABEL, [
					'label'         => esc_html__( 'Mobile Button Label', 'tribe' ),
					'name'          => self::MOBILE_LABEL,
					'type'          => 'text',
					'default_value' => esc_html__( 'In this section', 'tribe' ),
					'instructions'  => esc_html__(
						'Label to apply to the nav open/close toggle for mobile viewports.',
						'tribe'
					),
				] )
			)->add_field( new Field( self::NAME . '_' . self::MORE_LABEL, [
					'label'         => esc_html__( 'More Button Label', 'tribe' ),
					'name'          => self::MORE_LABEL,
					'type'          => 'text',
					'default_value' => esc_html__( 'More', 'tribe' ),
					'instructions'  => esc_html__(
						'Label to apply to the "more" drop-down menu toggle for desktop viewports.',
						'tribe'
					),
				] )
			)->add_field( new Field( self::NAME . '_' . self::DESKTOP_LABEL, [
					'label'        => esc_html__( 'Desktop Label', 'tribe' ),
					'name'         => self::DESKTOP_LABEL,
					'type'         => 'text',
					'instructions' => esc_html__(
						'An optional text label to display before the first menu item for desktop viewports.',
						'tribe'
					),
				] )
			);

		//==========================================
		// Setting Fields
		//==========================================
		$this->add_section( new Field_Section( self::SECTION_SETTINGS, esc_html__( 'Settings', 'tribe' ), 'accordion' ) )
			->add_field( new Field( self::NAME . '_' . self::STICKY, [
					'label' => esc_html__( 'Sticky on Scroll?', 'tribe' ),
					'name'  => self::STICKY,
					'type'  => 'true_false',
					'ui'    => 1,
				] )
			)->add_field( new Field( self::NAME . '_' . self::MOBILE_INIT, [
					'label'         => esc_html__( 'Default state on mobile', 'tribe' ),
					'name'          => self::MOBILE_INIT,
					'type'          => 'button_group',
					'choices'       => [
						self::MOBILE_INIT_CLOSED => esc_html__( 'Closed', 'tribe' ),
						self::MOBILE_INIT_OPEN   => esc_html__( 'Open', 'tribe' ),
					],
					'default_Value' => self::MOBILE_INIT_CLOSED,
				] )
			);
	}

}
