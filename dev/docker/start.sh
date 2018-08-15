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
CONFIG_FILE="${SCRIPTDIR}/composer-config.json"

if [ ! -f ${CONFIG_FILE} ]; then

    touch ${CONFIG_FILE}

    # Check for the TravisCI environment variable which exists when TravisCI is running.
    if [ "$CI" = true ]; then
        # Run only when Travis is detected, `$CI_USER_TOKEN` is an encrypted github Personal Access Token
        sudo printf '{ "github-oauth": { "github.com": "%s" } }\n' "$CI_USER_TOKEN" >> ${CONFIG_FILE}
        sudo chown travis:travis ${CONFIG_FILE}
    else
        # urlencode strings
        urlencode() {
            local LANG=C i c e=''
            for ((i=0;i<${#1};i++)); do
                        c=${1:$i:1}
                [[ "$c" =~ [a-zA-Z0-9\.\~\_\-] ]] || printf -v c '%%%02X' "'$c"
                        e+="$c"
            done
                echo "$e"
        }

        # create a urlencoded description using the Project ID and the git repo name, e.g. 'square1%20-%20square-one'
        TOKEN_DESCRIPTION=$(urlencode "${PROJECT_ID} - $(basename `git rev-parse --show-toplevel`)")
        echo "We have detected that you have not setup a GitHub oAuth token. Please go to https://github.com/settings/tokens/new?scopes=repo&description=${TOKEN_DESCRIPTION} and create one. Then enter it here and press [ENTER]: "
        read githubtoken
        printf '{ "github-oauth": { "github.com": "%s" } }\n' "$githubtoken" >> ${CONFIG_FILE}
    fi
fi

# symlink wp cli binary to the dev/bin directory so wpx.sh works
if [ ! -f ././../bin/wp ]; then
    WPBINARY=$(which wp)
    ln -s ${WPBINARY} ././../bin/wp
fi

# synchronize VM time with system time
${D_COMMAND} run --privileged --rm phpdockerio/php7-fpm date -s "$(date -u "+%Y-%m-%d %H:%M:%S")"

# start the containers
${DC_COMMAND} --project-name=${PROJECT_ID} up -d --force-recreate

bash ${SCRIPTDIR}/composer.sh install
