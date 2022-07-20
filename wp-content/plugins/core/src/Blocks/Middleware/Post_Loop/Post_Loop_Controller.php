<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

use Psr\SimpleCache\CacheInterface;
use Tribe\Project\Blocks\Middleware\Post_Loop\Field_Middleware\Post_Loop_Field_Middleware;
use Tribe\Project\Blocks\Middleware\Post_Loop\Models\Post_Loop_Model;
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
	protected Post_Cache_Manager $cache_manager;
	protected Manual_Post_Manager $manual_post_manager;

	public function __construct(
		Post_Loop_Model $model,
		Post_Cache_Manager $cache_manager,
		Manual_Post_Manager $manual_post_manager
	) {
		$this->model               = $model;
		$this->cache_manager       = $cache_manager;
		$this->manual_post_manager = $manual_post_manager;
	}

	/**
	 * Returns a list of Post Proxy objects.
	 *
	 * @param \WP_Post[]|array<int, array>                                           $posts
	 * @param bool                                                                   $manual_posts Whether this is a manual/faux post request.
	 * @param \Tribe\Project\Blocks\Middleware\Post_Loop\Models\Post_Loop_Model|null $model
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *
	 * @return \Tribe\Project\Blocks\Middleware\Post_Loop\Post_Proxy[]
	 */
	public function get_posts( array $posts = [], bool $manual_posts = false, ?Post_Loop_Model $model = null ): array {
		if ( $model ) {
			$this->model = $model;
		}

		if ( $posts ) {
			return $this->proxy_posts( $posts, $manual_posts );
		}

		if ( $this->model->query_type !== Post_Loop_Field_Middleware::QUERY_TYPE_AUTO ) {
			return $this->proxy_posts( $this->manual_post_manager->get_post_data( $this->model ), true );
		}

		// @phpstan-ignore-next-line WordPress once again lies about it types.
		return $this->proxy_posts( $this->get_query()->posts ?? [] );
	}

	/**
	 * Wrap WP_Post objects in our proxy object.
	 *
	 * @param \WP_Post[]|array<int, array> $posts
	 * @param bool                         $manual_posts Whether this is a manual/faux post request.
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *
	 * @see WP_Post::get_instance()
	 *
	 * @return \Tribe\Project\Blocks\Middleware\Post_Loop\Post_Proxy[]
	 */
	protected function proxy_posts( array $posts, bool $manual_posts = false ): array {
		return array_map( function ( $post ) use ( $manual_posts ) {
			$this->cache_manager->flush_term_relationship( $post->ID ?? $post['ID'] );

			if ( $post instanceof WP_Post ) {
				$post = $post->to_array();
			}

			$id = $post['ID'];

			if ( empty( $post['image'] ) ) {
				$post['image'] = acf_get_attachment( get_post_thumbnail_id( $id ) ) ?: null;
			}

			if ( empty( $post['cta']['link'] ) ) {
				$post['cta']['link'] = [
					'url'   => (string) get_the_permalink( $id ),
					'title' => get_the_title( $id ),
				];
			}

			$post_proxy = new Post_Proxy( $post );

			if ( $manual_posts ) {
				$this->cache_manager->add_post( $post_proxy );
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

}
