#!/usr/bin/env bash

# Runs WP CLI commands with xdebug enabled
#
# To use, SSH into the container and run this script
#
# cd dev/docker
# ./exec.sh /bin/bash
# cd /application/www/dev/docker
# ./wpx.sh <command>
#
# OR
#
# From your host machine, run:
#
# cd dev/docker
# ./exec.sh /application/www/dev/docker/wpx.sh <command>

WP_CLI="/usr/local/bin/wp"
SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
cd "$SCRIPTDIR";
PROJECT_ID=$(cat ./.projectID)

PHP_IDE_CONFIG="serverName=${PROJECT_ID}.tribe" php -dxdebug.remote_autostart=1 -dxdebug.remote_host=host.tribe -dxdebug.remote_enable=1 ${WP_CLI} --allow-root "$@"
