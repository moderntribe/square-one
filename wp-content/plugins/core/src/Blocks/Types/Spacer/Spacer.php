<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Spacer;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;

class Spacer extends Block_Config {
	public const NAME = 'spacer';

	public const SIZE           = 'size';
	public const DEFAULT        = 'default';
	public const LARGE          = 'large';

	public const DISPLAY_OPTIONS = 'display_options';
	public const ALL_SCREENS     = 'all-screens';
	public const DESKTOP_ONLY    = 'desktop-only';
	public const MOBILE_ONLY     = 'mobile-only';

	public const SECTION_CONTENT  = 's-content';

	/**
	 * Register the block
	 */
	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Spacer', 'tribe' ),
			'description' => __( 'Add space between blocks.', 'tribe' ),
			'icon'        => '<svg fill="none" height="32" viewBox="0 0 32 32" width="32" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="m8.53857 6.30769v-4.30769h2.00003v4.30769zm-2.23088 4.23081h-4.30769v-2.00004h4.30769zm2.23088-1.00004c0-.55228.44772-1 1-1h12.92313c.5522 0 1 .44772 1 1v12.92304c0 .5523-.4478 1-1 1h-12.92313c-.55228 0-1-.4477-1-1zm2.00003 1.00004v10.923h10.9231v-10.923zm-8.5386 12.923h4.30769v-2h-4.30769zm28.0001-12.923h-4.3077v-2.00004h4.3077zm-4.3077 12.923h4.3077v-2h-4.3077zm-4.231-21.4615v4.30769h2v-4.30769zm-12.92283 28v-4.3077h2.00003v4.3077zm12.92283-4.3077v4.3077h2v-4.3077z" fill="#000" fill-rule="evenodd"/></svg>',
			'keywords'    => [
				__( 'spacer', 'tribe' ),
				__( 'display', 'tribe' ),
			],
			'category' => 'design',
			'supports' => [
				'align'  => false,
				'anchor' => true,
			],
			'example'  => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [],
				],
			],
		] ) );
	}

	/**
	 * Register Fields for block
	 */
	public function add_fields() {
		$this->add_field( new Field( self::NAME . '_' . self::SIZE, [
			'label'   => __( 'Appearance', 'tribe' ),
			'name'    => self::SIZE,
			'type'    => 'button_group',
			'choices' => [
				self::DEFAULT  => __( 'Default', 'tribe' ),
				self::LARGE    => __( 'Large', 'tribe' ),
			],
			'default_value' => self::DEFAULT,
		] )
		)->add_field( new Field( self::NAME . '_' . self::DISPLAY_OPTIONS, [
				'label'         => __( 'Show on', 'tribe' ),
				'name'          => self::DISPLAY_OPTIONS,
				'type'    => 'radio',
				'choices' => [
					self::ALL_SCREENS  => __( 'All Screens', 'tribe' ),
					self::DESKTOP_ONLY => __( 'Desktop Only', 'tribe' ),
					self::MOBILE_ONLY  => __( 'Mobile Only', 'tribe' ),
				],
				'default_value' => self::ALL_SCREENS,
			] )
		);
	}
}
