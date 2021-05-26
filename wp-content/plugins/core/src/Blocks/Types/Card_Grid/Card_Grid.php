<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Card_Grid;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Project\Blocks\Fields\CTA;
use Tribe\Project\Post_Types\Post\Post;
use Tribe\Project\Post_Types\Sample\Sample;
use Tribe\Project\Taxonomies\Category\Category;
use Tribe\Project\Taxonomies\Example\Example;
use Tribe\Project\Taxonomies\Post_Tag\Post_Tag;

class Card_Grid extends Block_Config {

	public const NAME = 'cardgrid';

	public const SECTION_CONTENT = 's-content';
	public const TITLE           = 'title';
	public const DESCRIPTION     = 'description';

	public const POST_LIST = 'post_list';

	public const SECTION_SETTINGS = 's-settings';
	public const LAYOUT           = 'layout';
	public const LAYOUT_STACKED   = 'stacked';
	public const LAYOUT_INLINE    = 'inline';

	/**
	 * Register the block
	 */
	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Card Grid', 'tribe' ),
			'description' => __( 'A block of curated posts', 'tribe' ),
			'icon'        => 'sticky',
			'keywords'    => [ __( 'posts', 'tribe' ), __( 'display', 'tribe' ), __( 'text', 'tribe' ) ],
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
						CTA::GROUP_CTA    => [
							CTA::LINK => [
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
	public function add_fields() {
		//==========================================
		// Content Fields
		//==========================================
		$this->add_section( new Field_Section( self::SECTION_CONTENT, __( 'Content', 'tribe' ), 'accordion' ) )
			 ->add_field( new Field( self::NAME . '_' . self::TITLE, [
					 'label' => __( 'Title', 'tribe' ),
					 'name'  => self::TITLE,
					 'type'  => 'text',
				 ] )
			 )->add_field( new Field( self::NAME . '_' . self::DESCRIPTION, [
					'label'        => __( 'Description', 'tribe' ),
					'name'         => self::DESCRIPTION,
					'type'         => 'wysiwyg',
					'toolbar'      => 'basic',
					'media_upload' => 0,
				] )
			)->add_field(
				CTA::get_field( self::NAME )
			)->add_field( new Field( self::NAME . '_' . self::POST_LIST, [
					'label'             => __( 'Post List', 'tribe' ),
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
		$this->add_section( new Field_Section( self::SECTION_SETTINGS, __( 'Settings', 'tribe' ), 'accordion' ) )
			 ->add_field(
				 new Field( self::NAME . '_' . self::LAYOUT, [
					 'type'            => 'image_select',
					 'name'            => self::LAYOUT,
					 'choices'         => [
						 self::LAYOUT_STACKED => __( 'Stacked', 'tribe' ),
						 self::LAYOUT_INLINE  => __( 'Inline', 'tribe' ),
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
