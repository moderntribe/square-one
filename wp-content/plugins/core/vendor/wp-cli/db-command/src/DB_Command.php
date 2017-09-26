<?php

use \WP_CLI\Utils;

/**
 * Perform basic database operations using credentials stored in wp-config.php
 *
 * ## EXAMPLES
 *
 *     # Create a new database.
 *     $ wp db create
 *     Success: Database created.
 *
 *     # Drop an existing database.
 *     $ wp db drop --yes
 *     Success: Database dropped.
 *
 *     # Reset the current database.
 *     $ wp db reset --yes
 *     Success: Database reset.
 *
 *     # Execute a SQL query stored in a file.
 *     $ wp db query < debug.sql
 */
class DB_Command extends WP_CLI_Command {

	/**
	 * Create a new database.
	 *
	 * Runs `CREATE_DATABASE` SQL statement using `DB_HOST`, `DB_NAME`,
	 * `DB_USER` and `DB_PASSWORD` database credentials specified in
	 * wp-config.php.
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp db create
	 *     Success: Database created.
	 */
	public function create( $_, $assoc_args ) {

		self::run_query( self::get_create_query() );

		WP_CLI::success( "Database created." );
	}

	/**
	 * Delete the existing database.
	 *
	 * Runs `DROP_DATABASE` SQL statement using `DB_HOST`, `DB_NAME`,
	 * `DB_USER` and `DB_PASSWORD` database credentials specified in
	 * wp-config.php.
	 *
	 * ## OPTIONS
	 *
	 * [--yes]
	 * : Answer yes to the confirmation message.
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp db drop --yes
	 *     Success: Database dropped.
	 */
	public function drop( $_, $assoc_args ) {
		WP_CLI::confirm( "Are you sure you want to drop the '" . DB_NAME . "' database?", $assoc_args );

		self::run_query( sprintf( 'DROP DATABASE `%s`', DB_NAME ) );

		WP_CLI::success( "Database dropped." );
	}

	/**
	 * Remove all tables from the database.
	 *
	 * Runs `DROP_DATABASE` and `CREATE_DATABASE` SQL statements using
	 * `DB_HOST`, `DB_NAME`, `DB_USER` and `DB_PASSWORD` database credentials
	 * specified in wp-config.php.
	 *
	 * ## OPTIONS
	 *
	 * [--yes]
	 * : Answer yes to the confirmation message.
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp db reset --yes
	 *     Success: Database reset.
	 */
	public function reset( $_, $assoc_args ) {
		WP_CLI::confirm( "Are you sure you want to reset the '" . DB_NAME . "' database?", $assoc_args );

		self::run_query( sprintf( 'DROP DATABASE IF EXISTS `%s`', DB_NAME ) );
		self::run_query( self::get_create_query() );

		WP_CLI::success( "Database reset." );
	}

	/**
	 * Check the current status of the database.
	 *
	 * Runs `mysqlcheck` utility with `--check` using `DB_HOST`,
	 * `DB_NAME`, `DB_USER` and `DB_PASSWORD` database credentials
	 * specified in wp-config.php.
	 *
	 * [See docs](http://dev.mysql.com/doc/refman/5.7/en/check-table.html)
	 * for more details on the `CHECK TABLE` statement.
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp db check
	 *     Success: Database checked.
	 */
	public function check() {
		self::run( Utils\esc_cmd( '/usr/bin/env mysqlcheck --no-defaults %s', DB_NAME ), array(
			'check' => true,
		) );

		WP_CLI::success( "Database checked." );
	}

	/**
	 * Optimize the database.
	 *
	 * Runs `mysqlcheck` utility with `--optimize=true` using `DB_HOST`,
	 * `DB_NAME`, `DB_USER` and `DB_PASSWORD` database credentials
	 * specified in wp-config.php.
	 *
	 * [See docs](http://dev.mysql.com/doc/refman/5.7/en/optimize-table.html)
	 * for more details on the `OPTIMIZE TABLE` statement.
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp db optimize
	 *     Success: Database optimized.
	 */
	public function optimize() {
		self::run( Utils\esc_cmd( '/usr/bin/env mysqlcheck --no-defaults %s', DB_NAME ), array(
			'optimize' => true,
		) );

		WP_CLI::success( "Database optimized." );
	}

	/**
	 * Repair the database.
	 *
	 * Runs `mysqlcheck` utility with `--repair=true` using `DB_HOST`,
	 * `DB_NAME`, `DB_USER` and `DB_PASSWORD` database credentials
	 * specified in wp-config.php.
	 *
	 * [See docs](http://dev.mysql.com/doc/refman/5.7/en/repair-table.html) for
	 * more details on the `REPAIR TABLE` statement.
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp db repair
	 *     Success: Database repaired.
	 */
	public function repair() {
		self::run( Utils\esc_cmd( '/usr/bin/env mysqlcheck --no-defaults %s', DB_NAME ), array(
			'repair' => true,
		) );

		WP_CLI::success( "Database repaired." );
	}

