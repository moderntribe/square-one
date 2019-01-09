#!/usr/bin/env bash

SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
cd "$SCRIPTDIR";

if [[ "$OSTYPE" == "darwin"* ]]; then
	DC_COMMAND="docker-compose -f ../docker/docker-compose.yml"
elif [[ $(which docker.exe) ]]; then
	DC_COMMAND="docker-compose.exe -f ../docker/docker-compose.yml"
else
	DC_COMMAND="docker-compose -f ../docker/docker-compose.yml"
fi;

PROJECT_ID=$(cat ../docker/.projectID)

${DC_COMMAND} --project-name=${PROJECT_ID} exec php-fpm composer "$@"  -d /application/www/dev/tests