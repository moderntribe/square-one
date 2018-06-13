<?php

namespace Tribe\Project\Taxonomy;

class Taxonomy_Registration {

	/**
	 * @param array  $terms    The terms to register for the taxonomy
	 * @param string $taxonomy The taxonomy ID
	 * @param int    $version  The current version of the taxonomy schema
	 * @return void
	 */
	public static function add_terms_for( $terms, $taxonomy, $version = 1 ) {
		if ( !self::update_required( $taxonomy, $version ) ) {
			return; // already done;
		}
		foreach ( $terms as $slug => $name ) {
			$term = get_term_by( 'slug', $slug, $taxonomy );
			if ( !empty( $term ) ) {
				continue;
			}
			wp_insert_term( $name, $taxonomy, [ 'slug' => $slug ] );
		}
		$version_history = get_option( 'tribe_taxonomy_term_versions', [ ] );
		$version_history[ $taxonomy ] = $version;
		update_option( 'tribe_taxonomy_term_versions', $version_history );
	}

	/**
	 * @param string $taxonomy The taxonomy ID
	 * @param int    $version  The current version of the taxonomy schema
	 * @return bool Whether the taxonomy's schema should be updated
	 */
	private static function update_required( $taxonomy, $version ) {
		$version_history = get_option( 'tribe_taxonomy_term_versions', [ ] );
		if ( !$version ) {
			return false;
		}
		if ( isset( $version_history[ $taxonomy ] ) && version_compare( $version, $version_history[ $taxonomy ], '<=' ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Register the taxonomy with WordPress
	 *
	 * @param Taxonomy_Config $config
	 *
	 * @return void
	 */
	public static function register( Taxonomy_Config $config ) {
		$callback = self::build_registration_callback( $config );

		// don't register until init
		if ( did_action( 'init' ) ) {
			$callback();
		} else {
			add_action( 'init', $callback, 10, 0 );
		}
	}

	/**
	 * Build the callback that will register a taxonomy using the given config
	 *
	 * @param Taxonomy_Config $config
	 * @return \Closure
	 */
	private static function build_registration_callback( Taxonomy_Config $config ) {
		return function () use ( $config ) {
			if ( !$config->taxonomy() ) {
				throw new \RuntimeException( 'Invalid configuration. Specify a taxonomy.' );
			}
			// If the taxonomy is already registered, just extend it with the given post types
			if ( taxonomy_exists( $config->taxonomy() ) ) {
				foreach ( $config->post_types() as $post_type ) {
					register_taxonomy_for_object_type( $config->taxonomy(), $post_type );
				}
			} else {
				register_extended_taxonomy( $config->taxonomy(), $config->post_types(), $config->get_args(), $config->get_labels() );
			}

			// add initial terms for the taxonomy
			if ( self::update_required( $config->taxonomy(), $config->version() ) ) {
				self::add_terms_for( $config->default_terms(), $config->taxonomy(), $config->version() );
			}
		};
	}


	/**
	 * TODO: Move this to a taxonomy repository class
	 *
	 * @param int[] $term_ids
	 * @return int[]
	 */
	public static function get_term_taxonomy_ids_from_term_ids( $term_ids ) {
		if ( !is_string( $term_ids ) ) {
			$term_ids = implode( ',', $term_ids );
		}
		global $wpdb;
		$interval = sprintf( '(%s)', $term_ids );
		$q = "SELECT term_taxonomy_id FROM $wpdb->term_taxonomy WHERE term_id IN $interval";
		$term_taxonomy_ids = $wpdb->get_col( $q );

		return empty( $term_taxonomy_ids ) ? [ ] : $term_taxonomy_ids;
	}

	/**
	 * TODO: move this to a taxonomy repository class
	 *
	 * @param            $taxonomy
	 * @param string     $key_property
	 * @param string     $value_property
	 * @param array|null $args
	 * @return array
	 */
	public static function get_taxonomy_list( $taxonomy, $key_property = 'term_id', $value_property = 'name', array $args = null ) {
		$terms = get_terms( $taxonomy, $args );
		$list = [ ];

		foreach ( $terms as $term ) {
			$list[ $term->$key_property ] = $term->$value_property;
		}

		return $list;
	}
}