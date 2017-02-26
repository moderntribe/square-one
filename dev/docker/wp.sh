#!/bin/bash
# Runs WP CLI commands in a php container

SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
cd "$SCRIPTDIR";

PROJECT_ID="square1"

docker run --rm -i \
 -v="$SCRIPTDIR/php/php.ini:/etc/php7/conf.d/zz-php.ini:ro" \
 -v="$SCRIPTDIR/php/ext-xdebug.ini:/etc/php7/conf.d/zz-xdebug.ini:ro" \
 -v="$SCRIPTDIR/wp-cli.yml:/srv/www/wp-cli.yml" \
 -v="$SCRIPTDIR/../..:/srv/www/public" \
 --link="${PROJECT_ID}_memcached_1:memcached" \
 --link="global_mysql_1:mysql" \
 --dns=10.254.254.254 \
 --dns=8.8.8.8 \
 --network=global_default \
 -w="/srv/www/public" \
 --entrypoint="/usr/local/bin/wp" \
 moderntribe/php:7.0-fpm --allow-root "$@"