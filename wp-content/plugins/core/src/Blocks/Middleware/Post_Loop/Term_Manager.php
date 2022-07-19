<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

use Psr\SimpleCache\CacheInterface;

class Term_Manager {

	protected CacheInterface $store;

	/**
	 * A list of taxonomy names that can have their terms replaced.
	 *
	 * @var string[]
	 */
	protected array $allowed_taxonomies;

	public function __construct( CacheInterface $store, array $allowed_taxonomies = [] ) {
		$this->store              = $store;
		$this->allowed_taxonomies = $allowed_taxonomies;
	}

	/**
	 * Add terms to "faux manual posts" or, override existing manual posts' terms based on
	 * what the user selected in the Manual Query ACF UI.
	 *
	 * @filter get_terms
	 *
	 * @param \WP_Term[] $terms
	 * @param array      $args
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *
	 * @return \WP_Term[]
	 */
	public function get_terms( array $terms, array $args ): array {
		$requested_taxonomies = $args['taxonomy'] ?? [];
		$object_ids           = $args['object_ids'] ?? [];

		if ( ! $object_ids || ! $requested_taxonomies ) {
			return $terms;
		}

		$has_valid_taxonomy = array_intersect( $requested_taxonomies, $this->allowed_taxonomies );

		if ( ! $has_valid_taxonomy ) {
			return $terms;
		}

		foreach ( $object_ids as $object_id ) {
			if ( ! $this->store->has( (string) $object_id ) ) {
				continue;
			}

			/** @var \Tribe\Project\Blocks\Middleware\Post_Loop\Post_Proxy $post */
			$post = $this->store->get( (string) $object_id );

			$terms = array_replace(
				$terms,
				array_map( static fn( int $term_id ) => get_term( $term_id ), $post->post_category ?? [] )
			);
		}

		return $terms;
	}

}
