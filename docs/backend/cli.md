# CLI

## Overview

WP-CLI is an indispensable tool for managing sites but is equally as useful for automating repetitive tasks.

## Writing a command
To add a command to S1, extend `Command`. Note that the return values from 4 abstract methods are used to generate the command.<BR>
__command__ returns a text slug for the command. ex: `cli`<BR>
__run_command__ is the called method and takes the params $args and $assoc_args.<BR>
__description__ returns a text description for the command. ex: `Generates a new CLI command`<BR>
__arguments__ returns an array of arguments accepted by the callback. ex: 
```
[
    [
        'type'        => 'positional',
        'name'        => 'command',
        'optional'    => false,
        'description' => __( 'The command slug.', 'tribe' ),
    ],
    [
        'type'        => 'optional',
        'name'        => 'description',
        'optional'    => true,
        'description' => __( 'Command description.', 'tribe' ),
    ],
]
```

## Built-in commands
### CLI
#### Generators ####
`wp s1 generate cli`<BR>
Is used to create a new CLI command. This is a good jumping off point for something simple.
In addition to creating the command, it updates the CLI Service Provider to include the new command.
usage: `wp s1 generate cli <command>`

`wp s1 generate cpt`<BR>
Creates a new CPT and accepts single/plural strings. Further, the creation of a config file is optional (defaults to true).
usage: `wp s1 generate cpt <cpt> [--single=<single>] [--plural=<plural>] [--config]`

`wp s1 generate tax`<BR>
Creates a new Taxonomy and accepts single/plural strings and a comma separated list of post-types to associate with. Further the creation of a config file is optional (defaults to true).
usage: `wp s1 generate tax <tax> [--single=<single>] [--plural=<plural>] [--post_types=<post_type,post_type>] [--config]`

`wp s1 generate settings`<BR>
Creates a new empty settings page.
Page name value should be treated like a cpt/tax name. 
usage: `wp s1 generate settings <settings-page-name>`

#### Utility ####
`wp s1 pimple`<BR>
Dumps the files needed for [silex-pimple-plugin](https://plugins.jetbrains.com/plugin/7809-silex-pimple-plugin) to provide jump-to definition in PHP Storm.
usage: `wp s1 pimple`

#### DevOps ####
`wp s1 cache-prime`<BR>
Clears caches for all URLs on a given page (the default is the homepage). If the URL is external, there will be no effect. In this way, you are able to "get a head start" on caching. This is particularly useful on deploys. usage: `wp s1 cache-prime  [--target-url=<target-url>]`
