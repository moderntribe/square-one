<?php


namespace Tribe\Project\Cache;

use Tribe\Project\Post_Types\Campus_Update;
use Tribe\Project\Post_Types\School;

class Listener extends \Tribe\Libs\Cache\Listener {

	/**
	 * Register each hook that should lead to something expiring
	 *
	 * @return void
	 */
	public function hook() {
		add_action( 'save_post', [ $this, 'save_post' ], 10, 2 );
		add_action( 'wp_update_nav_menu_item', [ $this, 'update_menu' ], 10, 0 );
		add_action( 'wp_create_nav_menu', [ $this, 'update_menu' ], 10, 0 );
		add_action( 'wp_delete_nav_menu', [ $this, 'update_menu' ], 10, 0 );
		add_action( 'p2p_created_connection', [ $this, 'p2p_created_connection' ], 10, 1 );
		add_action( 'p2p_delete_connections', [ $this, 'p2p_delete_connections' ], 10, 1 );
	}

	/**
	 * A post has been created, updated, or trashed
	 *
	 * @param int    $post_id
	 * @param object $post
	 */
	public function save_post( $post_id, $post ) {
		// Example usage:
		// $this->cache->delete('some_cache_key', 'tribe');
		if ( ! in_array( $post->post_status, [ 'auto-draft', 'inherit' ] ) ) {
			$this->cache->flush_group( 'p2p_relationships' );
			$this->cache->flush_group( 'nav' );
		}
	}

	/**
	 * A p2p relation has been created.
	 *
	 * @param $p2p_id
	 */
	public function p2p_created_connection( $p2p_id ) {
		$this->cache->flush_group( 'p2p_relationships' );
	}

	/**
	 * One or more p2p relations have been deleted.
	 *
	 * @param $p2p_ids
	 */
	public function p2p_delete_connections( $p2p_ids ) {
		$this->cache->flush_group( 'p2p_relationships' );
	}

	/**
	 * A Menu has been edited or repositioned
	 */
	public function update_menu() {
		$this->cache->flush_group( 'nav' );
	}
}
