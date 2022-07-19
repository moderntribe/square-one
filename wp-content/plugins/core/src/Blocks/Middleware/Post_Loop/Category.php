<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

use Tribe\Project\Taxonomies\Category\Category as Category_Tax;

class Category {

	protected Post_Loop_Controller $controller;

	public function __construct( Post_Loop_Controller $controller ) {
		$this->controller = $controller;
	}

	/**
	 * Get category terms attached to a faux manual post or manual post with its category overridden.
	 *
	 * @filter get_object_terms
	 *
	 * @param \WP_Term[] $terms
	 * @param int[]      $object_ids
	 * @param string[]   $taxonomies
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *
	 * @return \WP_Term[]
	 */
	public function get( array $terms, array $object_ids, array $taxonomies ): array {
		if ( ! in_array( Category_Tax::NAME, $taxonomies ) ) {
			return $terms;
		}

		$store = $this->controller->get_store();

		foreach ( $object_ids as $object_id ) {
			if ( ! $store->has( (string) $object_id ) ) {
				continue;
			}

			/** @var \Tribe\Project\Blocks\Middleware\Post_Loop\Post_Proxy $post */
			$post = $store->get( (string) $object_id );

			// @phpstan-ignore-next-line fixed in https://github.com/WordPress/WordPress/commit/f4af743ea7da593889912cd36451baed5ca4d2fd
			$cat = $post->post_category[0] ?? 0;

			if ( ! $cat ) {
				continue;
			}

			$term = get_term( $cat, Category_Tax::NAME );

			if ( ! $term || is_wp_error( $term ) ) {
				continue;
			}

			$term->count++;

			array_unshift( $terms, $term );
		}

		return $terms;
	}

}
