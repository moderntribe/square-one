# CLI

## Overview

WP-CLI is an indispensable tool for managing sites but is equally as useful for automating repetitive tasks.

## Writing a command
To add a command to S1, extend `\Tribe\Libs\CLI\Command`. Note that the return values from 4 abstract methods are used to generate the command.

* `command` returns a text slug for the command. Example:
  ```php
  public function command() {
    return 'my-command';
  }
  ```
  You would call this command with `wp s1 my-command`
* `description` returns a text description for the command. Example:
  ```php
  public function description() {
    return __( 'Does a thing', 'tribe' );
  }
  ```
* `arguments` returns an array of arguments accepted by the command. Example:
  ```php
  public function arguments() {
    return [
      [
        'type'        => 'positional',
        'name'        => 'arbitrary',
        'optional'    => false,
        'description' => __( 'An arbitrary argument', 'tribe' ),
      ],
      [
        'type'        => 'assoc',
        'name'        => 'label',
        'optional'    => true,
        'description' => __( 'The label for the thing', 'tribe' ),
      ],
      [
        'type'        => 'flag',
        'name'        => 'dry-run',
        'optional'    => true,
        'default'     => false,
        'description' => __( 'During a dry-run, actions will be logged but not executed', 'tribe' ),
      ]
    ];
  }
  ```
* `run_command` is the command's callback method and takes the params `$args` and `$assoc_args`. Example:
  ```php
  public function run_command( $args, $assoc_args ) {
    $arbitrary = $args[0];
    $label     = $assoc_args['label'] ?? __( 'A default label', 'tribe' );
    $dry_run   = \WP_CLI\Utils\get_flag_value( $assoc_args, 'dry-run', false );
    // do stuff ...
  }
  ```

## Registering a command

Once the command has been created, it must be registered. This is accomplished in your application's `Definer` class by
adding it to the `\Tribe\Libs\CLI\CLI_Definer::COMMANDS` array. Example:

```php
<?php
declare( strict_types=1 );

namespace Tribe\Project\Something;

use DI;
use Tribe\Libs\CLI\CLI_Definer;
use Tribe\Libs\Container\Definer_Interface;

class Generator_Definer implements Definer_Interface {
	public function define(): array {
		return [
			/**
			 * Add commands for the CLI subscriber to register
			 */
			CLI_Definer::COMMANDS      => DI\add( [
				DI\get( My_Command::class ),
			] ),
		];
	}
}
```

While it is generally preferred that your custom commands extend `\Tribe\Libs\CLI\Command`, `CLI_Definer::COMMANDS`
can accept any class that implements `\Tribe\Libs\CLI\Command_Interface`.

## Built-in commands

For a list of available `s1` commands, run `wp s1`. Example output:

```
$ wp s1
usage: wp s1 cache-prime [--target-url=<target-url>]
   or: wp s1 generate <command>
   or: wp s1 import <command>
   or: wp s1 queues <command>

See 'wp help s1 <command>' for more information on a specific command.

$ wp s1 generate
usage: wp s1 generate cli <command> [--description=<description>]
   or: wp s1 generate component <component> [--properties=<properties>] [--template] [--context] [--controller] [--css] [--js] [--dry-run]
   or: wp s1 generate cpt <cpt> [--single=<single>] [--plural=<plural>]
   or: wp s1 generate settings <settings>
   or: wp s1 generate tax <taxonomy> [--post-types=<post-types>] [--single=<single>] [--plural=<plural>]

See 'wp help s1 generate <command>' for more information on a specific command.
```
