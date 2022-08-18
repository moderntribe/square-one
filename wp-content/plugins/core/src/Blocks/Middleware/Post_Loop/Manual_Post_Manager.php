<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

use Psr\SimpleCache\CacheInterface;
use stdClass;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Middleware\Post_Loop\Field_Middleware\Post_Loop_Field_Middleware;
use Tribe\Project\Blocks\Middleware\Post_Loop\Models\Post_Loop_Model;
use Tribe\Project\Taxonomies\Category\Category;
use WP_Post;

class Manual_Post_Manager {

	protected CacheInterface $store;
	protected Post_Cache_Manager $cache_manager;

	public function __construct( Post_Cache_Manager $cache_manager ) {
		$this->cache_manager = $cache_manager;
	}

	/**
	 * Build faux or overridden manual post data to be converted into a Post_Proxy object.
	 *
	 * Faux posts have a negative post ID.
	 *
	 * @param \Tribe\Project\Blocks\Middleware\Post_Loop\Models\Post_Loop_Model $model
	 *
	 * @see \Tribe\Project\Blocks\Middleware\Post_Loop\Post_Loop_Controller::get_posts()
	 *
	 * @return array
	 */
	public function get_post_data( Post_Loop_Model $model ): array {
		$posts = [];
		$count = PHP_INT_MIN;

		foreach ( $model->manual_posts as $repeater ) {
			if ( ! is_array( $repeater ) ) {
				continue;
			}

			$repeater = array_filter( $repeater );

			$post  = $repeater[ Post_Loop_Field_Middleware::MANUAL_POST ] ??= new WP_Post( new stdClass() );
			$image = $repeater[ Post_Loop_Field_Middleware::MANUAL_IMAGE ] ??= null;
			$cta   = $repeater[ Cta_Field::GROUP_CTA ] ??= null;
			$post  = $post->to_array();

			// Set faux post defaults if we aren't overriding an existing post and creating one from nothing.
			if ( ! $post['ID'] ) {
				$post = array_merge( $post, [
					'ID'             => $count,
					'filter'         => 'raw',
					'comment_status' => 'closed',
					'ping_status'    => 'closed',
					'post_name'      => sprintf( 'p-%d', abs( $count ) ),
				] );
			}

			// Apply overrides and add custom data.
			$post = array_merge( $post, array_intersect_key( $repeater, $post ), [
				'image' => $image,
				'cta'   => $cta,
			] );

			// Don't show blank repeater posts until they have at least a title.
			if ( empty( $post['post_title'] ) && $post['ID'] < 0 ) {
				continue;
			}

			$post['post_category'] ??= [];
			$post['post_category']   = array_unique( (array) $post['post_category'] );

			$category = get_taxonomy( Category::NAME );

			// Assign the default category if this post supports it.
			if ( $category ) {
				if ( in_array( $post['post_type'], (array) $category->object_type, true ) ) {
					if ( empty( $post['post_category'] ) ) {
						$post['post_category'] = [ (int) get_option( 'default_category' ) ];
					}
				} else {
					unset( $post['post_category'] );
				}
			}

			// Post date could have been modified, set the proper GMT date.
			$post['post_date_gmt'] = get_gmt_from_date( $post['post_date'] );

			$posts[] = $post;
			$count ++;
		}

		return $posts;
	}

}
