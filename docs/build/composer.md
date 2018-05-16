# Composer Install

We use [Composer](https://getcomposer.org/) to maintain dependencies. This allows us to **not** version control stuff that is already version controlled elsewhere.

## Composer in Docker
Traditionally, composer projects use the native `composer` command (e.g. `composer install`, `composer update`). Because Square One operates in Docker containers, we cannot rely on composer to behave appropriately in all environments because *composer runs on the host machine, and not in the docker container that has all the expected system requirements such as the appropriate PHP version.*

As such, Square one provides the `composer.sh` script that mirrors the native `composer` command. The only difference is that it runs *inside* the docker php-fpm container. The `composer.sh` command is found in `dev/docker/composer.sh`.

## Setting Up Square One
When you [initially start your docker containers](/dev/docker/README.md) using the `./dev/docker/start.sh` command, composer will look for a `composer-config.json` file in your `dev/docker` directory. This is a specially formatted JSON file used to authenticate against Github for private repo access. If it doesn't exist, you will be prompted to visit a URL on Github that will grant an oAuth token. Copy this token and paste it onto the command line at the prompt, and then create the `composer-config.json` file.

This will automatically also run `composer install` and setup Square One transparently. However, there may be times where, say, a version of WordPress needs to be bumped or a plugin needs updating. Make the change in `composer.json` and run `sh ./composer.sh update` from inside the `dev/docker` directory. This will download the updated packages and install in place. It will also upddate the `composer.lock` file which should be committed.


## Table of Contents

* [Overview](/docs/build/README.md)
* [Node](/docs/build/node.md)
* [Grunt Tasks](/docs/build/grunt.md)