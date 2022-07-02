<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Card_Grid;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
use Tribe\Project\Blocks\Block_Category;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Fields\Traits\With_Cta_Field;
use Tribe\Project\Post_Types\Post\Post;
use Tribe\Project\Post_Types\Sample\Sample;
use Tribe\Project\Taxonomies\Category\Category;
use Tribe\Project\Taxonomies\Example\Example;
use Tribe\Project\Taxonomies\Post_Tag\Post_Tag;

class Card_Grid extends Block_Config implements Cta_Field {

	use With_Cta_Field;

	public const NAME = 'cardgrid';

	public const TITLE       = 'title';
	public const LEADIN      = 'leadin';
	public const DESCRIPTION = 'description';

	public const SECTION_CARDS = 's-cards';
	public const POST_LIST     = 'post_list';

	public const SECTION_SETTINGS = 's-settings';
	public const LAYOUT           = 'layout';
	public const LAYOUT_STACKED   = 'stacked';
	public const LAYOUT_INLINE    = 'inline';

	/**
	 * Register the block
	 */
	public function add_block(): void {
		$this->set_block( new Block( self::NAME, [
			'title'    => esc_html__( 'Card Grid', 'tribe' ),
			'icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.25 2.25V6H9V2.25H2.25ZM1.5 0.75C1.08579 0.75 0.75 1.08579 0.75 1.5V6.75C0.75 7.16421 1.08579 7.5 1.5 7.5H9.75C10.1642 7.5 10.5 7.16421 10.5 6.75V1.5C10.5 1.08579 10.1642 0.75 9.75 0.75H1.5ZM10.5 9H0.75V10.5H10.5V9ZM10.5 21.75H0.75V23.25H10.5V21.75ZM13.5 9H23.25V10.5H13.5V9ZM23.25 21.75H13.5V23.25H23.25V21.75ZM2.25 18.75V15H9V18.75H2.25ZM0.75 14.25C0.75 13.8358 1.08579 13.5 1.5 13.5H9.75C10.1642 13.5 10.5 13.8358 10.5 14.25V19.5C10.5 19.9142 10.1642 20.25 9.75 20.25H1.5C1.08579 20.25 0.75 19.9142 0.75 19.5V14.25ZM15 2.25V6H21.75V2.25H15ZM14.25 0.75C13.8358 0.75 13.5 1.08579 13.5 1.5V6.75C13.5 7.16421 13.8358 7.5 14.25 7.5H22.5C22.9142 7.5 23.25 7.16421 23.25 6.75V1.5C23.25 1.08579 22.9142 0.75 22.5 0.75H14.25ZM15 18.75V15H21.75V18.75H15ZM13.5 14.25C13.5 13.8358 13.8358 13.5 14.25 13.5H22.5C22.9142 13.5 23.25 13.8358 23.25 14.25V19.5C23.25 19.9142 22.9142 20.25 22.5 20.25H14.25C13.8358 20.25 13.5 19.9142 13.5 19.5V14.25Z" fill="black"/></svg>',
			'keywords' => [ esc_html__( 'posts', 'tribe' ), esc_html__( 'display', 'tribe' ), esc_html__( 'text', 'tribe' ) ],
			'category' => Block_Category::CUSTOM_BLOCK_CATEGORY_SLUG,
			'supports' => [
				'align'  => false,
				'anchor' => true,
			],
			'example'  => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						self::TITLE       => esc_html__( 'A Selection of Posts', 'tribe' ),
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
						self::POST_LIST   => [

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

			$this->add_section( new Field_Section( self::SECTION_CARDS, esc_html__( 'Cards', 'tribe' ), 'accordion' ) )
				->add_field( new Field( self::NAME . '_' . self::POST_LIST, [
					'label'             => esc_html__( '', 'tribe' ),
					'name'              => self::POST_LIST,
					'type'              => 'tribe_post_list',
					'available_types'   => 'both',
					'post_types'        => [
						Post::NAME,
						Sample::NAME,
					],
					'post_types_manual' => [
						Post::NAME,
						Sample::NAME,
					],
					'taxonomies'        => [
						Post_Tag::NAME,
						Category::NAME,
						Example::NAME,
					],
					'limit_min'         => 2,
					'limit_max'         => 10,
				] )
			);

		//==========================================
		// Setting Fields
		//==========================================
		$this->add_section( new Field_Section( self::SECTION_SETTINGS, esc_html__( 'Settings', 'tribe' ), 'accordion' ) )
			 ->add_field(
				 new Field( self::NAME . '_' . self::LAYOUT, [
					 'type'            => 'image_select',
					 'name'            => self::LAYOUT,
					 'choices'         => [
						 self::LAYOUT_STACKED => esc_html__( 'Stacked', 'tribe' ),
						 self::LAYOUT_INLINE  => esc_html__( 'Inline', 'tribe' ),
					 ],
					 'default_value'   => self::LAYOUT_STACKED,
					 'multiple'        => 0,
					 'image_path'      => sprintf(
						 '%sassets/img/admin/blocks/%s/',
						 trailingslashit( get_template_directory_uri() ),
						 self::NAME
					 ),
					 'image_extension' => 'svg',
				 ] )
			 );
	}

}
