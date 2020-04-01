#!/bin/bash

SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
cd "$SCRIPTDIR";

environment=$1; shift
forceyes=false
branch=server/$environment

while getopts "b:y" opt; do
    case "$opt" in
        b)
            branch=$OPTARG
            ;;
        y)
            forceyes=true
            ;;
    esac
done

if [ ! -f ".host/config/$environment.cfg" ]; then
    echo "Unknown environment: $environment"
    exit 1
fi

source ".host/config/common.cfg"
source ".host/config/$environment.cfg"
deploy_timestamp=`date +%Y%m%d%H%M%S`

echo "Preparing to deploy $branch to $environment"

if [ -d .deploy/src ]; then
    cd .deploy/src
    git submodule foreach git reset --hard
    git submodule foreach git fetch --tags
    git submodule update
    cd ../..
else
    git clone $dev_repo .deploy/src
fi

cd .deploy/src
git reset --hard HEAD
git checkout $branch
git pull origin $branch
git submodule update --init --recursive
commit_hash=$(git rev-parse HEAD)
cd ../..


if [ -d .deploy/build ]; then
    cd .deploy/build
    git submodule foreach git reset --hard
    git submodule foreach git fetch --tags
    git submodule update
    cd ../..
else
    git clone $deploy_repo .deploy/build
fi

GIT_SSH_COMMAND="ssh -i .host/ansible_rsa -F /dev/null"

cd .deploy/build

if [ ! -d .git ]; then
    echo "Build directory is not a git repository. Aborting..."
    exit 1
fi

if git config "remote.$environment.url" > /dev/null; then
    git remote set-url $environment $deploy_repo
else
    git remote add $environment $deploy_repo
fi
git config core.autocrlf false
git fetch $environment
git checkout master
git reset --hard $environment/master

cd ../..

echo "syncing WordPress core files"
rsync -rp --delete .deploy/src/wp/ .deploy/build \
    --exclude=.git \
    --exclude=.gitmodules \
    --exclude=.gitignore \
    --exclude=.htaccess \
    --exclude=wp-content

echo "syncing wp-content dir"
rsync -rp --delete .deploy/src/wp-content .deploy/build \
    --exclude=.git \
    --exclude=.gitmodules \
    --exclude=.gitignore \
    --exclude=.htaccess \
    --exclude=.babelrc \
    --exclude=.editorconfig \
    --exclude=.eslintrc \
    --exclude=dev \
    --exclude=dev_components \
    --exclude=docs \
    --exclude=grunt_options \
    --exclude=node_modules \
    --exclude=wp-content/plugins/core/assets/templates/cli

echo "syncing configuration files"
# not wp-config.php. WP Engine manages that
rsync -rp .deploy/src/ .deploy/build \
    --include=local-config-sample.php \
    --include=general-config.php \
    --include=build-process.php \
    --include=.wpengine.htaccess \
    --exclude=*

cd .deploy/build
mv .wpengine.htaccess .htaccess
git add -Av

if [ $forceyes == false ]; then
    read -p "Ready to deploy $branch to $environment. Have you made a backup? [Y/n] " yn
    case $yn in
        [Yy]* ) ;;
        [Nn]* ) exit;;
        * ) exit;;
    esac
fi

git commit --allow-empty -m "Deployment $deploy_timestamp"
echo "pushing to $environment"
git push $environment master

echo "done"