	/**
	 * Open a MySQL console using credentials from wp-config.php
	 *
	 * ## OPTIONS
	 *
	 * [--database=<database>]
	 * : Use a specific database. Defaults to DB_NAME.
	 *
	 * [--default-character-set=<character-set>]
	 * : Use a specific character set. Defaults to DB_CHARSET when defined.
	 *
	 * [--<field>=<value>]
	 * : Extra arguments to pass to the MySQL executable.
	 *
	 * ## EXAMPLES
	 *
	 *     # Open MySQL console
	 *     $ wp db cli
	 *     mysql>
	 *
	 * @alias connect
	 */
	public function cli( $args, $assoc_args ) {
		if ( ! isset( $assoc_args['database'] ) ) {
			$assoc_args['database'] = DB_NAME;
		}

		self::run( '/usr/bin/env mysql --no-defaults --no-auto-rehash', $assoc_args );
	}

	/**
	 * Execute a SQL query against the database.
	 *
	 * Executes an arbitrary SQL query using `DB_HOST`, `DB_NAME`, `DB_USER`
	 *  and `DB_PASSWORD` database credentials specified in wp-config.php.
	 *
	 * ## OPTIONS
	 *
	 * [<sql>]
	 * : A SQL query. If not passed, will try to read from STDIN.
	 *
	 * [--<field>=<value>]
	 * : Extra arguments to pass to mysql.
	 *
	 * ## EXAMPLES
	 *
	 *     # Execute a query stored in a file
	 *     $ wp db query < debug.sql
	 *
	 *     # Check all tables in the database
	 *     $ wp db query "CHECK TABLE $(wp db tables | paste -s -d',');"
	 *     +---------------------------------------+-------+----------+----------+
	 *     | Table                                 | Op    | Msg_type | Msg_text |
	 *     +---------------------------------------+-------+----------+----------+
	 *     | wordpress_dbase.wp_users              | check | status   | OK       |
	 *     | wordpress_dbase.wp_usermeta           | check | status   | OK       |
	 *     | wordpress_dbase.wp_posts              | check | status   | OK       |
	 *     | wordpress_dbase.wp_comments           | check | status   | OK       |
	 *     | wordpress_dbase.wp_links              | check | status   | OK       |
	 *     | wordpress_dbase.wp_options            | check | status   | OK       |
	 *     | wordpress_dbase.wp_postmeta           | check | status   | OK       |
	 *     | wordpress_dbase.wp_terms              | check | status   | OK       |
	 *     | wordpress_dbase.wp_term_taxonomy      | check | status   | OK       |
	 *     | wordpress_dbase.wp_term_relationships | check | status   | OK       |
	 *     | wordpress_dbase.wp_termmeta           | check | status   | OK       |
	 *     | wordpress_dbase.wp_commentmeta        | check | status   | OK       |
	 *     +---------------------------------------+-------+----------+----------+
	 *
	 *     # Pass extra arguments through to MySQL
	 *     $ wp db query 'SELECT * FROM wp_options WHERE option_name="home"' --skip-column-names
	 *     +---+------+------------------------------+-----+
	 *     | 2 | home | http://wordpress-develop.dev | yes |
	 *     +---+------+------------------------------+-----+
	 */
	public function query( $args, $assoc_args ) {
		$assoc_args['database'] = DB_NAME;

		// The query might come from STDIN
		if ( !empty( $args ) ) {
			$assoc_args['execute'] = $args[0];
		}

		self::run( '/usr/bin/env mysql --no-defaults --no-auto-rehash', $assoc_args );
	}

