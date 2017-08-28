#!/usr/bin/env bash

SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

cd "$SCRIPTDIR"

docker-compose --project-name=global logs --follow