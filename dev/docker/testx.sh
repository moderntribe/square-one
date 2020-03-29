#!/usr/bin/env bash

# Runs Codeception commands with xdebug enabled

SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
cd "$SCRIPTDIR";
PROJECT_ID=$(cat ./.projectID)

# Cleanup debug files
${SCRIPTDIR}/exec.sh \
php \
-dxdebug.remote_autostart=0 \
-dxdebug.remote_enable=0 \
/application/www/vendor/bin/codecept \
--config "/application/www/dev/tests" clean

# Run the codeception command with tests
${SCRIPTDIR}/exec.sh \
php \
-dxdebug.remote_autostart=1 \
-dxdebug.remote_host=host.tribe \
-dxdebug.remote_enable=1 \
/application/www/vendor/bin/codecept \
--config "/application/www/dev/tests" "$@" \
--override "modules: config: WPBrowser: cookies: cookie-1: Name: XDEBUG_SESSION" \
--override "modules: config: WPBrowser: cookies: cookie-1: Value: PHPSTORM" \
--override "modules: config: WPWebDriver: xdebug_enabled: true"