#!/bin/bash
# Runs WP CLI commands in a php container

SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

${SCRIPTDIR}/exec.sh wp --allow-root "$@"