	/**
	 * Exports the database to a file or to STDOUT.
	 *
	 * Runs `mysqldump` utility using `DB_HOST`, `DB_NAME`, `DB_USER` and
	 * `DB_PASSWORD` database credentials specified in wp-config.php.
	 *
	 * ## OPTIONS
	 *
	 * [<file>]
	 * : The name of the SQL file to export. If '-', then outputs to STDOUT. If omitted, it will be '{dbname}.sql'.
	 *
	 * [--<field>=<value>]
	 * : Extra arguments to pass to mysqldump
	 *
	 * [--tables=<tables>]
	 * : The comma separated list of specific tables to export. Excluding this parameter will export all tables in the database.
	 *
	 * [--exclude_tables=<tables>]
	 * : The comma separated list of specific tables that should be skipped from exporting. Excluding this parameter will export all tables in the database.
	 *
	 * [--porcelain]
	 * : Output filename for the exported database.
	 *
	 * ## EXAMPLES
	 *
	 *     # Export database with drop query included
	 *     $ wp db export --add-drop-table
	 *     Success: Exported to 'wordpress_dbase.sql'.
	 *
	 *     # Export certain tables
	 *     $ wp db export --tables=wp_options,wp_users
	 *     Success: Exported to 'wordpress_dbase.sql'.
	 *
	 *     # Export all tables matching a wildcard
	 *     $ wp db export --tables=$(wp db tables 'wp_user*' --format=csv)
	 *     Success: Exported to 'wordpress_dbase.sql'.
	 *
	 *     # Export all tables matching prefix
	 *     $ wp db export --tables=$(wp db tables --all-tables-with-prefix --format=csv)
	 *     Success: Exported to 'wordpress_dbase.sql'.
	 *
	 *     # Skip certain tables from the exported database
	 *     $ wp db export --exclude_tables=wp_options,wp_users
	 *     Success: Exported to 'wordpress_dbase.sql'.
	 *
	 *     # Skip all tables matching a wildcard from the exported database
	 *     $ wp db export --exclude_tables=$(wp db tables 'wp_user*' --format=csv)
	 *     Success: Exported to 'wordpress_dbase.sql'.
	 *
	 *     # Skip all tables matching prefix from the exported database
	 *     $ wp db export --exclude_tables=$(wp db tables --all-tables-with-prefix --format=csv)
	 *     Success: Exported to 'wordpress_dbase.sql'.
	 *
	 * @alias dump
	 */
	public function export( $args, $assoc_args ) {
		if ( ! empty( $args[0] ) ) {
			$result_file = $args[0];
		} else {
			$hash = substr( md5( mt_rand() ), 0, 7 );
			$result_file = sprintf( '%s-%s.sql', DB_NAME, $hash );;
		}
		$stdout = ( '-' === $result_file );
		$porcelain = \WP_CLI\Utils\get_flag_value( $assoc_args, 'porcelain' );

		// Bail if both porcelain and STDOUT are set.
		if ( $stdout && $porcelain ) {
			WP_CLI::error( 'Porcelain is not allowed when output mode is STDOUT.' );
		}

		if ( ! $stdout ) {
			$assoc_args['result-file'] = $result_file;
		}

		$command = '/usr/bin/env mysqldump --no-defaults %s';
		$command_esc_args = array( DB_NAME );

		if ( isset( $assoc_args['tables'] ) ) {
			$tables = explode( ',', trim( $assoc_args['tables'], ',' ) );
			unset( $assoc_args['tables'] );
			$command .= ' --tables';
			foreach ( $tables as $table ) {
				$command .= ' %s';
				$command_esc_args[] = trim( $table );
			}
		}

		$exclude_tables = WP_CLI\Utils\get_flag_value( $assoc_args, 'exclude_tables' );
		if ( isset( $exclude_tables ) ) {
			$tables = explode( ',', trim( $assoc_args['exclude_tables'], ',' ) );
			unset( $assoc_args['exclude_tables'] );
			foreach ( $tables as $table ) {
				$command .= ' --ignore-table';
				$command .= ' %s';
				$command_esc_args[] = trim( DB_NAME . '.' . $table );
			}
		}

		$escaped_command = call_user_func_array( '\WP_CLI\Utils\esc_cmd', array_merge( array( $command ), $command_esc_args ) );

		// Remove parameters not needed for SQL run.
		unset( $assoc_args['porcelain'] );

		self::run( $escaped_command, $assoc_args );

		if ( $porcelain ) {
			WP_CLI::line( $result_file );
		}
		else if ( ! $stdout ) {
			WP_CLI::success( sprintf( "Exported to '%s'.", $result_file ) );
		}
	}

	/**
	 * Import a database from a file or from STDIN.
	 *
	 * Runs SQL queries using `DB_HOST`, `DB_NAME`, `DB_USER` and
	 * `DB_PASSWORD` database credentials specified in wp-config.php. This
	 * does not create database by itself and only performs whatever tasks are
	 * defined in the SQL.
	 *
	 * ## OPTIONS
	 *
	 * [<file>]
	 * : The name of the SQL file to import. If '-', then reads from STDIN. If omitted, it will look for '{dbname}.sql'.
	 *
	 * [--skip-optimization]
	 * : When using an SQL file, do not include speed optimization such as disabling auto-commit and key checks.
	 *
	 * ## EXAMPLES
	 *
	 *     # Import MySQL from a file.
	 *     $ wp db import wordpress_dbase.sql
	 *     Success: Imported from 'wordpress_dbase.sql'.
	 */
	public function import( $args, $assoc_args ) {
		if ( ! empty( $args[0] ) ) {
			$result_file = $args[0];
		} else {
			$result_file = sprintf( '%s.sql', DB_NAME );
		}

		$mysql_args = array(
			'database' => DB_NAME,
		);

		if ( '-' !== $result_file ) {
			if ( ! is_readable( $result_file ) ) {
				WP_CLI::error( sprintf( 'Import file missing or not readable: %s', $result_file ) );
			}

			$query = \WP_CLI\Utils\get_flag_value( $assoc_args, 'skip-optimization' )
				? 'SOURCE %s;'
				: 'SET autocommit = 0; SET unique_checks = 0; SET foreign_key_checks = 0; SOURCE %s; COMMIT;';

			$mysql_args['execute'] = sprintf( $query, $result_file );
		}

		self::run( '/usr/bin/env mysql --no-defaults --no-auto-rehash', $mysql_args );

		WP_CLI::success( sprintf( "Imported from '%s'.", $result_file ) );
	}

