#!/bin/bash

#############################
# Installation
##############################
# gem install docker-sync
# brew install fswatch
# brew install unison
# brew install python
# sudo easy_install pip
# sudo pip install unox
# sudo pip install macfsevents
# ############################

SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
cd "$SCRIPTDIR";

docker-sync start
docker-sync clean
