#!/bin/bash

#############################
# Installation
####
# gem install docker-sync
# brew install fswatch
# ############################

SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
cd "$SCRIPTDIR";

docker-sync start
docker-sync clean