	/**
	 * List the database tables.
	 *
	 * Defaults to all tables registered to the $wpdb database handler.
	 *
	 * ## OPTIONS
	 *
	 * [<table>...]
	 * : List tables based on wildcard search, e.g. 'wp_*_options' or 'wp_post?'.
	 *
	 * [--scope=<scope>]
	 * : Can be all, global, ms_global, blog, or old tables. Defaults to all.
	 *
	 * [--network]
	 * : List all the tables in a multisite install. Overrides --scope=<scope>.
	 *
	 * [--all-tables-with-prefix]
	 * : List all tables that match the table prefix even if not registered on $wpdb. Overrides --network.
	 *
	 * [--all-tables]
	 * : List all tables in the database, regardless of the prefix, and even if not registered on $wpdb. Overrides --all-tables-with-prefix.
	 *
	 * [--format=<format>]
	 * : Render output in a particular format.
	 * ---
	 * default: list
	 * options:
	 *   - list
	 *   - csv
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     # List tables for a single site, without shared tables like 'wp_users'
	 *     $ wp db tables --scope=blog --url=sub.example.com
	 *     wp_3_posts
	 *     wp_3_comments
	 *     wp_3_options
	 *     wp_3_postmeta
	 *     wp_3_terms
	 *     wp_3_term_taxonomy
	 *     wp_3_term_relationships
	 *     wp_3_termmeta
	 *     wp_3_commentmeta
	 *
	 *     # Export only tables for a single site
	 *     $ wp db export --tables=$(wp db tables --url=sub.example.com --format=csv)
	 *     Success: Exported to wordpress_dbase.sql
	 */
	public function tables( $args, $assoc_args ) {

		$format = WP_CLI\Utils\get_flag_value( $assoc_args, 'format' );
		unset( $assoc_args['format'] );

		if ( empty( $args ) && empty( $assoc_args ) ) {
			$assoc_args['scope'] = 'all';
		}

		$tables = WP_CLI\Utils\wp_get_table_names( $args, $assoc_args );

		if ( 'csv' === $format ) {
			WP_CLI::line( implode( ',', $tables ) );
		} else {
			foreach ( $tables as $table ) {
				WP_CLI::line( $table );
			}
		}
	}

	/**
	 * Display the database name and size.
	 *
	 * Display the database name and size for `DB_NAME` specified in wp-config.php.
	 * The size defaults to a human-readable number.
	 *
	 * ## OPTIONS
	 *
	 * [--size_format]
	 * : Display the database size only, as a bare number.
	 * ---
	 * default: b
	 * options:
	 *  - b (bytes)
	 *  - kb (kilobytes)
	 *  - mb (megabytes)
	 *  ---
	 *
	 * [--tables]
	 * : Display each table name and size instead of the database size.
	 *
	 * [--format]
	 * : table, csv, json
	 * ---
	 * default: table
	 * options:
	 *   - table
	 *   - csv
	 *   - json
	 * ---
	 *
	 * [--scope=<scope>]
	 * : Can be all, global, ms_global, blog, or old tables. Defaults to all.
	 *
	 * [--network]
	 * : List all the tables in a multisite install. Overrides --scope=<scope>.
	 *
	 * [--all-tables-with-prefix]
	 * : List all tables that match the table prefix even if not registered on $wpdb. Overrides --network.
	 *
	 * [--all-tables]
	 * : List all tables in the database, regardless of the prefix, and even if not registered on $wpdb. Overrides --all-tables-with-prefix.
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp db size
	 *     +-------------------+------+
	 *     | Name              | Size |
	 *     +-------------------+------+
	 *     | wordpress_default | 6 MB |
	 *     +-------------------+------+
	 *
	 *     $ wp db size --tables
	 *     +-----------------------+-------+
	 *     | Name                  | Size  |
	 *     +-----------------------+-------+
	 *     | wp_users              | 64 KB |
	 *     | wp_usermeta           | 48 KB |
	 *     | wp_posts              | 80 KB |
	 *     | wp_comments           | 96 KB |
	 *     | wp_links              | 32 KB |
	 *     | wp_options            | 32 KB |
	 *     | wp_postmeta           | 48 KB |
	 *     | wp_terms              | 48 KB |
	 *     | wp_term_taxonomy      | 48 KB |
	 *     | wp_term_relationships | 32 KB |
	 *     | wp_termmeta           | 48 KB |
	 *     | wp_commentmeta        | 48 KB |
	 *     +-----------------------+-------+
	 *
	 *     $ wp db size --size_format=b
	 *     5865472
	 *
	 *     $ wp db size --size_format=kb
	 *     5728
	 *
	 *     $ wp db size --size_format=mb
	 *     6
	 */
	public function size( $args, $assoc_args ) {

		// Avoid a constant redefinition in wp-config.
		@WP_CLI::get_runner()-> load_wordpress();

		global $wpdb;

		$format = WP_CLI\Utils\get_flag_value( $assoc_args, 'format' );
		$size_format = WP_CLI\Utils\get_flag_value( $assoc_args, 'size_format' );
		$tables = WP_CLI\Utils\get_flag_value( $assoc_args, 'tables' );
		$tables = ! empty( $tables );

		unset( $assoc_args['format'] );
		unset( $assoc_args['size_format'] );
		unset( $assoc_args['tables'] );

		if ( empty( $args ) && empty( $assoc_args ) ) {
			$assoc_args['scope'] = 'all';
		}

		// Build rows for the formatter.
		$rows = array();
		$fields = array( 'Name', 'Size' );

		if ( $tables ) {

			// Add all of the table sizes
			foreach( WP_CLI\Utils\wp_get_table_names( $args, $assoc_args ) as $table_name ) {

				// Get the table size.
				$table_bytes = $wpdb->get_var( $wpdb->prepare(
					"SELECT SUM(data_length + index_length) FROM information_schema.TABLES where table_schema = '%s' and Table_Name = '%s' GROUP BY Table_Name LIMIT 1",
					DB_NAME,
					$table_name
					)
				);

				// Add the table size to the list.
				$rows[] = array(
					'Name'  => $table_name,
					'Size'  => strtoupper( size_format( $table_bytes ) ),
				);
			}
		} else {

			// Get the database size.
			$db_bytes = $wpdb->get_var( $wpdb->prepare(
				"SELECT SUM(data_length + index_length) FROM information_schema.TABLES where table_schema = '%s' GROUP BY table_schema;",
				DB_NAME
				)
			);

			// Add the database size to the list.
			$rows[] = array(
				'Name'  => DB_NAME,
				'Size'  => strtoupper( size_format( $db_bytes ) ),
				);
		}

		if ( ! empty( $size_format ) && isset( $db_bytes ) && ! $tables ) {

			// Display the database size as a number.
			switch( $size_format ) {
				case 'mb':
					$divisor = MB_IN_BYTES;
					break;

				case 'kb':
					$divisor = KB_IN_BYTES;
					break;

				case 'b':
				default:
					$divisor = 1;
					break;
			}

			WP_CLI::Line( ceil( $db_bytes / $divisor ) );
		} else {

			// Display the rows.
			$args = array(
				'format' => $format,
			);

			$formatter = new \WP_CLI\Formatter( $args, $fields );
			$formatter->display_items( $rows );
		}
	}

