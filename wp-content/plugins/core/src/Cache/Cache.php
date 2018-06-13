<?php

namespace Tribe\Project\Cache;

/**
 * Wrapper for object cache to enable selective cache bursting etc.
 */
class Cache {

	const OPTION_GROUP_KEYS = 'tribe_cache_group_keys';

	/**
	 * @param mixed  $key    The cache key. Accepts any variable that can be serialized.
	 * @param  mixed $value  The value to cache
	 * @param string $group  The cache group
	 * @param int    $expire The cache lifespan in seconds. 0 to never expire
	 * @return bool Whether the value was successfully cached
	 */
	public function set( $key, $value, $group = 'tribe', $expire = 0 ) {
		$group = $this->get_group_key( $group );

		return wp_cache_set( $this->filter_key( $key ), $value, $group, $expire );
	}

	/**
	 * @param mixed  $key   The cache key. Accepts any variable that can be serialized.
	 * @param string $group The cache group
	 * @return mixed The value retrieved from the cache. false if no value was found.
	 */
	public function get( $key, $group = 'tribe' ) {
		$group = $this->get_group_key( $group );
		$results = wp_cache_get( $this->filter_key( $key ), $group );

		return $results;
	}

	/**
	 * @param    mixed $key   The cache key. Accepts any variable that can be serialized
	 * @param string   $group The cache group
	 * @return bool
	 */
	public function delete( $key, $group = 'tribe' ) {
		$group = $this->get_group_key( $group );
		$results = wp_cache_delete( $this->filter_key( $key ), $group );

		return $results;
	}

	/**
	 * Invalidate all cache entries in the group
	 *
	 * @param string $group The cache group to flush
	 * @return void
	 */
	public function flush_group( $group = 'tribe' ) {
		$this->update_group_key( $group );
	}

	/**
	 * Process the cache key so that any unique data may serve as a key, even if it's an object or array.
	 *
	 * @param array|object|string $key
	 *
	 * @return string
	 */
	private function filter_key( $key ) {
		if ( empty( $key ) ) return false;
		$key = ( is_array( $key ) ) ? md5( serialize( $key ) ) : $key;

		$key .= $this->version();
		return $key;
	}

	private function version() {
		if ( function_exists( 'tribe_get_version' ) ) {
			return tribe_get_version();
		} else {
			return '';
		}
	}

	/**
	 * Update the option containing the real keys for all cache groups
	 *
	 * @param array $keys
	 * @return void
	 */
	private function set_group_keys( array $keys ) {
		update_option( self::OPTION_GROUP_KEYS, $keys );
	}

	/**
	 * Get the option containing the real keys for all cache groups
	 *
	 * @return array
	 */
	private function get_group_keys() {
		$keys = get_option( self::OPTION_GROUP_KEYS, [ ] );
		if ( empty( $keys ) || !is_array( $keys ) ) {
			$keys = [ ];
		};

		return $keys;
	}

	/**
	 * Get the real key for the cache group
	 *
	 * @param string $group
	 * @return string
	 */
	private function get_group_key( $group ) {
		$keys = $this->get_group_keys();
		if ( isset( $keys[ $group ] ) ) {
			return $keys[ $group ];
		}
		// make a new key
		$group = $this->update_group_key( $group );

		return $group;
	}

	/**
	 * Change the real key for the cache group, thereby invalidating
	 * all data cached in that group.
	 *
	 * @param string $group
	 * @return string
	 */
	private function update_group_key( $group ) {
		$keys = $this->get_group_keys();
		$new = $group . time();
		$keys[ $group ] = $new;
		$this->set_group_keys( $keys );

		return $new;
	}
}

