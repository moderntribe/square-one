<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Post_List;

use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Project\Post_Types\Post\Post;
use Tribe\Project\Post_Types\Sample\Sample;
use Tribe\Project\Theme\Config\Image_Sizes;

class Post_List extends Block_Config {
	public const NAME = 'postlist';

	public const LEAD_IN     = 'leadin';
	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const CTA         = 'cta';

	public const QUERY_TYPE        = 'query_type';
	public const QUERY_TYPE_AUTO   = 'query_type_auto';
	public const QUERY_TYPE_MANUAL = 'query_type_manual';

	public const POSTS      = 'posts'; //Conditional to Manual
	public const LIMIT      = 'limit'; //Conditional to Auto
	public const TAXONOMIES = 'taxonomy_terms'; //Conditional to Auto, one per post type
	public const POST_TYPES = 'post_types'; //Conditional to Auto


	public const LAYOUT         = 'layout';
	public const LAYOUT_INLINE  = 'inline';
	public const LAYOUT_STACKED = 'stacked';

	public const QUERY_POST_TYPES = [
		Post::NAME,
		Sample::NAME
	];

	/**
	 * Register the block
	 */
	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Post List', 'tribe' ),
			'description' => __( 'A block of curated posts', 'tribe' ),
			'icon'        => 'sticky',
			'keywords'    => [ __( 'posts', 'tribe' ), __( 'display', 'tribe' ), __( 'text', 'tribe' ) ],
			'category'    => 'layout',
			'supports'    => [ 'align' => false ],
		] ) );
	}

	/**
	 * Register Fields for block
	 */
	public function add_fields() {
		$this->add_field( new Field( self::NAME . '_' . self::TITLE, [
				'label' => __( 'Title', 'tribe' ),
				'name'  => self::TITLE,
				'type'  => 'text',
			] )
		)->add_field( new Field( self::NAME . '_' . self::LEAD_IN, [
				'label' => __( 'Lead in', 'tribe' ),
				'name'  => self::LEAD_IN,
				'type'  => 'text',
			] )
		)->add_field( new Field( self::NAME . '_' . self::DESCRIPTION, [
				'label'        => __( 'Description', 'tribe' ),
				'name'         => self::DESCRIPTION,
				'type'         => 'wysiwyg',
				'toolbar'      => 'basic',
				'media_upload' => 0,
			] )
		)->add_field( new Field( self::NAME . '_' . self::CTA, [
				'label' => __( 'Call to Action', 'tribe' ),
				'name'  => self::CTA,
				'type'  => 'link',
			] )
		)->add_field( new Field( self::NAME . '_' . self::QUERY_TYPE, [
				'label'   => __( 'Type of Query', 'ur-core' ),
				'name'    => self::QUERY_TYPE,
				'type'    => 'select',
				'choices' => [
					self::QUERY_TYPE_AUTO   => __( 'Automatically Select Posts', 'ur-core' ),
					self::QUERY_TYPE_MANUAL => __( 'Manually Select Posts', 'ur-core' ),
				],
			] )
		//Post select for manual query
		)->add_field( new Field( self::NAME . '_' . self::POSTS, [
				'label'             => __( 'Post Selection', 'ur-core' ),
				'name'              => self::POSTS,
				'min'               => 1,
				'max'               => 20,
				'type'              => 'relationship',
				'post_type'         => self::QUERY_POST_TYPES,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_' . self::NAME . '_' . self::QUERY_TYPE,
							'operator' => '==',
							'value'    => self::QUERY_TYPE_MANUAL,
						],
					],
				],
			] )
		//Post Type selection for dynamic selection
		)->add_field( new Field( self::NAME . '_' . self::POST_TYPES, [
				'label'             => __( 'Post Types', 'ur-core' ),
				'name'              => self::POST_TYPES,
				'type'              => 'select',
				'conditional_logic' => [
					[
						[
							'field'    => 'field_' . self::NAME . '_' . self::QUERY_TYPE,
							'operator' => '==',
							'value'    => self::QUERY_TYPE_AUTO,
						],
					],
				],
				'multiple'          => false,
				'choices'           => $this->get_post_types_labels(),
			] )
		)->add_field( new Field( self::NAME . '_' . self::LIMIT, [
				'label'             => __( 'Limit', 'ur-core' ),
				'name'              => self::LIMIT,
				'min'               => 1,
				'max'               => 20,
				'step'              => 1,
				'type'              => 'number',
				'default'           => 1,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_' . self::NAME . '_' . self::QUERY_TYPE,
							'operator' => '==',
							'value'    => self::QUERY_TYPE_AUTO,
						],
					],
				],
			] )
		);
//		//We need to loop through all public post types to get taxonomy terms that only relate to the post type.
//		//In a perfect world, we could conditionally update this field with javascript but that would require
//		//a custom field being written. This will work for now. Could be a future consideration.
		foreach ( self::QUERY_POST_TYPES as $post_type ) {
			$this->add_field( new Field( self::NAME . '_' . self::TAXONOMIES . '_' . $post_type, [
				'label'             => __( 'Filter by Taxonomy Term', 'ur-core' ),
				'name'              => self::TAXONOMIES . '_' . $post_type,
				'post_type'         => $post_type,
				'type'              => 'advanced_taxonomy_selector',
				'field_type'        => 'multiselect',
				'conditional_logic' => [
					[
						[
							'field'    => 'field_ ' . self::NAME . '_' . self::QUERY_TYPE,
							'operator' => '==',
							'value'    => self::QUERY_TYPE_AUTO,
						],
						[
							'field'    => 'field_' . self::NAME . '_' . self::POST_TYPES,
							'operator' => '==',
							'value'    => $post_type,
						],
					],
				],
			] ) );
		}
	}

	/**
	 * Register Settings for Block
	 */
	public function add_settings() {
		$this->add_setting( new Field( self::NAME . '_' . self::LAYOUT, [
			'type'            => 'image_select',
			'name'            => self::LAYOUT,
			'choices'         => [
				self::LAYOUT_INLINE  => __( 'Inline', 'tribe' ),
				self::LAYOUT_STACKED => __( 'Stacked', 'tribe' ),
			],
			'default_value'   => [
				self::LAYOUT_INLINE,
			],
			'multiple'        => 0,
			'image_path'      => sprintf(
				'%sassets/img/admin/blocks/%s/',
				trailingslashit( get_template_directory_uri() ),
				self::NAME
			),
			'image_extension' => 'svg',
		] ) );
	}

	/**
	 * @param $post_type_slug
	 *
	 * @return mixed
	 */
	private function get_post_types_labels() {
		$array = [];
		foreach ( self::QUERY_POST_TYPES as $cpt ) {
			$obj = get_post_type_object( $cpt );
			if ( ! $obj ) {
				continue;
			}
			$array[ $cpt ] = esc_html( $obj->label );
		}

		return $array;
	}

}
