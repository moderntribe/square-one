#!/usr/bin/env bash

SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

cd "$SCRIPTDIR"

PROJECT_ID=$(cat ./.projectID)

# Create an empty composer cache folder if it doesn't exist, so we can mount it to php-fpm
COMPOSER_CACHE=${SCRIPTDIR}/composer-cache
if [ ! -d ${COMPOSER_CACHE} ]; then
    mkdir ${COMPOSER_CACHE}
fi;

echo "Starting docker-compose project: ${PROJECT_ID}"


if [[ "$OSTYPE" == "darwin"* ]]; then
	D_COMMAND="docker"
	DC_COMMAND="docker-compose"
elif [[ $(which docker.exe) ]]; then
	D_COMMAND="docker.exe"
	DC_COMMAND="docker-compose.exe"
else
	D_COMMAND="docker"
	DC_COMMAND="docker-compose"
fi;

# Create a composer-config.json file that mirrors the format of .composer/auth.json, so we can mount it to php-fpm
CONFIG_FILE=${SCRIPTDIR}/composer-config.json
if [ ! -f ${CONFIG_FILE} ]; then
    echo "We have detected that you have not setup a GitHub oAuth token. Please go to https://github.com/settings/tokens/new?scopes=repo&description=Square%20One and create one. Then enter it here and press [ENTER]: "
    read githubtoken
    touch ${CONFIG_FILE}
    printf '{ "github-oauth": { "github.com": "%s" } }\n' "$githubtoken" >> ${CONFIG_FILE}
fi

# synchronize VM time with system time
${D_COMMAND} run --privileged --rm phpdockerio/php7-fpm date -s "$(date -u "+%Y-%m-%d %H:%M:%S")"

# start the containers
${DC_COMMAND} --project-name=${PROJECT_ID} up -d --force-recreate

sh ${SCRIPTDIR}/composer.sh install