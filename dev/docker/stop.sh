#!/bin/bash

SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
cd "$SCRIPTDIR";

PROJECT_ID="square1"

docker-compose --project-name=${PROJECT_ID} down

