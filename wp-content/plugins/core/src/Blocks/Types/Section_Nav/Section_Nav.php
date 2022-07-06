<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Section_Nav;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Project\Blocks\Block_Category;

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
			'title'    => esc_html__( 'Section Nav', 'tribe' ),
			'icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 12C5.5 8.41015 8.41015 5.5 12 5.5C15.5899 5.5 18.5 8.41015 18.5 12C18.5 15.5899 15.5899 18.5 12 18.5C8.41015 18.5 5.5 15.5899 5.5 12ZM12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4ZM14.9523 8.36499L10.5018 11.3501L9.04782 16.0465L13.5036 13.0932L14.9523 8.36499Z" fill="black"/></svg>',
			'keywords' => [ esc_html__( 'navigation', 'tribe' ), esc_html__( 'menu', 'tribe' ) ],
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
