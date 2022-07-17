<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

use stdClass;
use Tribe\Project\Blocks\Fields\Cta_Field;
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

	public function __construct( Post_Loop_Model $model ) {
		$this->model = $model;
	}

	/**
	 * Returns a list of proxy posts.
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
	 * @return \Tribe\Project\Blocks\Middleware\Post_Loop\Post_Proxy[]
	 */
	public function proxy_posts( array $posts ): array {
		return array_map( static function ( $post ) {
			if ( $post instanceof WP_Post ) {
				$post = $post->to_array();
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

			return new Post_Proxy( $post );
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

			// Don't show blank repeater posts until they have at least a title
			if ( empty( $post['post_title'] ) && $post['ID'] < 0 ) {
				continue;
			}

			// Post date could have been modified, set the proper GMT date.
			$post['post_date_gmt'] = get_gmt_from_date( $post['post_date'] );

			$posts[] = $post;
			$count ++;
		}

		return $this->proxy_posts( $posts );
	}

}
