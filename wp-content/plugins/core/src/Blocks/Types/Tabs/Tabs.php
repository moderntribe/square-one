<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Tabs;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Libs\ACF\Repeater;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Fields\Traits\With_Cta_Field;

class Tabs extends Block_Config implements Cta_Field {

	use With_Cta_Field;

	public const NAME = 'tabs';

	public const LAYOUT            = 'layout';
	public const LAYOUT_HORIZONTAL = 'horizontal';
	public const LAYOUT_VERTICAL   = 'vertical';

	public const LEAD_IN     = 'leadin';
	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';

	public const SECTION_TABS = 's-tabs';
	public const TABS         = 'tabs';
	public const TAB_LABEL    = 'tab_label';
	public const TAB_CONTENT  = 'tab_content';

	/**
	 * Register the block
	 */
	public function add_block(): void {
		$this->set_block( new Block( self::NAME, [
			'title'           => __( 'Tabs', 'tribe' ),
			'description'     => __( 'Tab block', 'tribe' ),
			'icon'            => '<svg width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill="#fff" stroke="#000" stroke-linecap="round" stroke-linejoin="round" d="M.5.5h19v19H.5z"/><path fill="#000" d="M1 1h6v4H1z"/><path fill="#fff" stroke="#000" d="M7.5 1.5h5v3h-5zM13.5 1.5h5v3h-5zM1.5 4.5h17v14h-17z"/><path fill="#151515" d="M3 6h12v1H3zM3 8h9v1H3zM3 10h13v1H3zM3 12h6v1H3z"/></svg>',
			'keywords'        => [ __( 'tabs', 'tribe' ), __( 'display', 'tribe' ) ],
			'category'        => 'layout',
			'render_template' => plugin_dir_path( __FILE__ ) . 'Tabs_Route.php',
			'supports'        => [
				'align'  => false,
				'anchor' => true,
			],
			'example'         => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						self::TITLE       => esc_html__( 'A Tabs Block', 'tribe' ),
						self::LEAD_IN     => esc_html__( 'Suspendisse potenti', 'tribe' ),
						self::DESCRIPTION => esc_html__(
							'Pellentesque diam diam, aliquet non mauris eu, posuere mollis urna. Nulla eget congue ligula, a aliquam lectus. Duis non diam maximus justo dictum porttitor in in risus.',
							'tribe'
						),
						self::GROUP_CTA   => [
							self::LINK => [
								'title'  => esc_html__( 'Lorem ipsum', 'tribe' ),
								'url'    => '#',
								'target' => '',
							],
						],
						self::TABS        => [
							[
								self::TAB_LABEL   => esc_html__( 'Tab One', 'tribe' ),
								self::TAB_CONTENT => sprintf(
									'<p>%s</p>',
									esc_html__(
										'Sed aliquet quam posuere tellus convallis molestie. Aliquam neque tellus, viverra in augue ut, facilisis accumsan elit. Cras id convallis libero. Proin tincidunt elit quis bibendum faucibus. Pellentesque porttitor molestie eros at placerat. Morbi ac odio nec dolor commodo semper. Cras auctor euismod velit efficitur volutpat.',
										'tribe'
									)
								),
							],
							[
								self::TAB_LABEL   => esc_html__( 'Tab Two', 'tribe' ),
								self::TAB_CONTENT => sprintf(
									'<p>%s</p>',
									esc_html__(
										'Sed aliquet quam posuere tellus convallis molestie. Aliquam neque tellus, viverra in augue ut, facilisis accumsan elit. Cras id convallis libero. Proin tincidunt elit quis bibendum faucibus. Pellentesque porttitor molestie eros at placerat. Morbi ac odio nec dolor commodo semper. Cras auctor euismod velit efficitur volutpat.',
										'tribe'
									)
								),
							],
						],
					],
				],
			],
		] ) );
	}

	/**
	 * Register Fields for block
	 */
	public function add_fields(): void {
		$this->add_field( new Field( self::NAME . '_' . self::LAYOUT, [
				'label'         => __( 'Layout', 'tribe' ),
				'type'          => 'button_group',
				'name'          => self::LAYOUT,
				'choices'       => [
					self::LAYOUT_HORIZONTAL => __( 'Horizontal', 'tribe' ),
					self::LAYOUT_VERTICAL   => __( 'Vertical', 'tribe' ),
				],
				'default_value' => self::LAYOUT_HORIZONTAL,
			] )
		)->add_field( new Field( self::NAME . '_' . self::LEAD_IN, [
				'label'       => __( 'Lead in', 'tribe' ),
				'name'        => self::LEAD_IN,
				'type'        => 'text',
				'placeholder' => __( 'Leadin (optional)', 'tribe' ),
				'wrapper'     => [
					'class' => 'tribe-acf-hide-label',
				],
			] )
		)->add_field( new Field( self::NAME . '_' . self::TITLE, [
				'label' => __( 'Title', 'tribe' ),
				'name'  => self::TITLE,
				'type'  => 'text',
			] )
		)->add_field( new Field( self::NAME . '_' . self::DESCRIPTION, [
				'label'        => __( 'Description', 'tribe' ),
				'name'         => self::DESCRIPTION,
				'type'         => 'wysiwyg',
				'toolbar'      => Classic_Editor_Formats::MINIMAL,
				'tabs'         => 'visual',
				'media_upload' => 0,
			] )
		)->add_field(
			$this->get_cta_field( self::NAME )
		)->add_section( $this->get_tab_section() );
	}

	/**
	 * @return \Tribe\Libs\ACF\Field_Section
	 */
	protected function get_tab_section(): Field_Section {
		$section = new Field_Section( self::SECTION_TABS, __( 'Tabs', 'tribe' ), 'accordion' );
		$group   = new Repeater( self::NAME . '_' . self::TABS );
		$group->set_attributes( [
			'label'        => __( 'Tab Section', 'tribe' ),
			'name'         => self::TABS,
			'layout'       => 'block',
			'min'          => 0,
			'max'          => 10,
			'button_label' => __( 'Add Tab', 'tribe' ),
			'collapsed'    => 'field_' . self::TAB_LABEL,
			'wrapper'      => [
				'class' => 'tribe-acf-hide-label',
			],
		] );
		$header = new Field( self::TAB_LABEL, [
			'label' => __( 'Tab Label', 'tribe' ),
			'name'  => self::TAB_LABEL,
			'type'  => 'text',
		] );
		$group->add_field( $header );
		$content = new Field( self::TAB_CONTENT, [
			'label'        => __( 'Tab Content', 'tribe' ),
			'name'         => self::TAB_CONTENT,
			'type'         => 'wysiwyg',
			'toolbar'      => Classic_Editor_Formats::MINIMAL,
			'tabs'         => 'visual',
			'media_upload' => 0,
			'delay'        => 1,
		] );

		$group->add_field( $content );
		$section->add_field( $group );

		return $section;
	}

}
