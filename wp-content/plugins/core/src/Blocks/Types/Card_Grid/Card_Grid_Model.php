<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Card_Grid;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Components\blocks\card_grid\Card_Grid_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;
use Tribe\Project\Templates\Models\Post_List_Object;

class Card_Grid_Model extends Base_Model {
	/**
	 * @return array
	 */
	public function get_data(): array {
		return [
			Card_Grid_Controller::CLASSES     => $this->get_classes(),
			Card_Grid_Controller::TITLE       => $this->get( Card_Grid::TITLE, '' ),
			Card_Grid_Controller::DESCRIPTION => $this->get( Card_Grid::DESCRIPTION, '' ),
			Card_Grid_Controller::CTA         => $this->get_cta_args(),
			Card_Grid_Controller::POSTS       => $this->get_posts(),
		];
	}

	/**
	 * @return array
	 */
	private function get_cta_args(): array {
		$cta = wp_parse_args( $this->get( Card_Grid::CTA, [] ), [
			'title'  => '',
			'url'    => '',
			'target' => '',
		] );

		return [
			Link_Controller::CONTENT => $cta[ 'title' ],
			Link_Controller::URL     => $cta[ 'url' ],
			Link_Controller::TARGET  => $cta[ 'target' ],
		];
	}

	/**
	 * @return array
	 */
	private function get_posts(): array {
		$type = $this->get( Card_Grid::QUERY_TYPE, Card_Grid::QUERY_TYPE_AUTO );

		if ( Card_Grid::QUERY_TYPE_AUTO === $type ) {
			return $this->get_auto_posts();
		}

		return $this->get_manual_posts();
	}

	/**
	 * @return Post_List_Object[]
	 */
	private function get_manual_posts(): array {
		$manual_posts = $this->get( Card_Grid::POSTS, [] );
		$return       = [];
		/** @var \WP_Post $post */
		foreach ( $manual_posts as $post ) {
			$return[] = $this->format_post( $post );
		}

		return $return;
	}

	/**
	 * @return Post_List_Object[]
	 */
	private function get_auto_posts(): array {
		$post_types = (array) $this->get( Card_Grid::POST_TYPES, [] );
		$tax_query  = $this->get_tax_query_args( $post_types );
		$args       = [
			'post_type'      => $post_types,
			'tax_query'      => [
				'relation' => 'AND',
			],
			'post_status'    => 'publish',
			'posts_per_page' => $this->get( Card_Grid::LIMIT, 10 ),
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
	 * @param array $post_types
	 *
	 * @return array
	 */
	private function get_tax_query_args( array $post_types ): array {
		$tax_and_terms = [];
		foreach ( $post_types as $cpt ) {
			$terms = $this->get( Card_Grid::TAXONOMIES . '_' . $cpt );
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
		$post_obj = new Post_List_Object( [
			'title'     => get_the_title(),
			'content'   => get_the_content(),
			'excerpt'   => get_the_excerpt(),
			'image'     => get_post_thumbnail_id(),
			'link'      => [
				'url'    => get_permalink(),
				'target' => '',
				'label'  => get_the_title(),
			],
			'post_type' => get_post_type(),
			'post_id'   => $_post->ID,
		] );
		wp_reset_postdata();

		return $post_obj;
	}
}
