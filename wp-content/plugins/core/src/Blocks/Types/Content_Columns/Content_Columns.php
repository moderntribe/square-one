<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Content_Columns;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Libs\ACF\Repeater;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
use Tribe\Project\Blocks\Block_Category;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Fields\Traits\With_Cta_Field;

class Content_Columns extends Block_Config implements Cta_Field {

	use With_Cta_Field;

	public const NAME = 'contentcolumns';

	public const SECTION_CONTENT = 's-content';
	public const LEADIN          = 'leadin';
	public const TITLE           = 'title';
	public const DESCRIPTION     = 'description';

	public const SECTION_COLUMNS = 's-columns';
	public const COLUMNS         = 'columns';
	public const COLUMN_TITLE    = 'col_title';
	public const COLUMN_CONTENT  = 'col_content';

	public const SECTION_SETTINGS     = 's-appearance';
	public const CONTENT_ALIGN        = 'content-align';
	public const CONTENT_ALIGN_LEFT   = 'left';
	public const CONTENT_ALIGN_CENTER = 'center';

	/**
	 * Add our block
	 */
	public function add_block(): void {
		$this->set_block( new Block( self::NAME, [
			'title'    => esc_html__( 'Content Columns', 'tribe' ),
			'icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.30597 4.5H1.5V19.5H5.30597V4.5ZM1.5 3C0.671573 3 0 3.67157 0 4.5V19.5C0 20.3284 0.671574 21 1.5 21H6.80597V3H1.5ZM13.903 4.5H10.097V19.5H13.903V4.5ZM8.59698 3V21H15.403V3H8.59698ZM18.694 4.5H22.4999V19.5H18.694V4.5ZM17.194 21V3H22.4999C23.3284 3 23.9999 3.67157 23.9999 4.5V19.5C23.9999 20.3284 23.3284 21 22.4999 21H17.194Z" fill="black"/></svg>',
			'keywords' => [ esc_html__( 'content', 'tribe' ), esc_html__( 'display', 'tribe' ) ],
			'category' => Block_Category::CUSTOM_BLOCK_CATEGORY_SLUG,
			'supports' => [
				'align'  => false,
				'anchor' => true,
			],
			'example'  => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						self::COLUMNS => [
							[
								self::COLUMN_TITLE   => esc_html__( 'Lorem ipsum dolor sit amet.', 'tribe' ),
								self::COLUMN_CONTENT => esc_html__(
									'Cras ut ornare dui, sed venenatis est. Donec euismod in leo quis consequat.',
									'tribe'
								),
								self::GROUP_CTA      => [
									self::LINK => [
										'title'  => esc_html__( 'Lorem ipsum', 'tribe' ),
										'url'    => '#',
										'target' => '',
									],
								],
							],
						],

					],
				],
			],
		] ) );
	}

	/**
	 * Add Fields
	 */
	protected function add_fields(): void {
		//==========================================
		// Content Fields
		//==========================================
		$this->add_field( new Field( self::NAME . '_' . self::LEADIN, [
					'label' => esc_html__( 'Overline', 'tribe' ),
					'name'  => self::LEADIN,
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
			);

			$this->add_section( new Field_Section( self::SECTION_COLUMNS, esc_html__( 'Columns', 'tribe' ), 'accordion' ) )
				->add_field( $this->get_links_section() );

		//==========================================
		// Setting Fields
		//==========================================
		$this->add_section( new Field_Section( self::SECTION_SETTINGS, esc_html__( 'Settings', 'tribe' ), 'accordion' ) )
			 ->add_field( new Field( self::NAME . '_' . self::CONTENT_ALIGN, [
				 'label'         => esc_html__( 'Text Alignment', 'tribe' ),
				 'type'          => 'button_group',
				 'name'          => self::CONTENT_ALIGN,
				 'choices'       => [
					 self::CONTENT_ALIGN_CENTER => esc_html__( 'Center', 'tribe' ),
					 self::CONTENT_ALIGN_LEFT   => esc_html__( 'Left', 'tribe' ),
				 ],
				 'default_value' => self::CONTENT_ALIGN_CENTER,
			 ] ) );
	}

	/**
	 * @return \Tribe\Libs\ACF\Repeater
	 */
	protected function get_links_section(): Repeater {
		$group = new Repeater( self::NAME . '_' . self::COLUMNS );
		$group->set_attributes( [
			'label'        => esc_html__( 'Columns', 'tribe' ),
			'name'         => self::COLUMNS,
			'layout'       => 'block',
			'min'          => 1,
			'max'          => 3,
			'button_label' => esc_html__( 'Add Column', 'tribe' ),
		] );

		$text = new Field( self::COLUMN_TITLE, [
			'label' => esc_html__( 'Title', 'tribe' ),
			'name'  => self::COLUMN_TITLE,
			'type'  => 'text',
		] );

		$content = new Field( self::COLUMN_CONTENT, [
			'label'        => esc_html__( 'Description', 'tribe' ),
			'name'         => self::COLUMN_CONTENT,
			'type'         => 'wysiwyg',
			'toolbar'      => Classic_Editor_Formats::MINIMAL,
			'tabs'         => 'visual',
			'media_upload' => 0,
		] );

		$cta = $this->get_cta_field( self::NAME );

		$group->add_field( $text );
		$group->add_field( $content );
		$group->add_field( $cta );

		return $group;
	}

}