	/**
	 * Display the database table prefix.
	 *
	 * Display the database table prefix, as defined by the database handler's interpretation of the current site.
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp db prefix
	 *     wp_
	 */
	public function prefix() {
		// Avoid a constant redefinition in wp-config.
		@WP_CLI::get_runner()->load_wordpress();

		global $wpdb;

		WP_CLI::log( $wpdb->prefix );
	}

	/**
	 * Find a string in the database.
	 *
	 * Searches through all or a selection of database tables for a given string, Outputs colorized references to the string.
	 *
	 * Defaults to searching through all tables registered to $wpdb. On multisite, this default is limited to the tables for the current site.
	 *
	 * ## OPTIONS
	 *
	 * <search>
	 * : String to search for. The search is case-insensitive by default.
	 *
	 * [<tables>...]
	 * : One or more tables to search through for the string.
	 *
	 * [--network]
	 * : Search through all the tables registered to $wpdb in a multisite install.
	 *
	 * [--all-tables-with-prefix]
	 * : Search through all tables that match the registered table prefix, even if not registered on $wpdb. On one hand, sometimes plugins use tables without registering them to $wpdb. On another hand, this could return tables you don't expect. Overrides --network.
	 *
	 * [--all-tables]
	 * : Search through ALL tables in the database, regardless of the prefix, and even if not registered on $wpdb. Overrides --network and --all-tables-with-prefix.
	 *
	 * [--before_context=<num>]
	 * : Number of characters to display before the match.
	 * ---
	 * default: 40
	 * ---
	 *
	 * [--after_context=<num>]
	 * : Number of characters to display after the match.
	 * ---
	 * default: 40
	 * ---
	 *
	 * [--regex]
	 * : Runs the search as a regular expression (without delimiters). The search becomes case-sensitive (i.e. no PCRE flags are added, except 'u' if the database charset is UTF-8). Delimiters must be escaped if they occur in the expression.
	 *
	 * [--regex-flags=<regex-flags>]
	 * : Pass PCRE modifiers to the regex search (e.g. 'i' for case-insensitivity). Note that 'u' (UTF-8 mode) will not be automatically added.
	 *
	 * [--regex-delimiter=<regex-delimiter>]
	 * : The delimiter to use for the regex. It must be escaped if it appears in the search string.
	 * ---
	 * default: /
	 * ---
	 *
	 * [--table_column_once]
	 * : Output the 'table:column' line once before all matching row lines in the table column rather than before each matching row.
	 *
	 * [--one_line]
	 * : Place the 'table:column' output on the same line as the row id and match ('table:column:id:match'). Overrides --table_column_once.
	 *
	 * [--matches_only]
	 * : Only output the string matches (including context). No 'table:column's or row ids are outputted.
	 *
	 * [--stats]
	 * : Output stats on the number of matches found, time taken, tables/columns/rows searched, tables skipped.
	 *
	 * [--table_column_color=<color_code>]
	 * : Percent color code to use for the 'table:column' output. For a list of available percent color codes, see below. Default '%G' (bright green).
	 *
	 * [--id_color=<color_code>]
	 * : Percent color code to use for the row id output. For a list of available percent color codes, see below. Default '%Y' (bright yellow).
	 *
	 * [--match_color=<color_code>]
	 * : Percent color code to use for the match (unless both before and after context are 0, when no color code is used). For a list of available percent color codes, see below. Default '%3%k' (black on a mustard background).
	 *
	 * The percent color codes available are:
	 * '%y' Yellow (dark) (mustard)
	 * '%g' Green (dark)
	 * '%b' Blue (dark)
	 * '%r' Red (dark)
	 * '%m' Magenta (dark)
	 * '%c' Cyan (dark)
	 * '%w' White (dark) (light gray)
	 * '%k' Black
	 * '%Y' Yellow (bright)
	 * '%G' Green (bright)
	 * '%B' Blue (bright)
	 * '%R' Red (bright)
	 * '%M' Magenta (bright)
	 * '%C' Cyan (bright)
	 * '%W' White
	 * '%K' Black (bright) (dark gray)
	 * '%3' Yellow background (dark) (mustard)
	 * '%2' Green background (dark)
	 * '%4' Blue background (dark)
	 * '%1' Red background (dark)
	 * '%5' Magenta background (dark)
	 * '%6' Cyan background (dark)
	 * '%7' White background (dark) (light gray)
	 * '%0' Black background
	 * '%8' Reverse
	 * '%U' Underline
	 * '%F' Blink (unlikely to work)
	 * They can be concatenated. For instance, the default match color of black on a mustard (dark yellow) background '%3%k' can be made black on a bright yellow background with '%Y%0%8'.
	 *
	 * ## EXAMPLES
	 *
	 *     # Search through the database for the 'wordpress-develop' string
	 *     $ wp db search wordpress-develop
	 *     wp_options:option_value
	 *     1:http://wordpress-develop.dev
	 *     wp_options:option_value
	 *     1:http://example.com/foo
	 *         ...
	 *
	 *     # Search through a multisite database on the subsite 'foo' for the 'example.com' string
	 *     $ wp db search example.com --url=example.com/foo
	 *     wp_2_comments:comment_author_url
	 *     1:http://example.com/
	 *     wp_2_options:option_value
	 *         ...
	 *
	 *     # Search through the database for the 'https?://' regular expression, printing stats.
	 *     $ wp db search 'https?:\/\/' --regex --stats
	 *     wp_comments:comment_author_url
	 *     1:https://wordpress.org/
	 *         ...
	 *     Success: Found 99146 matches in 10.752s (10.559s searching). Searched 12 tables, 53 columns, 1358907 rows. 1 table skipped: wp_term_relationships.
	 *
	 */
	public function search( $args, $assoc_args ) {
		global $wpdb;

		$start_run_time = microtime( true );

		// Avoid a constant redefinition in wp-config.
		@WP_CLI::get_runner()->load_wordpress();

		$search = array_shift( $args );

		$before_context = \WP_CLI\Utils\get_flag_value( $assoc_args, 'before_context', 40 );
		$before_context = '' === $before_context ? $before_context : (int) $before_context;

		$after_context = \WP_CLI\Utils\get_flag_value( $assoc_args, 'after_context', 40 );
		$after_context = '' === $after_context ? $after_context : (int) $after_context;

		if ( ( $regex = \WP_CLI\Utils\get_flag_value( $assoc_args, 'regex', false ) ) ) {
			$regex_flags = \WP_CLI\Utils\get_flag_value( $assoc_args, 'regex-flags', false );
			$regex_delimiter = \WP_CLI\Utils\get_flag_value( $assoc_args, 'regex-delimiter', '/' );
			if ( '' === $regex_delimiter ) {
				$regex_delimiter = '/';
			}
		}

		$colors = self::get_colors( $assoc_args, array( 'table_column' => '%G', 'id' => '%Y', 'match' => $before_context || $after_context ? '%3%k' : '' ) );

		$table_column_once = \WP_CLI\Utils\get_flag_value( $assoc_args, 'table_column_once', false );
		$one_line = \WP_CLI\Utils\get_flag_value( $assoc_args, 'one_line', false );
		$matches_only = \WP_CLI\Utils\get_flag_value( $assoc_args, 'matches_only', false );
		$stats = \WP_CLI\Utils\get_flag_value( $assoc_args, 'stats', false );

		$column_count = $row_count = $match_count = 0;
		$skipped = array();

		if ( $regex ) {
			// Note the user must escape the delimiter in the search.
			$search_regex = $regex_delimiter . $search . $regex_delimiter;
			if ( $regex_flags ) {
				$search_regex .= $regex_flags;
			}
			if ( false === @preg_match( $search_regex, '' ) ) {
				WP_CLI::error( "The regex '$search_regex' fails." );
			}
			$encoding = null;
			if ( 0 === strpos( $wpdb->charset, 'utf8' ) ) {
				$encoding = 'UTF-8';
				if ( ! $regex_flags ) {
					$search_regex .= 'u';
				}
			}
		} else {
			$safe_search = preg_quote( $search, '#' );
			if ( 0 === strpos( $wpdb->charset, 'utf8' ) ) {
				$context_re = \cli\can_use_pcre_x() ? '\X' : '.';
				$search_regex = '#(' . $context_re . '{0,' . $before_context . '})(' . $safe_search .')(' . $context_re . '{0,' . $after_context . '})#iu';
			} else {
				$search_regex = '#(.{0,' . $before_context . '})(' . $safe_search .')(.{0,' . $after_context . '})#i';
			}
			$esc_like_search = '%' . self::esc_like( $search ) . '%';
		}

		$tables = WP_CLI\Utils\wp_get_table_names( $args, $assoc_args );

		$start_search_time = microtime( true );

		foreach ( $tables as $table ) {
			list( $primary_keys, $text_columns, $all_columns ) = self::get_columns( $table );
			if ( ! $all_columns ) {
				WP_CLI::error( "No such table '$table'." );
			}
			if ( ! $text_columns ) {
				if ( $stats ) {
					$skipped[] = $table;
				} else {
					// Don't bother warning for term relationships (which is just 3 int columns).
					if ( ! preg_match( '/_term_relationships$/', $table ) ) {
						WP_CLI::warning( $primary_keys ? "No text columns for table '$table' - skipped." : "No primary key or text columns for table '$table' - skipped." );
					}
				}
				continue;
			}
			$column_count += count( $text_columns );
			if ( ! $primary_keys ) {
				WP_CLI::warning( "No primary key for table '$table'. No row ids will be outputted." );
				$primary_key = $primary_key_sql = '';
			} else {
				$primary_key = array_shift( $primary_keys );
				$primary_key_sql = $primary_key . ', ';
			}

			foreach ( $text_columns as $column ) {
				if ( $regex ) {
					$results = $wpdb->get_results( "SELECT {$primary_key_sql}{$column} FROM {$table}" );
				} else {
					$results = $wpdb->get_results( $wpdb->prepare( "SELECT {$primary_key_sql}{$column} FROM {$table} WHERE {$column} LIKE %s;", $esc_like_search ) );
				}
				if ( $results ) {
					$row_count += count( $results );
					$table_column_val = $colors['table_column'][0] . "{$table}:{$column}" . $colors['table_column'][1];
					$outputted_table_column_once = false;
					foreach ( $results as $result ) {
						$col_val = $result->$column;
						if ( preg_match_all( $search_regex, $col_val, $matches, $regex ? PREG_OFFSET_CAPTURE : PREG_PATTERN_ORDER ) ) {
							if ( ! $matches_only && ( ! $table_column_once || ! $outputted_table_column_once ) && ! $one_line ) {
								WP_CLI::log( $table_column_val );
								$outputted_table_column_once = true;
							}
							$pk_val = $primary_key ? ( $colors['id'][0] . $result->$primary_key . $colors['id'][1] . ':' ) : '';

							$bits = array();
							if ( $regex ) {
								if ( null === $encoding ) {
									$encoding = false;
									if ( ( $before_context || $after_context ) && function_exists( 'mb_detect_encoding' ) ) {
										$encoding = mb_detect_encoding( $col_val, null, true /*strict*/ );
									}
								}
								foreach ( $matches[0] as $match_arr ) {
									$match = $match_arr[0];
									$offset = $match_arr[1];
									// Offsets are in bytes, so need to use `strlen()` and  `substr()` before using `safe_substr()`.
									$before = $before_context && $offset ? \cli\safe_substr( substr( $col_val, 0, $offset ), -$before_context, null /*length*/, false /*is_width*/, $encoding ) : '';
									$after = $after_context ? \cli\safe_substr( substr( $col_val, $offset + strlen( $match ) ), 0, $after_context, false /*is_width*/, $encoding ) : '';
									$bits[] = $before . $colors['match'][0] . $match . $colors['match'][1] . $after;
								}
							} else {
								foreach ( $matches[0] as $key => $value ) {
									$bits[] = $matches[1][ $key ] . $colors['match'][0] . $matches[2][ $key ] . $colors['match'][1] . $matches[3][ $key ];
								}
							}
							$match_count += count( $bits );
							$col_val = implode( ' [...] ', $bits );

							WP_CLI::log( $matches_only ? $col_val : ( $one_line ? "{$table_column_val}:{$pk_val}{$col_val}" : "{$pk_val}{$col_val}" ) );
						}
					}
				}
			}
		}

		if ( $stats ) {
			$table_count = count( $tables );
			$skipped_count = count( $skipped );
			$match_str = 1 === $match_count ? 'match' : 'matches';
			$table_str = 1 === $table_count ? 'table' : 'tables';
			$column_str = 1 === $column_count ? 'column' : 'columns';
			$row_str = 1 === $row_count ? 'row' : 'rows';
			$skipped_str = 1 === $skipped_count ? 'table skipped' : 'tables skipped';
			if ( 0 !== $skipped_count ) {
				$skipped_str .= ': ' . implode( ', ', $skipped );
			}
			$end_time = microtime( true );
			$run_time = $end_time - $start_run_time;
			$search_time = $end_time - $start_search_time;
			$stats_msg = sprintf(
				"Found %d %s in %.3fs (%.3fs searching). Searched %d %s, %d %s, %d %s. %d %s.",
				$match_count, $match_str, $run_time, $search_time, $table_count, $table_str, $column_count, $column_str, $row_count, $row_str, $skipped_count, $skipped_str
			);
			WP_ClI::success( $stats_msg );
		}
	}

