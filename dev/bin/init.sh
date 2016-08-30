#!/bin/sh

set -e

cd "$(dirname "$0")/../../"

if [ -z "$1" ]; then
    echo "Please provide a new name for this project, like: init.sh new-project-name, it should match the project name on GitHub"
    exit
fi

git remote set-url origin "git@github.com:moderntribe/$1.git"
git remote add upstream "git@github.com:moderntribe/square-one.git"

# create necessary branches
git branch develop
git branch server/dev
git branch server/production