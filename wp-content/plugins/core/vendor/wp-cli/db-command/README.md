wp-cli/db-command
=================

Perform basic database operations using credentials stored in wp-config.php.

[![Build Status](https://travis-ci.org/wp-cli/db-command.svg?branch=master)](https://travis-ci.org/wp-cli/db-command)

Quick links: [Using](#using) | [Installing](#installing) | [Contributing](#contributing) | [Support](#support)

## Using

This package implements the following commands:

### wp db create

Create a new database.

~~~
wp db create 
~~~

Runs `CREATE_DATABASE` SQL statement using `DB_HOST`, `DB_NAME`,
`DB_USER` and `DB_PASSWORD` database credentials specified in
wp-config.php.

**EXAMPLES**

    $ wp db create
    Success: Database created.



### wp db drop

Delete the existing database.

~~~
wp db drop [--yes]
~~~

Runs `DROP_DATABASE` SQL statement using `DB_HOST`, `DB_NAME`,
`DB_USER` and `DB_PASSWORD` database credentials specified in
wp-config.php.

**OPTIONS**

	[--yes]
		Answer yes to the confirmation message.

**EXAMPLES**

    $ wp db drop --yes
    Success: Database dropped.



### wp db reset

Remove all tables from the database.

~~~
wp db reset [--yes]
~~~

Runs `DROP_DATABASE` and `CREATE_DATABASE` SQL statements using
`DB_HOST`, `DB_NAME`, `DB_USER` and `DB_PASSWORD` database credentials
specified in wp-config.php.

**OPTIONS**

	[--yes]
		Answer yes to the confirmation message.

**EXAMPLES**

    $ wp db reset --yes
    Success: Database reset.



### wp db check

Check the current status of the database.

~~~
wp db check 
~~~

Runs `mysqlcheck` utility with `--check` using `DB_HOST`,
`DB_NAME`, `DB_USER` and `DB_PASSWORD` database credentials
specified in wp-config.php.

[See docs](http://dev.mysql.com/doc/refman/5.7/en/check-table.html)
for more details on the `CHECK TABLE` statement.

**EXAMPLES**

    $ wp db check
    Success: Database checked.



### wp db optimize

Optimize the database.

~~~
wp db optimize 
~~~

Runs `mysqlcheck` utility with `--optimize=true` using `DB_HOST`,
`DB_NAME`, `DB_USER` and `DB_PASSWORD` database credentials
specified in wp-config.php.

[See docs](http://dev.mysql.com/doc/refman/5.7/en/optimize-table.html)
for more details on the `OPTIMIZE TABLE` statement.

**EXAMPLES**

    $ wp db optimize
    Success: Database optimized.



### wp db prefix

Display the database table prefix.

~~~
wp db prefix 
~~~

Display the database table prefix, as defined by the database handler's interpretation of the current site.

**EXAMPLES**

    $ wp db prefix
    wp_



### wp db repair

Repair the database.

~~~
wp db repair 
~~~

Runs `mysqlcheck` utility with `--repair=true` using `DB_HOST`,
`DB_NAME`, `DB_USER` and `DB_PASSWORD` database credentials
specified in wp-config.php.

[See docs](http://dev.mysql.com/doc/refman/5.7/en/repair-table.html) for
more details on the `REPAIR TABLE` statement.

**EXAMPLES**

    $ wp db repair
    Success: Database repaired.



### wp db cli

Open a MySQL console using credentials from wp-config.php

~~~
wp db cli [--database=<database>] [--default-character-set=<character-set>] [--<field>=<value>]
~~~

**OPTIONS**

	[--database=<database>]
		Use a specific database. Defaults to DB_NAME.

	[--default-character-set=<character-set>]
		Use a specific character set. Defaults to DB_CHARSET when defined.

	[--<field>=<value>]
		Extra arguments to pass to the MySQL executable.

**EXAMPLES**

    # Open MySQL console
    $ wp db cli
    mysql>



### wp db query

Execute a SQL query against the database.

~~~
wp db query [<sql>] [--<field>=<value>]
~~~

Executes an arbitrary SQL query using `DB_HOST`, `DB_NAME`, `DB_USER`
 and `DB_PASSWORD` database credentials specified in wp-config.php.

**OPTIONS**

	[<sql>]
		A SQL query. If not passed, will try to read from STDIN.

	[--<field>=<value>]
		Extra arguments to pass to mysql.

**EXAMPLES**

    # Execute a query stored in a file
    $ wp db query < debug.sql

    # Check all tables in the database
    $ wp db query "CHECK TABLE $(wp db tables | paste -s -d',');"
    +---------------------------------------+-------+----------+----------+
    | Table                                 | Op    | Msg_type | Msg_text |
    +---------------------------------------+-------+----------+----------+
    | wordpress_dbase.wp_users              | check | status   | OK       |
    | wordpress_dbase.wp_usermeta           | check | status   | OK       |
    | wordpress_dbase.wp_posts              | check | status   | OK       |
    | wordpress_dbase.wp_comments           | check | status   | OK       |
    | wordpress_dbase.wp_links              | check | status   | OK       |
    | wordpress_dbase.wp_options            | check | status   | OK       |
    | wordpress_dbase.wp_postmeta           | check | status   | OK       |
    | wordpress_dbase.wp_terms              | check | status   | OK       |
    | wordpress_dbase.wp_term_taxonomy      | check | status   | OK       |
    | wordpress_dbase.wp_term_relationships | check | status   | OK       |
    | wordpress_dbase.wp_termmeta           | check | status   | OK       |
    | wordpress_dbase.wp_commentmeta        | check | status   | OK       |
    +---------------------------------------+-------+----------+----------+

    # Pass extra arguments through to MySQL
    $ wp db query 'SELECT * FROM wp_options WHERE option_name="home"' --skip-column-names
    +---+------+------------------------------+-----+
    | 2 | home | http://wordpress-develop.dev | yes |
    +---+------+------------------------------+-----+



### wp db export

Exports the database to a file or to STDOUT.

~~~
wp db export [<file>] [--<field>=<value>] [--tables=<tables>] [--exclude_tables=<tables>] [--porcelain]
~~~

Runs `mysqldump` utility using `DB_HOST`, `DB_NAME`, `DB_USER` and
`DB_PASSWORD` database credentials specified in wp-config.php.

**OPTIONS**

	[<file>]
		The name of the SQL file to export. If '-', then outputs to STDOUT. If omitted, it will be '{dbname}.sql'.

	[--<field>=<value>]
		Extra arguments to pass to mysqldump

	[--tables=<tables>]
		The comma separated list of specific tables to export. Excluding this parameter will export all tables in the database.

	[--exclude_tables=<tables>]
		The comma separated list of specific tables that should be skipped from exporting. Excluding this parameter will export all tables in the database.

	[--porcelain]
		Output filename for the exported database.

**EXAMPLES**

    # Export database with drop query included
    $ wp db export --add-drop-table
    Success: Exported to 'wordpress_dbase.sql'.

    # Export certain tables
    $ wp db export --tables=wp_options,wp_users
    Success: Exported to 'wordpress_dbase.sql'.

    # Export all tables matching a wildcard
    $ wp db export --tables=$(wp db tables 'wp_user*' --format=csv)
    Success: Exported to 'wordpress_dbase.sql'.

    # Export all tables matching prefix
    $ wp db export --tables=$(wp db tables --all-tables-with-prefix --format=csv)
    Success: Exported to 'wordpress_dbase.sql'.

    # Skip certain tables from the exported database
    $ wp db export --exclude_tables=wp_options,wp_users
    Success: Exported to 'wordpress_dbase.sql'.

    # Skip all tables matching a wildcard from the exported database
    $ wp db export --exclude_tables=$(wp db tables 'wp_user*' --format=csv)
    Success: Exported to 'wordpress_dbase.sql'.

    # Skip all tables matching prefix from the exported database
    $ wp db export --exclude_tables=$(wp db tables --all-tables-with-prefix --format=csv)
    Success: Exported to 'wordpress_dbase.sql'.



### wp db import

Import a database from a file or from STDIN.

~~~
wp db import [<file>] [--skip-optimization]
~~~

Runs SQL queries using `DB_HOST`, `DB_NAME`, `DB_USER` and
`DB_PASSWORD` database credentials specified in wp-config.php. This
does not create database by itself and only performs whatever tasks are
defined in the SQL.

**OPTIONS**

	[<file>]
		The name of the SQL file to import. If '-', then reads from STDIN. If omitted, it will look for '{dbname}.sql'.

	[--skip-optimization]
		When using an SQL file, do not include speed optimization such as disabling auto-commit and key checks.

**EXAMPLES**

    # Import MySQL from a file.
    $ wp db import wordpress_dbase.sql
    Success: Imported from 'wordpress_dbase.sql'.



### wp db search

Find a string in the database.

~~~
wp db search <search> [<tables>...] [--network] [--all-tables-with-prefix] [--all-tables] [--before_context=<num>] [--after_context=<num>] [--regex] [--regex-flags=<regex-flags>] [--regex-delimiter=<regex-delimiter>] [--table_column_once] [--one_line] [--matches_only] [--stats] [--table_column_color=<color_code>] [--id_color=<color_code>] [--match_color=<color_code>]
~~~

Searches through all or a selection of database tables for a given string, Outputs colorized references to the string.

Defaults to searching through all tables registered to $wpdb. On multisite, this default is limited to the tables for the current site.

**OPTIONS**

	<search>
		String to search for. The search is case-insensitive by default.

	[<tables>...]
		One or more tables to search through for the string.

	[--network]
		Search through all the tables registered to $wpdb in a multisite install.

	[--all-tables-with-prefix]
		Search through all tables that match the registered table prefix, even if not registered on $wpdb. On one hand, sometimes plugins use tables without registering them to $wpdb. On another hand, this could return tables you don't expect. Overrides --network.

	[--all-tables]
		Search through ALL tables in the database, regardless of the prefix, and even if not registered on $wpdb. Overrides --network and --all-tables-with-prefix.

	[--before_context=<num>]
		Number of characters to display before the match.
		---
		default: 40
		---

	[--after_context=<num>]
		Number of characters to display after the match.
		---
		default: 40
		---

	[--regex]
		Runs the search as a regular expression (without delimiters). The search becomes case-sensitive (i.e. no PCRE flags are added, except 'u' if the database charset is UTF-8). Delimiters must be escaped if they occur in the expression.

	[--regex-flags=<regex-flags>]
		Pass PCRE modifiers to the regex search (e.g. 'i' for case-insensitivity). Note that 'u' (UTF-8 mode) will not be automatically added.

	[--regex-delimiter=<regex-delimiter>]
		The delimiter to use for the regex. It must be escaped if it appears in the search string.
		---
		default: /
		---

	[--table_column_once]
		Output the 'table:column' line once before all matching row lines in the table column rather than before each matching row.

	[--one_line]
		Place the 'table:column' output on the same line as the row id and match ('table:column:id:match'). Overrides --table_column_once.

	[--matches_only]
		Only output the string matches (including context). No 'table:column's or row ids are outputted.

	[--stats]
		Output stats on the number of matches found, time taken, tables/columns/rows searched, tables skipped.

	[--table_column_color=<color_code>]
		Percent color code to use for the 'table:column' output. For a list of available percent color codes, see below. Default '%G' (bright green).

	[--id_color=<color_code>]
		Percent color code to use for the row id output. For a list of available percent color codes, see below. Default '%Y' (bright yellow).

	[--match_color=<color_code>]
		Percent color code to use for the match (unless both before and after context are 0, when no color code is used). For a list of available percent color codes, see below. Default '%3%k' (black on a mustard background).

The percent color codes available are:
'%y' Yellow (dark) (mustard)
'%g' Green (dark)
'%b' Blue (dark)
'%r' Red (dark)
'%m' Magenta (dark)
'%c' Cyan (dark)
'%w' White (dark) (light gray)
'%k' Black
'%Y' Yellow (bright)
'%G' Green (bright)
'%B' Blue (bright)
'%R' Red (bright)
'%M' Magenta (bright)
'%C' Cyan (bright)
'%W' White
'%K' Black (bright) (dark gray)
'%3' Yellow background (dark) (mustard)
'%2' Green background (dark)
'%4' Blue background (dark)
'%1' Red background (dark)
'%5' Magenta background (dark)
'%6' Cyan background (dark)
'%7' White background (dark) (light gray)
'%0' Black background
'%8' Reverse
'%U' Underline
'%F' Blink (unlikely to work)
They can be concatenated. For instance, the default match color of black on a mustard (dark yellow) background '%3%k' can be made black on a bright yellow background with '%Y%0%8'.

**EXAMPLES**

    # Search through the database for the 'wordpress-develop' string
    $ wp db search wordpress-develop
    wp_options:option_value
    1:http://wordpress-develop.dev
    wp_options:option_value
    1:http://example.com/foo
        ...

    # Search through a multisite database on the subsite 'foo' for the 'example.com' string
    $ wp db search example.com --url=example.com/foo
    wp_2_comments:comment_author_url
    1:http://example.com/
    wp_2_options:option_value
        ...

    # Search through the database for the 'https?://' regular expression, printing stats.
    $ wp db search 'https?:\/\/' --regex --stats
    wp_comments:comment_author_url
    1:https://wordpress.org/
        ...
    Success: Found 99146 matches in 10.752s (10.559s searching). Searched 12 tables, 53 columns, 1358907 rows. 1 table skipped: wp_term_relationships.



### wp db tables

List the database tables.

~~~
wp db tables [<table>...] [--scope=<scope>] [--network] [--all-tables-with-prefix] [--all-tables] [--format=<format>]
~~~

Defaults to all tables registered to the $wpdb database handler.

**OPTIONS**

	[<table>...]
		List tables based on wildcard search, e.g. 'wp_*_options' or 'wp_post?'.

	[--scope=<scope>]
		Can be all, global, ms_global, blog, or old tables. Defaults to all.

	[--network]
		List all the tables in a multisite install. Overrides --scope=<scope>.

	[--all-tables-with-prefix]
		List all tables that match the table prefix even if not registered on $wpdb. Overrides --network.

	[--all-tables]
		List all tables in the database, regardless of the prefix, and even if not registered on $wpdb. Overrides --all-tables-with-prefix.

	[--format=<format>]
		Render output in a particular format.
		---
		default: list
		options:
		  - list
		  - csv
		---

**EXAMPLES**

    # List tables for a single site, without shared tables like 'wp_users'
    $ wp db tables --scope=blog --url=sub.example.com
    wp_3_posts
    wp_3_comments
    wp_3_options
    wp_3_postmeta
    wp_3_terms
    wp_3_term_taxonomy
    wp_3_term_relationships
    wp_3_termmeta
    wp_3_commentmeta

    # Export only tables for a single site
    $ wp db export --tables=$(wp db tables --url=sub.example.com --format=csv)
    Success: Exported to wordpress_dbase.sql



### wp db size

Display the database name and size.

~~~
wp db size [--size_format] [--tables] [--format] [--scope=<scope>] [--network] [--all-tables-with-prefix] [--all-tables]
~~~

Display the database name and size for `DB_NAME` specified in wp-config.php.
The size defaults to a human-readable number.

**OPTIONS**

	[--size_format]
		Display the database size only, as a bare number.
		---
		default: b
		options:
		 - b (bytes)
		 - kb (kilobytes)
		 - mb (megabytes)
		 ---

	[--tables]
		Display each table name and size instead of the database size.

	[--format]
		table, csv, json
		---
		default: table
		options:
		  - table
		  - csv
		  - json
		---

	[--scope=<scope>]
		Can be all, global, ms_global, blog, or old tables. Defaults to all.

	[--network]
		List all the tables in a multisite install. Overrides --scope=<scope>.

	[--all-tables-with-prefix]
		List all tables that match the table prefix even if not registered on $wpdb. Overrides --network.

	[--all-tables]
		List all tables in the database, regardless of the prefix, and even if not registered on $wpdb. Overrides --all-tables-with-prefix.

**EXAMPLES**

    $ wp db size
    +-------------------+------+
    | Name              | Size |
    +-------------------+------+
    | wordpress_default | 6 MB |
    +-------------------+------+

    $ wp db size --tables
    +-----------------------+-------+
    | Name                  | Size  |
    +-----------------------+-------+
    | wp_users              | 64 KB |
    | wp_usermeta           | 48 KB |
    | wp_posts              | 80 KB |
    | wp_comments           | 96 KB |
    | wp_links              | 32 KB |
    | wp_options            | 32 KB |
    | wp_postmeta           | 48 KB |
    | wp_terms              | 48 KB |
    | wp_term_taxonomy      | 48 KB |
    | wp_term_relationships | 32 KB |
    | wp_termmeta           | 48 KB |
    | wp_commentmeta        | 48 KB |
    +-----------------------+-------+

    $ wp db size --size_format=b
    5865472

    $ wp db size --size_format=kb
    5728

    $ wp db size --size_format=mb
    6

## Installing

This package is included with WP-CLI itself, no additional installation necessary.

To install the latest version of this package over what's included in WP-CLI, run:

    wp package install git@github.com:wp-cli/db-command.git

## Contributing

We appreciate you taking the initiative to contribute to this project.

Contributing isn’t limited to just code. We encourage you to contribute in the way that best fits your abilities, by writing tutorials, giving a demo at your local meetup, helping other users with their support questions, or revising our documentation.

For a more thorough introduction, [check out WP-CLI's guide to contributing](https://make.wordpress.org/cli/handbook/contributing/). This package follows those policy and guidelines.

### Reporting a bug

Think you’ve found a bug? We’d love for you to help us get it fixed.

Before you create a new issue, you should [search existing issues](https://github.com/wp-cli/db-command/issues?q=label%3Abug%20) to see if there’s an existing resolution to it, or if it’s already been fixed in a newer version.

Once you’ve done a bit of searching and discovered there isn’t an open or fixed issue for your bug, please [create a new issue](https://github.com/wp-cli/db-command/issues/new). Include as much detail as you can, and clear steps to reproduce if possible. For more guidance, [review our bug report documentation](https://make.wordpress.org/cli/handbook/bug-reports/).

### Creating a pull request

Want to contribute a new feature? Please first [open a new issue](https://github.com/wp-cli/db-command/issues/new) to discuss whether the feature is a good fit for the project.

Once you've decided to commit the time to seeing your pull request through, [please follow our guidelines for creating a pull request](https://make.wordpress.org/cli/handbook/pull-requests/) to make sure it's a pleasant experience. See "[Setting up](https://make.wordpress.org/cli/handbook/pull-requests/#setting-up)" for details specific to working on this package locally.

## Support

Github issues aren't for general support questions, but there are other venues you can try: http://wp-cli.org/#support


*This README.md is generated dynamically from the project's codebase using `wp scaffold package-readme` ([doc](https://github.com/wp-cli/scaffold-package-command#wp-scaffold-package-readme)). To suggest changes, please submit a pull request against the corresponding part of the codebase.*
