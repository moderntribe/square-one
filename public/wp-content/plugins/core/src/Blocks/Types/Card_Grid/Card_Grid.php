<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Card_Grid;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
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
			'title'       => esc_html__( 'Card Grid', 'tribe' ),
			'description' => esc_html__( 'A block of curated posts', 'tribe' ),
			'icon'        => 'sticky',
			'keywords'    => [ esc_html__( 'posts', 'tribe' ), esc_html__( 'display', 'tribe' ), esc_html__( 'text', 'tribe' ) ],
			'category'    => 'layout',
			'supports'    => [
				'align'  => false,
				'anchor' => true,
			],
			'example'     => [
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
					'label'   => esc_html__( 'Leadin', 'tribe' ),
					'name'    => self::LEADIN,
					'type'    => 'text',
					'wrapper' => [
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
			);
			
			$this->add_section( new Field_Section( self::SECTION_CARDS, esc_html__( 'Cards', 'tribe' ), 'accordion' ) )
				->add_field( new Field( self::NAME . '_' . self::POST_LIST, [
					'label'             => esc_html__( 'Post List', 'tribe' ),
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
