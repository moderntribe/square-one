<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Buttons;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Repeater;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Fields\Traits\With_Cta_Field;

class Buttons extends Block_Config implements Cta_Field {

	use With_Cta_Field;

	public const NAME = 'buttons';

	public const BUTTONS      = 'buttons';
	public const BUTTON_LINK  = 'button_link';
	public const BUTTON_STYLE = 'button_style';

	public const STYLE_PRIMARY   = 'primary';
	public const STYLE_SECONDARY = 'secondary';
	public const STYLE_CTA       = 'cta';

	/**
	 * @return void
	 */
	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Buttons', 'tribe' ),
			'description' => __( 'One or more links styled to look like buttons.', 'tribe' ),
			'icon'        => '<svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true" focusable="false"><path d="M19 6.5H5c-1.1 0-2 .9-2 2v7c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7c0-1.1-.9-2-2-2zm.5 9c0 .3-.2.5-.5.5H5c-.3 0-.5-.2-.5-.5v-7c0-.3.2-.5.5-.5h14c.3 0 .5.2.5.5v7zM8 13h8v-1.5H8V13z"></path></svg>',
			'keywords'    => [ __( 'formatting', 'tribe' ) ],
			'category'    => 'layout',
			'supports'    => [
				'align'  => false,
				'anchor' => true,
			],
			'example'     => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						self::BUTTONS => [
							[
								self::BUTTON_LINK  => [
									'title'  => esc_html__( 'Lorem ipsum', 'tribe' ),
									'url'    => '#',
									'target' => '',
								],
								self::BUTTON_STYLE => self::STYLE_PRIMARY,
							],
							[
								self::BUTTON_LINK  => [
									'title'  => esc_html__( 'Lorem ipsum', 'tribe' ),
									'url'    => '#',
									'target' => '',
								],
								self::BUTTON_STYLE => self::STYLE_SECONDARY,
							],
						],

					],
				],
			],
		] ) );
	}

	/**
	 * @return void
	 */
	public function add_fields() {
		$this->add_field(
			$this->get_links_section()
		);
	}

	/**
	 * @return \Tribe\Libs\ACF\Repeater
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

		$group->add_field( $this->get_cta_field( self::NAME ) );

		$button_style = new Field( self::NAME . '_' . self::BUTTON_STYLE, [
			'allow_null'    => 0,
			'choices'       => [
				self:: STYLE_PRIMARY   => __( 'Primary', 'tribe' ),
				self:: STYLE_SECONDARY => __( 'Secondary', 'tribe' ),
				self:: STYLE_CTA       => __( 'Text CTA', 'tribe' ),
			],
			'default_value' => self::STYLE_PRIMARY,
			'label'         => __( 'Style', 'tribe' ),
			'layout'        => 'horizontal',
			'name'          => self::BUTTON_STYLE,
			'return_format' => 'value',
			'required'      => 0,
			'type'          => 'button_group',
		] );

		$group->add_field( $button_style );

		return $group;
	}

}
