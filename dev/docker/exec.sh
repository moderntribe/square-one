#!/usr/bin/env bash
# Executes a script in the php-fpm container

SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
cd "$SCRIPTDIR";

PROJECT_ID=$(cat ./.projectID)

if [[ "$OSTYPE" == "darwin"* ]]; then
	DC_COMMAND="docker-compose"
elif [[ $(which docker.exe) ]]; then
	DC_COMMAND="docker-compose.exe"
else
	DC_COMMAND="docker-compose"
fi;

COMPOSE_INTERACTIVE_NO_CLI=1 ${DC_COMMAND} --project-name=${PROJECT_ID} exec php-fpm "$@"