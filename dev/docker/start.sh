#!/usr/bin/env bash

SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

cd "$SCRIPTDIR"

ENV_FILE=../../.env

if [ ! -f ${ENV_FILE} ]; then
  echo "Can't find file: $(git rev-parse --show-toplevel)/.env. Copy .env.sample to .env and follow the instructions inside."
  exit 1
fi

PROJECT_ID=$(cat ./.projectID)

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

# Create an empty composer folder so we can mount it with the proper permissions
COMPOSER_DIR=${SCRIPTDIR}/composer

if [ ! -d "${COMPOSER_DIR}/" ]; then
  mkdir ${COMPOSER_DIR}
  # create global composer config otherwise it becomes unreadable on the host in linux
  printf '{
      "config": {},
      "repositories": {
          "packagist": {
              "type": "composer",
              "url": "https://packagist.org"
          }
      }
  }' >> ${COMPOSER_DIR}/config.json
fi

# Check for a volume mounted auth.json so we can populate it with github credentials so composer can fetch private repos
CONFIG_FILE="${COMPOSER_DIR}/auth.json"

# If the auth file is empty, composer will completely error out.
if [ ! -s ${CONFIG_FILE} ]; then
  rm -rf ${CONFIG_FILE}
fi

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
# only try this when TravisCI is not running
if [ -z "$CI" ]; then
    if [ ! -f ../bin/wp ]; then
        WPBINARY=$(which wp)
        ln -s ${WPBINARY} ../bin/wp
    fi
fi

# synchronize VM time with system time
${D_COMMAND} run --privileged --rm phpdockerio/php7-fpm date -s "$(date -u "+%Y-%m-%d %H:%M:%S")"

# start the containers
${DC_COMMAND} --project-name=${PROJECT_ID} up -d --force-recreate

# Install composer parallel installer globally
if [ ! -f "${COMPOSER_DIR}/composer.json" ]; then
  bash ${SCRIPTDIR}/exec.sh composer global require hirak/prestissimo --classmap-authoritative --update-no-dev
fi

bash ${SCRIPTDIR}/composer.sh install --optimize-autoloader
