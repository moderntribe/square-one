#!/usr/bin/env bash

if [[ "$OSTYPE" == "darwin"* ]]; then
	DC_COMMAND="docker-compose"
elif [[ $(which docker.exe) ]]; then
	DC_COMMAND="docker-compose.exe"
else
	DC_COMMAND="docker-compose"
fi;

PROJECT_ID=$(cat ./.projectID)

${DC_COMMAND} --project-name=${PROJECT_ID} exec php-fpm composer "$@"  -d=/application/www