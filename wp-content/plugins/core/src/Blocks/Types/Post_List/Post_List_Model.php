<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Post_List;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\post_list\Post_List_Controller;
use Tribe\Project\Templates\Models\Post_List_Object;

class Post_List_Model extends Base_Model {
	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Post_List_Controller::CLASSES => $this->get_classes(),
			Post_List_Controller::POSTS   => $this->get_posts(),
		];
	}

	/**
	 * @return array
	 */
	private function get_posts(): array {
		$type = $this->get( Post_List::QUERY_TYPE, Post_List::QUERY_TYPE_AUTO );

		if ( Post_List::QUERY_TYPE_AUTO === $type ) {
			return $this->get_posts_from_query();
		}

		return $this->get_manually_selected_posts();
	}

	/**
	 * @return Post_List_Object[]
	 */
	private function get_manually_selected_posts(): array {
		$manual_rows = $this->get( Post_List::MANUAL_QUERY, [] );

		$post_array = [];
		foreach ( $manual_rows as $row ) {
			$post_obj = null;
			if ( ! $row[ Post_List::MANUAL_POST ] && ! $row[ Post_List::MANUAL_TOGGLE ] ) {
				continue; //no post and no override/custom
			}

			//Get manually selected post
			if ( $row[ Post_List::MANUAL_POST ] && $row[ Post_List::MANUAL_POST ] instanceof \WP_Post ) {
				$post_obj = $this->format_post( $row[ Post_List::MANUAL_POST ] );
			}

			//build custom or overwrite selected post above
			if ( $row[ Post_List::MANUAL_TOGGLE ] ) {
				$post_obj = $this->maybe_overwrite_values( $row, $post_obj );
			}

			//Check if we have data for this post to remove any empty rows
			if ( ! $post_obj || ! $this->is_valid_post( $post_obj ) ) {
				continue;
			}
			$post_array[] = $post_obj;
		}

		return $post_array;
	}

	/**
	 * @param      $values
	 * @param null $post_object
	 *
	 * @return Post_List_Object
	 */
	private function maybe_overwrite_values( $values, $post_object = null ): Post_List_Object {
		if ( ! $post_object ) {
			$post_object = new Post_List_Object();
		}

		if ( ! empty( $values[ Post_List::MANUAL_TITLE ] ) ) {
			$post_object->set_title( $values[ Post_List::MANUAL_TITLE ] );
		}

		if ( ! empty( $values[ Post_List::MANUAL_EXCERPT ] ) ) {
			$post_object->set_excerpt( $values[ Post_List::MANUAL_EXCERPT ] );
		}

		if ( ! empty( $values[ Post_List::MANUAL_THUMBNAIL ] ) ) {
			$post_object->set_image_id( (int) $values[ Post_List::MANUAL_THUMBNAIL ] );
		}

		if ( $values[ Post_List::MANUAL_CTA ] && is_array( $values[ Post_List::MANUAL_CTA ] ) ) {
			$post_object->set_link( $values[ Post_List::MANUAL_CTA ] );
		}

		return $post_object;
	}

	/**
	 * @param Post_List_Object $post_object
	 *
	 * @return bool
	 */
	private function is_valid_post( Post_List_Object $post_object ): bool {
		return ! ( empty( $post_object->get_title() ) &&
		           empty( $post_object->get_excerpt() ) &&
		           ! $post_object->get_image_id() &&
		           empty( $post_object->get_link() ) );
	}

	/**
	 * @return Post_List_Object[]
	 */
	private function get_posts_from_query(): array {
		$group      = $this->get( Post_List::QUERY_GROUP, [] );
		$post_types = (array) $group[ Post_List::QUERY_POST_TYPES ];
		$tax_query  = $this->get_tax_query_args( $group );
		$args       = [
			'post_type'      => $post_types,
			'tax_query'      => [
				'relation' => 'AND',
			],
			'post_status'    => 'publish',
			'posts_per_page' => $group[ Post_List::QUERY_LIMIT ],
		];
		foreach ( $tax_query as $taxonomy => $ids ) {
			$args[ 'tax_query' ][] = [
				'taxonomy' => $taxonomy,
				'field'    => 'id',
				'terms'    => array_map( 'intval', $ids ),
				'operator' => 'IN',
			];
		}

		$_posts = get_posts( $args );

		$return = [];
		foreach ( $_posts as $p ) {
			$return[] = $this->format_post( $p );
		}

		return $return;
	}

	/**
	 * Builds an array of taxonomy terms based on the selected taxonomies so to ignore term fields hidden with conditional logic.
	 *
	 * @param array $field_group
	 *
	 * @return array
	 */
	private function get_tax_query_args( array $field_group ): array {
		$tax_and_terms = [];

		foreach ( $field_group[ Post_List::QUERY_TAXONOMIES ] as $taxonomy ) {
			$terms = $field_group[ Post_List::QUERY_TAXONOMIES . '_' . $taxonomy ] ?? false;

			if ( ! $terms ) {
				continue;
			}
			foreach ( $terms as $term ) {
				if ( ! $term instanceof \WP_Term ) {
					continue;
				}
				$tax_and_terms[ $term->taxonomy ][] = $term->term_id;
			}
		}

		return $tax_and_terms;
	}

	/**
	 * @param \WP_Post $_post
	 *
	 * @return Post_List_Object
	 */
	private function format_post( \WP_Post $_post ): Post_List_Object {
		global $post;
		$post = $_post;
		setup_postdata( $post );
		$post_obj = new Post_List_Object();
		$post_obj->set_title( get_the_title() )
		         ->set_content( get_the_content() )
		         ->set_excerpt( get_the_excerpt() )
		         ->set_image_id( get_post_thumbnail_id() )
		         ->set_link( [
			         'url'    => get_the_permalink(),
			         'target' => '',
			         'label'  => get_the_title(),
		         ] )
		         ->set_post_type( get_post_type() )
		         ->set_post_id( $_post->ID );

		wp_reset_postdata();

		return $post_obj;
	}
}
