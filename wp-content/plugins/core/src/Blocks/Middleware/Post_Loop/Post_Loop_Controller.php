<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

use Psr\SimpleCache\CacheInterface;
use stdClass;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Middleware\Post_Loop\Field_Middleware\Post_Loop_Field_Middleware;
use Tribe\Project\Blocks\Middleware\Post_Loop\Models\Post_Loop_Model;
use Tribe\Project\Taxonomies\Category\Category;
use WP_Post;
use WP_Query;

/**
 * Build a list of Post_Proxy objects for use as the "$posts" property in controllers.
 *
 * @see \Tribe\Project\Blocks\Middleware\Post_Loop\Model_Middleware\Post_Loop_Field_Model_Middleware
 */
class Post_Loop_Controller {

	public const POSTS = 'posts';

	protected Post_Loop_Model $model;
	protected CacheInterface $store;

	public function __construct( Post_Loop_Model $model, CacheInterface $store ) {
		$this->model = $model;
		$this->store = $store;
	}

	/**
	 * Get the post store containing faux Post_Proxy objects.
	 *
	 * @return \Psr\SimpleCache\CacheInterface
	 */
	public function get_store(): CacheInterface {
		return $this->store;
	}

	/**
	 * Returns a list of proxy posts.
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *
	 * @return \Tribe\Project\Blocks\Middleware\Post_Loop\Post_Proxy[]
	 */
	public function get_posts(): array {
		if ( $this->model->query_type !== Post_Loop_Field_Middleware::QUERY_TYPE_AUTO ) {
			return $this->get_manual_posts();
		}

		// @phpstan-ignore-next-line WordPress once again lies about it types.
		return $this->proxy_posts( $this->get_query()->posts ?? [] );
	}

	/**
	 * Wrap WP_Post objects in our proxy object.
	 *
	 * @param \WP_Post[]|array<int, array> $posts
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *
	 * @return \Tribe\Project\Blocks\Middleware\Post_Loop\Post_Proxy[]
	 */
	public function proxy_posts( array $posts, bool $manual_posts = false ): array {
		return array_map( function ( $post ) use ( $manual_posts ) {
			if ( $post instanceof WP_Post ) {
				$post = $this->post_to_array( $post );
			}

			$id = $post['ID'];

			if ( empty( $post['image'] ) ) {
				$post['image'] = acf_get_attachment( get_post_thumbnail_id( $id ) ) ?: null;
			}

			if ( empty( $post['cta']['link'] ) ) {
				$post['cta']['link'] = [
					'url'   => get_the_permalink( $id ) ?: '',
					'title' => get_the_title( $id ) ?: '',
				];
			}

			$post_proxy = new Post_Proxy( $post );

			if ( $manual_posts ) {
				$this->store->set( (string) $post_proxy->ID, $post_proxy );
			}

			return $post_proxy;
		}, $posts );
	}

	/**
	 * Dynamically query posts.
	 *
	 * @return \WP_Query
	 */
	protected function get_query(): WP_Query {
		// The user has yet to fully configure the query in the edit post UI.
		if ( ! $this->model->query->post_types || ! $this->model->query->limit ) {
			return new WP_Query();
		}

		$args = [
			'post_type'      => $this->model->query->post_types,
			'post_status'    => 'publish',
			'posts_per_page' => $this->model->query->limit,
			'order'          => $this->model->query->order,
			'orderby'        => $this->model->query->order_by,
			'no_found_rows'  => true,
		];

		if ( $this->model->taxonomies ) {
			$args['tax_query']             = $this->build_taxonomy_query_args();
			$args['tax_query']['relation'] = $this->model->tax_relation;
		}

		return new WP_Query( $args );
	}

	/**
	 * Build WP_Query taxonomy arguments using multiple taxonomies
	 * and terms.
	 *
	 * @return array
	 */
	protected function build_taxonomy_query_args(): array {
		$tax_args = [];

		foreach ( $this->model->taxonomies as $taxonomy => $terms ) {
			if ( ! $terms ) {
				continue;
			}

			if ( isset( $tax_args[ $taxonomy ] ) ) {
				$tax_args[ $taxonomy ]['terms'] = array_merge( $tax_args[ $taxonomy ]['terms'], $terms );
			} else {
				$tax_args[ $taxonomy ] = [
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $terms,
				];
			}
		}

		return array_values( $tax_args );
	}

	/**
	 * Collect the manually selected posts and the fake posts.
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *
	 * @return \Tribe\Project\Blocks\Middleware\Post_Loop\Post_Proxy[]
	 */
	protected function get_manual_posts(): array {
		$posts = [];
		$count = PHP_INT_MIN;

		foreach ( $this->model->manual_posts as $repeater ) {
			if ( ! is_array( $repeater ) ) {
				continue;
			}

			$repeater = array_filter( $repeater );

			$post  = $repeater[ Post_Loop_Field_Middleware::MANUAL_POST ] ??= new WP_Post( new stdClass() );
			$image = $repeater[ Post_Loop_Field_Middleware::MANUAL_IMAGE ] ??= null;
			$cta   = $repeater[ Cta_Field::GROUP_CTA ] ??= null;
			$post  = $this->post_to_array( $post );

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

			// Don't show blank repeater posts until they have at least a title
			if ( empty( $post['post_title'] ) && $post['ID'] < 0 ) {
				continue;
			}

			$post['post_category'] ??= [];
			$post['post_category']   = array_unique( (array) $post['post_category'] );

			$category = get_taxonomy( Category::NAME );

			// Assign the default category if this post supports it
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

			$this->clear_term_cache( $post['ID'] );

			$posts[] = $post;
			$count ++;
		}

		return $this->proxy_posts( $posts, true );
	}

	/**
	 * Ensure live block updates and page loads show the correct category by clearing the
	 * term relationship cache.
	 *
	 * @param int $post_id
	 *
	 * @return void
	 */
	protected function clear_term_cache( int $post_id ): void {
		wp_cache_delete( $post_id, sprintf( '%s_relationships', Category::NAME ) );
	}

	/**
	 * Convert a WP_Post object into an array, ensuring term relationship caches are cleared first.
	 *
	 * @param \WP_Post $post
	 *
	 * @return array
	 */
	protected function post_to_array( WP_Post $post ): array {
		if ( $post->ID !== null ) {
			$this->clear_term_cache( $post->ID );
		}

		return $post->to_array();
	}

}
