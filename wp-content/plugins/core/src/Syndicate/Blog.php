<?php

namespace Tribe\Project\Syndicate;

use Tribe\Project\Syndicate\Admin\Settings_Fields;

class Blog {

	/*
	 * The WP tables we will utilize in syndication.
	 */
	const TABLES = [
		'posts',
		'postmeta',
		'terms',
		'term_taxonomy',
		'comments',
		'termmeta',
		'term_relationships',
	];

	/*
	 * auto_incr keys for the syndication tables.
	 */
	const AUTO_INCREMENT_KEYS = [
		'ID',
		'meta_id',
		'term_id',
		'term_taxonomy_id',
		'comment_ID',
		'meta_id',
		'',
	];

	/*
	 * 1 billion auto_increment offset.
	 */
	const OFFSET                    = 1000000000;
	const VIEW_NAME                 = 'syndicate';
	const SYNDICATED_TRACKING_TABLE = 'syndicated_posts';

	/**
	 * @param $cqueries
	 *
	 * @return array
	 * @filter dbdelta_create_queries
	 */
	public function table_creation( $cqueries ) {

		if ( $this->check_if_blog_creation_queries( $cqueries ) ) {
			// WP filters the creation queries, so we want to append our updates to the insertion query.
			add_filter( 'dbdelta_insert_queries', [ $this, 'update_insertion_query' ] );
		}

		return $cqueries;
	}