	private static function get_create_query() {

		$create_query = sprintf( 'CREATE DATABASE `%s`', DB_NAME );
		if ( defined( 'DB_CHARSET' ) && constant( 'DB_CHARSET' ) ) {
			$create_query .= sprintf( ' DEFAULT CHARSET `%s`', constant( 'DB_CHARSET' ) );
		}
		if ( defined( 'DB_COLLATE' ) && constant( 'DB_COLLATE' ) ) {
			$create_query .= sprintf( ' DEFAULT COLLATE `%s`', constant( 'DB_COLLATE' ) );
		}
		return $create_query;
	}

	private static function run_query( $query ) {
		self::run( '/usr/bin/env mysql --no-defaults --no-auto-rehash', array( 'execute' => $query ) );
	}

	private static function run( $cmd, $assoc_args = array(), $descriptors = null ) {
		$required = array(
			'host' => DB_HOST,
			'user' => DB_USER,
			'pass' => DB_PASSWORD,
		);

		if ( ! isset( $assoc_args['default-character-set'] )
			&& defined( 'DB_CHARSET' ) && constant( 'DB_CHARSET' ) ) {
			$required['default-character-set'] = constant( 'DB_CHARSET' );
		}

		$final_args = array_merge( $assoc_args, $required );
		Utils\run_mysql_command( $cmd, $final_args, $descriptors );
	}

