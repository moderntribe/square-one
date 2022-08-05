<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Tribe_Query_Loop;

use Tribe\Project\Templates\Components\blocks\tribe_query_loop\Tribe_Query_Loop_Controller;
use WP_Post;

/**
 * Class Tribe_Query_Loop_Model
 *
 * Responsible for mapping values from the block to arguments
 * for the component.
 */
class Tribe_Query_Loop_Model extends \Tribe\Project\Blocks\Types\Base_Model {

	public function get_data(): array {
		return [
			Tribe_Query_Loop_Controller::ATTRS        => $this->get_attrs(),
			Tribe_Query_Loop_Controller::CLASSES      => $this->get_classes(),
			Tribe_Query_Loop_Controller::POSTS        => $this->get_posts(),
			Tribe_Query_Loop_Controller::LAYOUT       => $this->get( Tribe_Query_Loop::LAYOUT, Tribe_Query_Loop::LAYOUT_ROW ),
			Tribe_Query_Loop_Controller::HIDE_EXCERPT => $this->get( Tribe_Query_Loop::HIDE_EXCERPT, false ),
			Tribe_Query_Loop_Controller::HIDE_TOPIC   => $this->get( Tribe_Query_Loop::HIDE_TOPIC, false ),
		];
	}

	/**
	 * @return array
	 */
	private function get_posts(): array {
		return $this->get_posts_from_query();
	}

	/**
	 * @return \WP_Post[]
	 */
	private function get_posts_from_query(): array {
		$group = $this->get( Tribe_Query_Loop::QUERY_GROUP, [] );
		if ( empty( $group ) ) {
			return [];
		}
		$post_types = (array) $group[ Tribe_Query_Loop::QUERY_POST_TYPES ] ?: [];
		$tax_query  = $this->get_tax_query_args( $group );
		$args       = [
			'post_type'      => $post_types,
			'tax_query'      => [
				'relation' => 'AND',
			],
			'post_status'    => 'publish',
			'posts_per_page' => $group[ Tribe_Query_Loop::QUERY_LIMIT ],
			'offset'         => $group[ Tribe_Query_Loop::QUERY_OFFSET ],
		];


		foreach ( $tax_query as $taxonomy => $ids ) {
			$args['tax_query'][] = [
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

		if ( empty( $field_group[ Tribe_Query_Loop::QUERY_TAXONOMIES ] ) ) {
			return $tax_and_terms;
		}

		foreach ( $field_group[ Tribe_Query_Loop::QUERY_TAXONOMIES ] as $taxonomy ) {
			$terms = $field_group[ Tribe_Query_Loop::QUERY_TAXONOMIES . '_' . $taxonomy ] ?? false;

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
	 * @return \WP_Post
	 */
	private function format_post( \WP_Post $_post ): WP_Post {
		return $_post;
	}

}
