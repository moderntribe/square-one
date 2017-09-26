wp-cli/config-command
=====================

Manage the wp-config.php file.

[![Build Status](https://travis-ci.org/wp-cli/config-command.svg?branch=master)](https://travis-ci.org/wp-cli/config-command)

Quick links: [Using](#using) | [Installing](#installing) | [Contributing](#contributing) | [Support](#support)

## Using

This package implements the following commands:

### wp config

Manage the wp-config.php file

~~~
wp config
~~~





### wp config create

Generate a wp-config.php file.

~~~
wp config create --dbname=<dbname> --dbuser=<dbuser> [--dbpass=<dbpass>] [--dbhost=<dbhost>] [--dbprefix=<dbprefix>] [--dbcharset=<dbcharset>] [--dbcollate=<dbcollate>] [--locale=<locale>] [--extra-php] [--skip-salts] [--skip-check] [--force]
~~~

Creates a new wp-config.php with database constants, and verifies that
the database constants are correct.

**OPTIONS**

	--dbname=<dbname>
		Set the database name.

	--dbuser=<dbuser>
		Set the database user.

	[--dbpass=<dbpass>]
		Set the database user password.

	[--dbhost=<dbhost>]
		Set the database host.
		---
		default: localhost
		---

	[--dbprefix=<dbprefix>]
		Set the database table prefix.
		---
		default: wp_
		---

	[--dbcharset=<dbcharset>]
		Set the database charset.
		---
		default: utf8
		---

	[--dbcollate=<dbcollate>]
		Set the database collation.
		---
		default:
		---

	[--locale=<locale>]
		Set the WPLANG constant. Defaults to $wp_local_package variable.

	[--extra-php]
		If set, the command copies additional PHP code into wp-config.php from STDIN.

	[--skip-salts]
		If set, keys and salts won't be generated, but should instead be passed via `--extra-php`.

	[--skip-check]
		If set, the database connection is not checked.

	[--force]
		Overwrites existing files, if present.

**EXAMPLES**

    # Standard wp-config.php file
    $ wp config create --dbname=testing --dbuser=wp --dbpass=securepswd --locale=ro_RO
    Success: Generated 'wp-config.php' file.

    # Enable WP_DEBUG and WP_DEBUG_LOG
    $ wp config create --dbname=testing --dbuser=wp --dbpass=securepswd --extra-php <<PHP
    $ define( 'WP_DEBUG', true );
    $ define( 'WP_DEBUG_LOG', true );
    $ PHP
    Success: Generated 'wp-config.php' file.

    # Avoid disclosing password to bash history by reading from password.txt
    # Using --prompt=dbpass will prompt for the 'dbpass' argument
    $ wp config create --dbname=testing --dbuser=wp --prompt=dbpass < password.txt
    Success: Generated 'wp-config.php' file.



### wp config get

Get variables, constants, and file includes defined in wp-config.php file.

~~~
wp config get [--fields=<fields>] [--constant=<constant>] [--global=<global>] [--format=<format>]
~~~

**OPTIONS**

	[--fields=<fields>]
		Limit the output to specific fields. Defaults to all fields.

	[--constant=<constant>]
		Returns the value of a specific constant defined in the wp-config.php file.

	[--global=<global>]
		Returns the value of a specific global defined in the wp-config.php file.

	[--format=<format>]
		Render output in a particular format.
		---
		default: table
		options:
		  - table
		  - csv
		  - json
		  - yaml
		---

**EXAMPLES**

    # List variables and constants defined in wp-config.php file.
    $ wp config get --format=table
    +------------------+------------------------------------------------------------------+----------+
    | key              | value                                                            | type     |
    +------------------+------------------------------------------------------------------+----------+
    | table_prefix     | wp_                                                              | variable |
    | DB_NAME          | wp_cli_test                                                      | constant |
    | DB_USER          | root                                                             | constant |
    | DB_PASSWORD      | root                                                             | constant |
    | AUTH_KEY         | r6+@shP1yO&$)1gdu.hl[/j;7Zrvmt~o;#WxSsa0mlQOi24j2cR,7i+QM/#7S:o^ | constant |
    | SECURE_AUTH_KEY  | iO-z!_m--YH$Tx2tf/&V,YW*13Z_HiRLqi)d?$o-tMdY+82pK$`T.NYW~iTLW;xp | constant |
    +------------------+------------------------------------------------------------------+----------+



### wp config path

Get the path to wp-config.php file.

~~~
wp config path 
~~~

**EXAMPLES**

    # Get wp-config.php file path
    $ wp config path
    /home/person/htdocs/project/wp-config.php

## Installing

This package is included with WP-CLI itself, no additional installation necessary.

To install the latest version of this package over what's included in WP-CLI, run:

    wp package install git@github.com:wp-cli/config-command.git

## Contributing

We appreciate you taking the initiative to contribute to this project.

Contributing isn’t limited to just code. We encourage you to contribute in the way that best fits your abilities, by writing tutorials, giving a demo at your local meetup, helping other users with their support questions, or revising our documentation.

For a more thorough introduction, [check out WP-CLI's guide to contributing](https://make.wordpress.org/cli/handbook/contributing/). This package follows those policy and guidelines.

### Reporting a bug

Think you’ve found a bug? We’d love for you to help us get it fixed.

Before you create a new issue, you should [search existing issues](https://github.com/wp-cli/config-command/issues?q=label%3Abug%20) to see if there’s an existing resolution to it, or if it’s already been fixed in a newer version.

Once you’ve done a bit of searching and discovered there isn’t an open or fixed issue for your bug, please [create a new issue](https://github.com/wp-cli/config-command/issues/new). Include as much detail as you can, and clear steps to reproduce if possible. For more guidance, [review our bug report documentation](https://make.wordpress.org/cli/handbook/bug-reports/).

### Creating a pull request

Want to contribute a new feature? Please first [open a new issue](https://github.com/wp-cli/config-command/issues/new) to discuss whether the feature is a good fit for the project.

Once you've decided to commit the time to seeing your pull request through, [please follow our guidelines for creating a pull request](https://make.wordpress.org/cli/handbook/pull-requests/) to make sure it's a pleasant experience. See "[Setting up](https://make.wordpress.org/cli/handbook/pull-requests/#setting-up)" for details specific to working on this package locally.

## Support

Github issues aren't for general support questions, but there are other venues you can try: http://wp-cli.org/#support


*This README.md is generated dynamically from the project's codebase using `wp scaffold package-readme` ([doc](https://github.com/wp-cli/scaffold-package-command#wp-scaffold-package-readme)). To suggest changes, please submit a pull request against the corresponding part of the codebase.*
