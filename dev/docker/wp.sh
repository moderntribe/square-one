#!/bin/bash
# Runs WP CLI commands in a php container

SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
cd "$SCRIPTDIR";

PROJECT_ID=$(cat ./.projectID)

docker-compose --project-name=${PROJECT_ID} exec php-fpm wp --allow-root "$@"
