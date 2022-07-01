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
			'title'    => esc_html__( 'Tabs', 'tribe' ),
			'icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.5 2.25H7.62488H9.125H13.3749H14.5024H20.2524V6.5H22.25V18.75C22.25 19.413 21.9866 20.0489 21.5178 20.5178C21.0489 20.9866 20.413 21.25 19.75 21.25H4C3.33696 21.25 2.70107 20.9866 2.23223 20.5178C1.76339 20.0489 1.5 19.413 1.5 18.75V2.25ZM18.7524 6.5V3.75H14.8749V6.5H18.7524ZM3 3.75H7.62488V7.25H7.625V8H20.75V18.75C20.75 19.0152 20.6446 19.2696 20.4571 19.4571C20.2696 19.6446 20.0152 19.75 19.75 19.75H4C3.73478 19.75 3.48043 19.6446 3.29289 19.4571C3.10536 19.2696 3 19.0152 3 18.75V3.75ZM9.125 6.5V3.75H13.0024V6.5H9.125Z" fill="black"/></svg>',
			'keywords' => [ esc_html__( 'tabs', 'tribe' ), esc_html__( 'display', 'tribe' ) ],
			'category' => 'tribe-custom',
			'supports' => [
				'align'  => false,
				'anchor' => true,
			],
			'example'  => [
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
		$this->add_field( new Field( self::NAME . '_' . self::LEAD_IN, [
				'label' => esc_html__( 'Overline', 'tribe' ),
				'name'  => self::LEAD_IN,
				'type'  => 'text',
			] )
		)->add_field( new Field( self::NAME . '_' . self::TITLE, [
				'label' => esc_html__( 'Title', 'tribe' ),
				'name'  => self::TITLE,
				'type'  => 'text',
			] )
		)->add_field( new Field( self::NAME . '_' . self::DESCRIPTION, [
				'label'        => esc_html__( 'Description', 'tribe' ),
				'name'         => self::DESCRIPTION,
				'type'         => 'wysiwyg',
				'toolbar'      => Classic_Editor_Formats::MINIMAL,
				'tabs'         => 'visual',
				'media_upload' => 0,
			] )
		)->add_field(
			$this->get_cta_field( self::NAME )
		)->add_field( new Field( self::NAME . '_' . self::LAYOUT, [
			'label'         => esc_html__( 'Layout', 'tribe' ),
			'type'          => 'button_group',
			'name'          => self::LAYOUT,
			'choices'       => [
				self::LAYOUT_HORIZONTAL => esc_html__( 'Horizontal', 'tribe' ),
				self::LAYOUT_VERTICAL   => esc_html__( 'Vertical', 'tribe' ),
			],
			'default_value' => self::LAYOUT_HORIZONTAL,
		] )
		)->add_section( $this->get_tab_section() );
	}

	/**
	 * @return \Tribe\Libs\ACF\Field_Section
	 */
	protected function get_tab_section(): Field_Section {
		$section = new Field_Section( self::SECTION_TABS, esc_html__( 'Tabs', 'tribe' ), 'accordion' );
		$group   = new Repeater( self::NAME . '_' . self::TABS );
		$group->set_attributes( [
			'label'        => esc_html__( 'Tab Section', 'tribe' ),
			'name'         => self::TABS,
			'layout'       => 'block',
			'min'          => 0,
			'max'          => 10,
			'button_label' => esc_html__( 'Add Tab', 'tribe' ),
			'collapsed'    => 'field_' . self::TAB_LABEL,
			'wrapper'      => [
				'class' => 'tribe-acf-hide-label',
			],
		] );
		$header = new Field( self::TAB_LABEL, [
			'label' => esc_html__( 'Tab Label', 'tribe' ),
			'name'  => self::TAB_LABEL,
			'type'  => 'text',
		] );
		$group->add_field( $header );
		$content = new Field( self::TAB_CONTENT, [
			'label'        => esc_html__( 'Tab Content', 'tribe' ),
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
