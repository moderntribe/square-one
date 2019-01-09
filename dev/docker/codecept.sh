#!/bin/bash

# Runs codeception on the core plugin with xdebug enabled
#
# To use, SSH into the container and run this script
#
# cd dev/docker
# ./exec.sh /bin/bash
# cd /application/www/dev/docker
# ./codecept.sh run integration
#
# OR
#
# From your host machine, run:
#
# cd dev/docker
# ./exec.sh /application/www/dev/docker/codecept.sh run integration
#
# OR
#
# Set a Run Configuration in PhpStorm to run the above script for you
# https://cldup.com/06_SCMNA9z.png


SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
cd "$SCRIPTDIR";
PROJECT_ID=$(cat ./.projectID)

cd "../../dev/tests";

echo $PATH;

PHP_IDE_CONFIG="serverName=${PROJECT_ID}.tribe" php -dxdebug.remote_autostart=1 -dxdebug.remote_host=host.tribe -dxdebug.remote_enable=1 ../../vendor/bin/codecept "$@"
