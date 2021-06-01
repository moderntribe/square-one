<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Content_Loop;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Fields\Traits\With_Cta_Field;
use Tribe\Project\Post_Types\Page\Page;
use Tribe\Project\Post_Types\Post\Post;
use Tribe\Project\Taxonomies\Category\Category;
use Tribe\Project\Taxonomies\Post_Tag\Post_Tag;

class Content_Loop extends Block_Config implements Cta_Field {

	use With_Cta_Field;

	public const NAME = 'contentloop';

	public const SECTION_CONTENT  = 's-content';
	public const SECTION_SETTINGS = 's-settings';

	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const LEADIN      = 'leadin';

	public const POST_LIST = 'post_list';

	//Layout Fields
	public const LAYOUT         = 'layout';
	public const LAYOUT_ROW     = 'layout_row';
	public const LAYOUT_FEATURE = 'layout_feature';
	public const LAYOUT_COLUMNS = 'layout_columns';

	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Content Loop', 'tribe' ),
			'description' => __( 'A loop of auto or manual set posts with style options', 'tribe' ),
			'icon'        => '<svg enable-background="new 0 0 146.3 106.3" version="1.1" viewBox="0 0 146.3 106.3" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><style type="text/css">.st0{fill:#16D690;}.st1{fill:#21A6CB;}.st2{fill:#008F8F;}</style><polygon class="st0" points="145.2 106.3 72.6 42.3 26.5 1.2 0 106.3"/><polygon class="st1" points="145.2 106.3 0 106.3 72.6 42.3 118.6 1.2"/><polygon class="st2" points="72.6 42.3 145.2 106.3 0 106.3"/></svg>', // TODO: set SVG icon
			'keywords'    => [ __( 'content', 'tribe' ), __( 'loop', 'tribe' ) ],
			'category'    => 'common',
			'supports'    => [
				'align'  => false,
				'anchor' => true,
				'html'   => false,
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
			 )->add_field( new Field( self::NAME . '_' . self::LEADIN, [
					'label' => __( 'Lead in', 'tribe' ),
					'name'  => self::LEADIN,
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
			)->add_field( new Field( self::NAME . '_' . self::POST_LIST, [
					'label'             => __( 'Post List', 'tribe' ),
					'name'              => self::POST_LIST,
					'type'              => 'tribe_post_list',
					'available_types'   => 'both',
					'post_types'        => [
						Post::NAME,
						Page::NAME,
					],
					'post_types_manual' => [
						Post::NAME,
						Page::NAME,
					],
					'taxonomies'        => [
						Post_Tag::NAME,
						Category::NAME,
					],
					'limit_min'         => 3,
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
						 self::LAYOUT_ROW     => __( 'Row', 'tribe' ),
						 self::LAYOUT_FEATURE => __( 'Feature', 'tribe' ),
						 self::LAYOUT_COLUMNS => __( 'Columns', 'tribe' ),
					 ],
					 'default_value'   => self::LAYOUT_ROW,
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
