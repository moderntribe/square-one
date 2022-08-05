<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

use Psr\SimpleCache\CacheInterface;
use Tribe\Libs\Field_Models\Models\Post_Proxy;
use Tribe\Project\Taxonomies\Category\Category;

class Post_Cache_Manager {

	protected CacheInterface $store;

	public function __construct( CacheInterface $store ) {
		$this->store = $store;
	}

	/**
	 * Add the manual post to the store for term look up and to the WordPress post cache as
	 * WordPress checks this first to see if a post exists or not.
	 *
	 * @param \Tribe\Libs\Field_Models\Models\Post_Proxy $post_proxy
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *
	 * @return bool
	 */
	public function add_post( Post_Proxy $post_proxy ): bool {
		$this->store->set( (string) $post_proxy->ID, $post_proxy );

		if ( $post_proxy->is_faux_post() ) {
			return wp_cache_add( $post_proxy->ID, $post_proxy->post(), 'posts' );
		}

		return true;
	}

	/**
	 * Ensure live block updates and page loads show the set category by clearing the
	 * term relationship cache.
	 *
	 * @param int    $post_id
	 * @param string $taxonomy
	 *
	 * @return bool
	 */
	public function flush_term_relationship( int $post_id, string $taxonomy = Category::NAME ): bool {
		return wp_cache_delete( $post_id, sprintf( '%s_relationships', $taxonomy ) );
	}

}