	public function create_syndicated_tracking_table() {
		global $wpdb;
		$syndicated_tracking_table = self::SYNDICATED_TRACKING_TABLE;

		if ( $wpdb->get_var( sprintf( "SHOW TABLE STATUS FROM %s WHERE `name` LIKE '%s';", DB_NAME, $wpdb->base_prefix . $syndicated_tracking_table ) ) ) {
			return [];
		}

		return [
			"CREATE TABLE IF NOT EXISTS {$wpdb->base_prefix}{$syndicated_tracking_table} (
				source_post_id int NOT NULL,
				copied_post_id bigint NOT NULL,
				blog_id int NOT NULL
			)",
			"CREATE INDEX {$syndicated_tracking_table}_copied_index
			ON {$wpdb->base_prefix}{$syndicated_tracking_table} ( blog_id, copied_post_id )",
			"CREATE INDEX {$syndicated_tracking_table}_source_index
			ON {$wpdb->base_prefix}{$syndicated_tracking_table} ( source_post_id )",
		];
	}

	/**
	 * @param $iqueries
	 *
	 * @return array
	 * @filter dbdelta_insert_queries
	 */
	public function update_insertion_query( $iqueries ) {
		$syndicated_tracking_table = $this->create_syndicated_tracking_table();

		remove_filter( 'dbdelta_insert_queries', [ $this, 'update_insertion_query' ] );

		return array_merge( $syndicated_tracking_table, $this->auto_increment_queries(), $this->views(), $iqueries );
	}

	// Check if the current queries are blog creation queries.
	private function check_if_blog_creation_queries( $cqueries ) {
		$next_blog_queries = wp_get_db_schema( 'blog', $this->blog_id() );
		$current_queries   = trim( implode( ';', $cqueries ) ) . ';';

		return trim( $next_blog_queries ) == $current_queries;
	}

	// Get the blog ID in question.
	private function blog_id() {
		global $wpdb;

		return (int) $wpdb->blogid;
	}

	// Creates alter table queries.
	private function auto_increment_queries() {
		global $wpdb;

		$alter_queries   = [];
		$increment_start = (int) $this->blog_id() * self::OFFSET;

		foreach ( self::TABLES as $table ) {
			$alter_queries[] = "ALTER TABLE {$wpdb->prefix}{$table} AUTO_INCREMENT {$increment_start}";
		}

		return $alter_queries;
	}

	// Creates view queries.
	public function views() {
		global $wpdb;

		return $this->construct_view_queries( $wpdb->base_prefix, $wpdb->blogid );
	}

	/**
	 * @filter syndicate/alter_views
	 */
	public function alter_views() {
		global $wpdb;

		$blog_ids = get_sites( [ 'fields' => 'ids', 'number' => 0 ] );
		$queries  = [];

		foreach ( $blog_ids as $blog_id ) {
			// There are no views to alter on the main site.
			if ( BLOG_ID_CURRENT_SITE == $blog_id ) {
				continue;
			}

			$queries = array_merge( $queries, $this->construct_view_queries( $wpdb->base_prefix, $blog_id ) );
		}

		foreach ( $queries as $query ) {
			$wpdb->query( $query );
		}

	}

	private function construct_view_queries( $prefix, $blog_id ) {
		global $wpdb;
		$syndicated_tracking_table = self::SYNDICATED_TRACKING_TABLE;

		$views     = [];
		$view_name = self::VIEW_NAME;

		$blog_prefix = $wpdb->get_blog_prefix( $blog_id );

		foreach ( self::TABLES as $table ) {

			switch ( $table ) {
				case 'posts'                :
					switch_to_blog( SITE_ID_CURRENT_SITE );
					$statuses   = implode( ',', array_map( function ( $state ) {
						return sprintf( "'%s'", esc_sql( $state ) );
					}, Settings_Fields::states() ) );
					$post_types = implode( ',', array_map( function ( $type ) {
						return sprintf( "'%s'", esc_sql( $type ) );
					}, Settings_Fields::types() ) );
					restore_current_blog();

					$views[] = "CREATE OR REPLACE VIEW {$blog_prefix}{$view_name}_{$table} AS 
						SELECT * FROM {$prefix}{$table} WHERE id NOT IN (
							SELECT source_post_id FROM {$prefix}$syndicated_tracking_table WHERE blog_id = {$blog_id}
						)
						AND `post_type` IN ({$post_types})
						AND `post_status` IN ({$statuses})
						UNION ALL
						SELECT * FROM {$blog_prefix}{$table}";
					break;

				case 'term_taxonomy'        :
					$views[] = "CREATE OR REPLACE VIEW {$blog_prefix}{$view_name}_{$table} AS 
						SELECT * FROM {$prefix}{$table} 
							WHERE `taxonomy` <> 'nav_menu'
						UNION ALL
						SELECT * FROM {$blog_prefix}{$table}";
					break;

				case 'termmeta'             :
					$views[] = "CREATE OR REPLACE VIEW {$blog_prefix}{$view_name}_{$table} AS
						SELECT * FROM {$prefix}{$table}
						UNION ALL
						SELECT * FROM {$blog_prefix}{$table}";
					break;

				case 'term_relationships'   :
					$views[] = "CREATE OR REPLACE VIEW {$blog_prefix}{$view_name}_{$table} AS
						SELECT * FROM {$prefix}{$table}
						UNION ALL
						SELECT * FROM {$blog_prefix}{$table}";
					break;
				default                     :
					$views[] = "CREATE OR REPLACE VIEW {$blog_prefix}{$view_name}_{$table} AS 
						SELECT * FROM {$prefix}{$table} 
						UNION ALL
						SELECT * FROM {$blog_prefix}{$table}";
					break;
			}
		}

		return $views;
	}

	// Simple wrapper to return table names for blog_id.
	public function blog_table_names() {
		global $wpdb;
		$table_names = [];

		foreach ( self::TABLES as $table ) {
			$table_names[] = $wpdb->prefix . $table;
		}

		return $table_names;
	}

	// Wrapper to get view names.
	public function blog_view_names() {
		return array_map( [ $this, 'get_view_by_table_name' ], $this->blog_table_names() );
	}

	public function get_view_by_table_name( $table_name ) {
		global $wpdb;

		return str_replace( $wpdb->prefix, $wpdb->prefix . self::VIEW_NAME . '_', $table_name );
	}

	// Get auto_increment column name for a standard WP table.
	public function get_auto_increment_key( $table ) {
		$key = array_search( $table, $this->blog_table_names(), true );

		return self::AUTO_INCREMENT_KEYS[ $key ];
	}

	// Get the next insertion ID for a standard WP table.
	public function get_next_insertion_id( $table ) {
		global $wpdb;

		$table = str_replace( $wpdb->prefix, '', $table );

		return $wpdb->get_var( sprintf( "SHOW TABLE STATUS FROM %s WHERE `name` LIKE '%s';"
			, DB_NAME, $wpdb->prefix . $table ), 10 );
	}

	// Get a valid insertion ID.
	public function get_valid_insertion_id( $table ) {
		global $wpdb;

		// Get the max key so we continue sequentially.
		$max_insertion = $wpdb->get_var( "SELECT max({$this->get_auto_increment_key($table)} FROM $table" );
		if ( $this->next_insertion_id_is_valid( $max_insertion + 1 ) ) {
			return $max_insertion + 1;
		}

		// Get the used IDs and a valid range.
		$used_ids    = $wpdb->get_col( "SELECT {$this->get_auto_increment_key($table)} FROM $table " );
		$valid_range = range( $wpdb->blogid * self::OFFSET, ( $wpdb->blogid * self::OFFSET ) - 1 );

		return array_diff( $valid_range, $used_ids )[0];
	}

	// Check if the insertion ID is valid.
	protected function next_insertion_id_is_valid( $table, $auto_increment = 0 ) {
		$insertion_id = $auto_increment ? $auto_increment : (int) $this->get_next_insertion_id( $table );

		$valid = filter_var( $insertion_id, FILTER_VALIDATE_INT, [
			'options' => [
				'min_range' => $this->blog_id() * self::OFFSET,
				'max_range' => ( ( $this->blog_id() + 1 ) * self::OFFSET ) - 1,
			],
		] );

		return (bool) $valid;
	}

	private function fix_auto_increment( $table ) {
		global $wpdb;

		$new_offset = (int) $this->get_valid_insertion_id( $table );
		$wpdb->query( "ALTER TABLE {$wpdb->prefix}{$table} AUTO_INCREMENT {$new_offset}" );
	}

	// Verifies auto_increment values.
	private function periodic_cleanup() {
		foreach ( self::TABLES as $table ) {
			if ( ! $this->next_insertion_id_is_valid( $table, $this->get_next_insertion_id( $table ) ) ) {
				$this->fix_auto_increment( $table );
			}
		}
	}

	// WP has 3 primary insertion queries on blog creation that assume auto_incr of 1.
	// This fixes those.
	public function enforce_insertion_id( $query, $table ) {
		global $wpdb;


		// "SELECT 0" instead of an empty string to avoid a WP notice when an empty query runs
		if ( $query == "INSERT INTO `{$wpdb->terms}` (`term_id`, `name`, `slug`, `term_group`) VALUES (1, 'Uncategorized', 'uncategorized', 0)" ) {
			$query = 'SELECT 0';
		}

		if ( $query == "INSERT INTO `{$wpdb->term_taxonomy}` (`term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES (1, 'category', '', 0, 1)" ) {
			$query = 'SELECT 0';
		}

		if (
			false !== strpos( $query, "INSERT INTO `{$wpdb->comments}` (`comment_post_ID`, `comment_author`, " )
			&& false !== strpos( $query, 'VALUES ( 1' )
		) {
			$query = 'SELECT 0';
		}

		// This only runs periodic cleanup one time.
		// And only if we are dealing with tables we care about.
		static $increment_checked = false;
		if ( ! $increment_checked ) {
			$this->periodic_cleanup();
		}

		return $query;
	}

	/**
	 * @filter init
	 */
	public function register_tables() {
		global $wpdb;

		// make WP aware of our tables
		foreach ( self::TABLES as $table ) {
			$view           = self::VIEW_NAME . '_' . $table;
			$wpdb->tables[] = $view;
			$name           = $this->get_view_by_table_name( $table );
			$wpdb->$view    = $name;
		}

		$syndicated_table         = self::SYNDICATED_TRACKING_TABLE;
		$wpdb->ms_global_tables[] = $syndicated_table;
		$wpdb->$syndicated_table  = $wpdb->base_prefix . '_' . $syndicated_table;
	}

	/**
	 * @filter wpmu_drop_tables
	 */
	public function drop_views( array $tables, int $blog_id ):array {

		global $wpdb;

		if ( empty( $blog_id ) || 1 === $blog_id || $blog_id !== $GLOBALS['blog_id'] ) {
			return $tables;
		}

		foreach( self::TABLES as $table ) {
			unset( $tables[ self::VIEW_NAME . '_' . $table ] );

			$table_name = $wpdb->prefix . self::VIEW_NAME . '_' . $table;
			$wpdb->query( "DROP VIEW IF EXISTS {$table_name}" );
		}

		return $tables;
	}

	/**
	 * Drops records in tribe_syndicated_post when blog deletion occurs
	 *
	 * @param int $blog_id
	 */
	public function prune_syndicated_table( int $blog_id ) {
		global $wpdb;

		$sql = $wpdb->prepare( "DELETE FROM {$wpdb->base_prefix}syndicated_posts WHERE blog_id=%d", $blog_id );
		$wpdb->query( $sql );
	}
}
