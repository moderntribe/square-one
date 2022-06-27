<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Accordion;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Libs\ACF\Repeater;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Fields\Traits\With_Cta_Field;

class Accordion extends Block_Config implements Cta_Field {

	use With_Cta_Field;

	public const NAME = 'accordion';

	public const LAYOUT         = 'layout';
	public const LAYOUT_INLINE  = 'inline';
	public const LAYOUT_STACKED = 'stacked';

	public const SCROLL_TO = 'scroll_to';

	public const LEAD_IN     = 'leadin';
	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';

	public const SECTION_ACCORDION = 's-accordion';
	public const ACCORDION         = 'accordion';
	public const ROW_HEADER        = 'row_header';
	public const ROW_CONTENT       = 'row_content';

	public function add_block(): void {
		$this->set_block( new Block( self::NAME, [
			'title'       => esc_html__( 'Accordion', 'tribe' ),
			'description' => esc_html__( 'The Accordion block', 'tribe' ),
			'icon'        => '<svg width="24" height="18" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill="#000" d="M0 0h23.7v2.7H0zM0 12h23.7v2.7H0zM0 15.3h23.7V18H0z"/><path fill="#fff" stroke="#000" d="M1.7 3.8h20v6.8h-20z"/></svg>',
			'keywords'    => [ esc_html__( 'accordion', 'tribe' ) ],
			'category'    => 'layout',
			'supports'    => [
				'align'  => false,
				'anchor' => true,
			],
			'example'     => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						self::LEAD_IN     => esc_html__( 'Lorem ipsum dolor sit amet.', 'tribe' ),
						self::TITLE       => esc_html__( 'The Accordion Title', 'tribe' ),
						self::DESCRIPTION => esc_html__(
							'Cras ut ornare dui, sed venenatis est. Donec euismod in leo quis consequat.',
							'tribe'
						),
						self::GROUP_CTA   => [
							self::LINK => [
								'title'  => esc_html__( 'Lorem ipsum', 'tribe' ),
								'url'    => '#',
								'target' => '',
							],
						],
						self::ACCORDION   => [
							[
								self::ROW_CONTENT => esc_html__(
									'Cras ut ornare dui, sed venenatis est. Donec euismod in leo quis consequat.',
									'tribe'
								),
								self::ROW_HEADER  => esc_html__( 'Lorem ipsum dolor sit amet', 'tribe' ),
							],
						],
					],
				],
			],
		] ) );
	}

	public function add_fields(): void {
		$this->add_field( new Field( self::NAME . '_' . self::LAYOUT, [
				'label'         => esc_html__( 'Layout', 'tribe' ),
				'name'          => self::LAYOUT,
				'type'          => 'button_group',
				'choices'       => [
					self::LAYOUT_INLINE  => esc_html__( 'Inline', 'tribe' ),
					self::LAYOUT_STACKED => esc_html__( 'Stacked', 'tribe' ),
				],
				'default_value' => self::LAYOUT_INLINE,
			] )
		)->add_field( new Field( self::NAME . '_' . self::SCROLL_TO, [
			'label'         => esc_html__( 'Scroll to item after opening?', 'tribe' ),
			'name'          => self::SCROLL_TO,
			'type'          => 'true_false',
			'default_value' => false,
			'ui'            => 1,
			] )
		)->add_field( new Field( self::NAME . '_' . self::LEAD_IN, [
				'label'       => esc_html__( 'Lead in', 'tribe' ),
				'name'        => self::LEAD_IN,
				'type'        => 'text',
				'placeholder' => esc_html__( 'Leadin (optional)', 'tribe' ),
				'wrapper'     => [
					'class' => 'tribe-acf-hide-label',
				],
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
		)->add_section(
			$this->get_accordion_section()
		);
	}

	/**
	 * @return \Tribe\Libs\ACF\Field_Section
	 */
	protected function get_accordion_section(): Field_Section {
		$section = new Field_Section( self::SECTION_ACCORDION, esc_html__( 'Accordion Items', 'tribe' ), 'accordion' );
		$group   = new Repeater( self::NAME . '_' . self::ACCORDION );
		$group->set_attributes( [
			'label'     => esc_html__( 'Accordion Section', 'tribe' ),
			'name'      => self::ACCORDION,
			'layout'    => 'block',
			'min'       => 0,
			'max'       => 10,
			'collapsed' => 'field_' . self::ROW_HEADER,
			'wrapper'   => [
				'class' => 'tribe-acf-hide-label',
			],
		] );
		$header = new Field( self::ROW_HEADER, [
			'label' => esc_html__( 'Header', 'tribe' ),
			'name'  => self::ROW_HEADER,
			'type'  => 'text',
		] );
		$group->add_field( $header );
		$content = new Field( self::ROW_CONTENT, [
			'label'        => esc_html__( 'Content', 'tribe' ),
			'name'         => self::ROW_CONTENT,
			'type'         => 'wysiwyg',
			'toolbar'      => Classic_Editor_Formats::MINIMAL,
			'tabs'         => 'visual',
			'media_upload' => 0,
		] );

		$group->add_field( $content );
		$section->add_field( $group );

		return $section;
	}

}
