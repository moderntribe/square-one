<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Post_List;

use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field_Group;
use Tribe\Libs\ACF\Repeater;
use Tribe\Project\Post_Types\Post\Post;
use Tribe\Project\Post_Types\Sample\Sample;
use Tribe\Project\Taxonomies\Category\Category;
use Tribe\Project\Taxonomies\Example\Example;
use Tribe\Project\Taxonomies\Post_Tag\Post_Tag;
use Tribe\Project\Theme\Config\Image_Sizes;

class Post_List extends Block_Config {
	public const NAME = 'postlist';

	public const POST_LIST = 'post_list';

	/**
	 * Register the block
	 */
	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Post List Field', 'tribe' ),
			'description' => __( 'A first pass on panel\'s post list field', 'tribe' ),
			'icon'        => 'sticky',
			'keywords'    => [ __( 'posts', 'tribe' ), __( 'display', 'tribe' ), __( 'text', 'tribe' ) ],
			'category'    => 'layout',
			'supports'    => [
				'align'  => false,
				'anchor' => true,
			],
		] ) );
	}

	/**
	 * Register Fields for block
	 */
	public function add_fields() {
		$this->add_field( new Field( self::NAME . '_' . self::POST_LIST, [
				'label'           => __( 'Post List', 'tribe' ),
				'name'            => self::POST_LIST,
				'type'            => 'tribe_post_list',
				'available_types' => 'both',
				'post_types'      => [
					Post::NAME,
					Sample::NAME,
				],
				'taxonomies'      => [
					Post_Tag::NAME,
					Category::NAME,
					Example::NAME,
				],
				'limit_min'       => 2,
				'limit_max'       => 12,
			] )
		);
	}

}
