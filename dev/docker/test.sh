#!/usr/bin/env bash

# Runs Codeception commands with xdebug enabled

SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
cd "$SCRIPTDIR";
PROJECT_ID=$(cat ./.projectID)

${SCRIPTDIR}/exec.sh \
php \
-dxdebug.remote_autostart=0 \
-dxdebug.remote_enable=0 \
/application/www/vendor/bin/codecept \
--config "/application/www/dev/tests" "$@"