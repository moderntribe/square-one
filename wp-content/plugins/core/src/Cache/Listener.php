<?php declare(strict_types=1);

namespace Tribe\Project\Cache;

class Listener extends \Tribe\Libs\Cache\Listener {

	/**
	 * A post has been created, updated, or trashed
	 *
	 *
	 * @param int    $post_id
	 * @param object $post
	 *
	 * @return void
	 */
	public function save_post( $post_id, $post ): void {
		// Example usage:
		// $this->cache->delete('some_cache_key', 'tribe');
		if ( in_array( $post->post_status, [ 'auto-draft', 'inherit' ] ) ) {
			return;
		}

		$this->cache->flush_group( 'p2p_relationships' );
		$this->cache->flush_group( 'nav' );
	}

	/**
	 * A p2p relation has been created.
	 *
	 *
	 * @param $p2p_id
	 *
	 * @return void
	 */
	public function p2p_created_connection($p2p_id): void {
		$this->cache->flush_group( 'p2p_relationships' );
	}

	/**
	 * One or more p2p relations have been deleted.
	 *
	 *
	 * @param $p2p_ids
	 *
	 * @return void
	 */
	public function p2p_delete_connections( $p2p_ids ): void {
		$this->cache->flush_group( 'p2p_relationships' );
	}

	/**
	 * A Menu has been edited or repositioned
	 *
	 * @return void
	 */
	public function update_menu(): void {
		$this->cache->flush_group( 'nav' );
	}

}
