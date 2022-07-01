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
			'title'    => esc_html__( 'Accordion', 'tribe' ),
			'icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M19.2185 5.41438C18.8951 5.15562 18.4231 5.20806 18.1644 5.53151C17.9056 5.85495 17.9581 6.32692 18.2815 6.58568L20.1565 8.08568C20.4304 8.30481 20.8196 8.30481 21.0935 8.08568L22.9685 6.58568C23.292 6.32692 23.3444 5.85495 23.0857 5.53151C22.8269 5.20806 22.355 5.15562 22.0315 5.41438L20.625 6.53956L19.2185 5.41438ZM16.5 6.00004H0.75V7.50004H16.5V6.00004ZM16.5 11.25H0.75V12.75H16.5V11.25ZM0.75 16.5H16.5V18H0.75V16.5ZM18.1644 10.7815C18.4231 10.4581 18.8951 10.4056 19.2185 10.6644L20.625 11.7896L22.0315 10.6644C22.355 10.4056 22.8269 10.4581 23.0857 10.7815C23.3444 11.105 23.292 11.5769 22.9685 11.8357L21.0935 13.3357C20.8196 13.5548 20.4304 13.5548 20.1565 13.3357L18.2815 11.8357C17.9581 11.5769 17.9056 11.105 18.1644 10.7815ZM19.2185 15.9144C18.8951 15.6556 18.4231 15.7081 18.1644 16.0315C17.9056 16.355 17.9581 16.8269 18.2815 17.0857L20.1565 18.5857C20.4304 18.8048 20.8196 18.8048 21.0935 18.5857L22.9685 17.0857C23.292 16.8269 23.3444 16.355 23.0857 16.0315C22.8269 15.7081 22.355 15.6556 22.0315 15.9144L20.625 17.0396L19.2185 15.9144Z" fill="black"/></svg>',
			'keywords' => [ esc_html__( 'accordion', 'tribe' ) ],
			'category' => 'tribe-custom',
			'supports' => [
				'align'  => false,
				'anchor' => true,
			],
			'example'  => [
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
			'label'        => esc_html__( 'Accordion Section', 'tribe' ),
			'name'         => self::ACCORDION,
			'layout'       => 'block',
			'min'          => 0,
			'max'          => 10,
			'collapsed'    => 'field_' . self::ROW_HEADER,
			'button_label' => 'Add Item',
			'wrapper'      => [
				'class' => 'tribe-acf-hide-label',
			],
		] );
		$header = new Field( self::ROW_HEADER, [
			'label' => esc_html__( 'Title', 'tribe' ),
			'name'  => self::ROW_HEADER,
			'type'  => 'text',
		] );
		$group->add_field( $header );
		$content = new Field( self::ROW_CONTENT, [
			'label'        => esc_html__( 'Description', 'tribe' ),
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