	/**
	 * Get the column names of a db table differentiated into key columns and text columns and all columns.
	 *
	 * @param string $table The table name.
	 * @return array A 3 element array consisting of an array of primary key column names, an array of text column names, and an array containing all column names.
	 */
	private static function get_columns( $table ) {
		global $wpdb;

		$primary_keys = $text_columns = $all_columns = array();
		$suppress_errors = $wpdb->suppress_errors();
		if ( ( $results = $wpdb->get_results( "DESCRIBE $table" ) ) ) {
			foreach ( $wpdb->get_results( "DESCRIBE $table" ) as $col ) {
				if ( 'PRI' === $col->Key ) {
					$primary_keys[] = $col->Field;
				}
				if ( self::is_text_col( $col->Type ) ) {
					$text_columns[] = $col->Field;
				}
				$all_columns[] = $col->Field;
			}
		}
		$wpdb->suppress_errors( $suppress_errors );
		return array( $primary_keys, $text_columns, $all_columns );
	}

	/**
	 * Whether a column is considered text or not.
	 *
	 * @param string Column type.
	 * @bool True if text column, false otherwise.
	 */
	private static function is_text_col( $type ) {
		foreach ( array( 'text', 'varchar' ) as $token ) {
			if ( false !== strpos( $type, $token ) )
				return true;
		}

		return false;
	}

