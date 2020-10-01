<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Buttons;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Repeater;

class Buttons extends Block_Config {
	public const NAME = 'buttons';

	public const BUTTONS           = 'buttons';
	public const BUTTON_LINK       = 'button_link';
	public const BUTTON_ARIA_LABEL = 'button_aria_label';
	public const BUTTON_STYLE      = 'button_style';
	public const BUTTON_CLASSES    = 'button_classes';

	public const STYLE_PRIMARY   = 'primary';
	public const STYLE_SECONDARY = 'secondary';
	public const STYLE_CTA       = 'cta';

	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Buttons', 'tribe' ),
			'description' => __( 'One or more links styled to look like buttons.', 'tribe' ),
			'icon'        => '<svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true" focusable="false"><path d="M19 6.5H5c-1.1 0-2 .9-2 2v7c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7c0-1.1-.9-2-2-2zm.5 9c0 .3-.2.5-.5.5H5c-.3 0-.5-.2-.5-.5v-7c0-.3.2-.5.5-.5h14c.3 0 .5.2.5.5v7zM8 13h8v-1.5H8V13z"></path></svg>',
			'keywords'    => [ __( 'formatting', 'tribe' ) ],
			'category'    => 'layout',
			'supports'    => [ 'align' => false ],
			'example'     => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						self::BUTTONS => [
							[
								self::BUTTON_LINK       => [
									'title'  => esc_html__( 'Lorem ipsum', 'tribe' ),
									'url'    => '#',
									'target' => '',
								],
								self::BUTTON_ARIA_LABEL => '',
								self::BUTTON_CLASSES    => '',
								self::BUTTON_STYLE      => self::STYLE_PRIMARY,
							],
							[
								self::BUTTON_LINK       => [
									'title'  => esc_html__( 'Lorem ipsum', 'tribe' ),
									'url'    => '#',
									'target' => '',
								],
								self::BUTTON_ARIA_LABEL => '',
								self::BUTTON_CLASSES    => '',
								self::BUTTON_STYLE      => self::STYLE_SECONDARY,
							],
						],

					],
				],
			],
		] ) );
	}

	public function add_fields() {
		$this->add_field(
			$this->get_links_section()
		);
	}

	/**
	 * @return Repeater
	 */
	protected function get_links_section() {
		$group = new Repeater( self::NAME . '_' . self::BUTTONS );

		$group->set_attributes( [
			'label'        => __( 'Buttons', 'tribe' ),
			'name'         => self::BUTTONS,
			'layout'       => 'block',
			'min'          => 1,
			'max'          => 10,
			'button_label' => __( 'Add Button', 'tribe' ),
		] );

		$link = new Field( self::BUTTON_LINK, [
			'label' => __( 'Link', 'tribe' ),
			'name'  => self::BUTTON_LINK,
			'type'  => 'link',
		] );

		$group->add_field( $link );

		$button_style = new Field( self::NAME . '_' . self::BUTTON_STYLE, [
			'type'            => 'image_select',
			'name'            => self::BUTTON_STYLE,
			'choices'         => [
				self::STYLE_PRIMARY   => __( 'Primary', 'tribe' ),
				self::STYLE_SECONDARY => __( 'Secondary', 'tribe' ),
				self::STYLE_CTA       => __( 'Text CTA', 'tribe' ),
			],
			'default_value'   => self::STYLE_PRIMARY,
			'multiple'        => 0,
			'image_path'      => sprintf(
				'%sassets/img/admin/blocks/%s/',
				trailingslashit( get_template_directory_uri() ),
				self::NAME
			),
			'image_extension' => 'svg',
		] );

		$group->add_field( $button_style );

		$classes = new Field( self::BUTTON_CLASSES, [
			'label' => __( 'Custom Classes', 'tribe' ),
			'name'  => self::BUTTON_CLASSES,
			'type'  => 'text',
		] );

		$group->add_field( $classes );

		$aria_label = new Field( self::BUTTON_ARIA_LABEL, [
			'label'        => __( 'Screen Reader Label', 'tribe' ),
			'name'         => self::BUTTON_ARIA_LABEL,
			'type'         => 'text',
			'instructions' => __(
				'A custom label for screen readers if the button\'s action or purpose isn\'t easily identifiable. (Optional)',
				'tribe'
			),
		] );

		$group->add_field( $aria_label );

		return $group;
	}

}
