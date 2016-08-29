#!/bin/sh

set -e

cd "$(dirname "$0")/../../"

read -p "Running this script will initialize/re-initialize the repository.  Are you sure you want to continue? [y/n] " -n 1 -r
echo
if ! [[ $REPLY =~ ^[Yy]$ ]]
then
    exit
fi

if [ -z "$1" ]; then
    echo "Please provide a new name for this project, like: init.sh new-project-name, it should match the project name on GitHub"
    exit
fi

# Initialize as a new repo
rm -rf .git
git init

# re-add submodules
git config -f .gitmodules --get-regexp '^submodule\..*\.path$' |
while read path_key path
do
    url_key=$(echo $path_key | sed 's/\.path/.url/')
    url=$(git config -f .gitmodules --get "$url_key")
    if [ -d "$path" ]; then
        rm -rf "$path"
    fi
    git submodule add $url $path
done

git add .
git commit -m "Initial commit after copy from square-one"

git remote add origin "git@github.com:moderntribe/$1.git"

# create necessary branches
git branch develop
git branch server/dev