	/**
	 * Escapes a MySQL string for inclusion in a `LIKE` clause. BC wrapper around different WP versions of this.
	 *
	 * @param string $old String to escape.
	 * @param string Escaped string.
	 */
	private static function esc_like( $old ) {
		global $wpdb;

		// Remove notices in 4.0 and support backwards compatibility
		if ( method_exists( $wpdb, 'esc_like' ) ) {
			// 4.0
			$old = $wpdb->esc_like( $old );
		} else {
			// 3.9 or less
			$old = like_escape( esc_sql( $old ) );
		}

		return $old;
	}

	/**
	 * Gets the color codes from the options if any, and returns the passed in array colorized with 2 elements per entry, a color code (or '') and a reset (or '').
	 *
	 * @param array $assoc_args The associative argument array passed to the command.
	 * @param array $colors Array of default percent color code strings keyed by the 3 color contexts 'table_column', 'id', 'match'.
	 * @return array Array containing 3 2-element arrays.
	 */
	private function get_colors( $assoc_args, $colors ) {
		$color_reset = WP_CLI::colorize( '%n' );

		$color_codes = implode( '', array_map( function ( $v ) {
			return substr( $v, 1 );
		}, array_keys( \cli\Colors::getColors() ) ) );

		$color_codes_regex = '/^(?:%[' . $color_codes . '])*$/';

		foreach ( array_keys( $colors ) as $color_col ) {
			if ( false !== ( $col_color_flag = \WP_CLI\Utils\get_flag_value( $assoc_args, $color_col . '_color', false ) ) ) {
				if ( ! preg_match( $color_codes_regex, $col_color_flag, $matches ) ) {
					WP_CLI::warning( "Unrecognized percent color code '$col_color_flag' for '{$color_col}_color'." );
				} else {
					$colors[ $color_col ] = $matches[0];
				}
			}
			$colors[ $color_col ] = $colors[ $color_col ] ? array( WP_CLI::colorize( $colors[ $color_col ] ), $color_reset ) : array( '', '' );
		}

		return $colors;
	}
}
