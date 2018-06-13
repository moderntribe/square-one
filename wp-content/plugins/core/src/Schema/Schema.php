<?php

namespace Tribe\Project\Schema;

/**
 * Class Schools
 *
 * @package Tribe
 *
 * A base class for handling schema updates. To use, extend and implement
 * the get_updates() method. Instantiate your subclass where appropriate to
 * do any required updates. Example:
 *
 * $my_schema = new My_Schema();
 * if ( $my_schema->update_required() ) {
 *   $my_schema->do_updates();
 * }
 */
abstract class Schema {
	protected $schema_version = 0;
	protected $version_option = '';

	public function __construct() {
		$this->version_option = get_class($this).'-schema';
	}

	/**
	 * We've had problems with the notoptions and
	 * alloptions caches getting out of sync with the DB,
	 * forcing an eternal update cycle
	 *
	 * @return void
	 */
	protected function clear_option_caches() {
		wp_cache_delete( 'notoptions', 'options' );
		wp_cache_delete( 'alloptions', 'options' );
	}

	public function do_updates() {
		$this->clear_option_caches();
		$updates = $this->get_updates();
		asort($updates);
		try {
			foreach ( $updates as $version => $callback ) {
				if ( $this->is_version_in_db_less_than($version) ) {
					call_user_func($callback);
				}
			}
			$this->update_version_option( $this->schema_version );
		} catch ( \Exception $e ) {
			// fail silently, but it should try again next time
		}
	}

	protected function update_version_option( $new_version ) {
		update_option( $this->version_option, $new_version );
	}

	/**
	 * Returns an array of callbacks with numeric keys.
	 * Any key higher than the version recorded in the DB
	 * and lower than $this->schema_version will have its
	 * callback called.
	 *
	 * @return array
	 */
	abstract protected function get_updates();

	protected function is_version_in_db_less_than( $version ) {
		$version_in_db = get_option($this->version_option, 0 );

		if ( version_compare( $version, $version_in_db ) > 0 ) {
			return TRUE;
		}
		return FALSE;
	}

	public function update_required() {
		return $this->is_version_in_db_less_than( $this->schema_version );
	}
}