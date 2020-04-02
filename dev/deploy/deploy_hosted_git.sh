#!/bin/bash

function console_log(){
  echo ""
  echo "#########################################################################"
  echo "### $1"
  echo ""
}

if[ ! -f "./deploy.sh"]; then
    echo "Script must be run in the /dev/deploy/ directory. Aborting..."
    exit 1
fi
exit 1
if [ $forceyes == false ]; then
    console_log ""
    read -p "This will Deploy $branch to $environment. Have you made a backup? [Y/n] " yn
    case $yn in
        [Yy]* ) ;;
        [Nn]* ) exit;;
        * ) exit;;
    esac
fi

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

console_log "Preparing to deploy $branch to $environment"
console_log "Clone $dev_repo to .deploy/src"

if [ ! -d .deploy/src ]; then
    git clone $dev_repo .deploy/src
fi

cd .deploy/src
git reset --hard HEAD
git checkout $branch
git pull origin $branch
commit_hash=$(git rev-parse HEAD)
cd ../..

console_log "Clone $deploy_repo to .deploy/build"

if [ ! -d .deploy/build ]; then
    git clone $deploy_repo .deploy/build
fi

console_log "Build $deploy_repo in .deploy/build"





# GIT_SSH_COMMAND="ssh -i .host/ansible_rsa -F /dev/null"

console_log "Set $deploy_repo as remote for push"

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

console_log "Set syncing WordPress core files from src to build"
rsync -rp --delete .deploy/src/wp/ .deploy/build \
    --exclude=.git \
    --exclude=.gitmodules \
    --exclude=.gitignore \
    --exclude=.htaccess \
    --exclude=wp-content

console_log "Syncing wp-content dir from src to build"
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

console_log "Syncing configuration files from src to build"
# not wp-config.php. Host manages that
rsync -rp .deploy/src/ .deploy/build \
    --include=local-config-sample.php \
    --include=general-config.php \
    --include=build-process.php \
    --include=.wpengine.htaccess \
    --exclude=*

cd .deploy/build
mv .wpengine.htaccess .htaccess

console_log "Git add build to $deploy_repo"
git add -Av

if [ $forceyes == false ]; then
    console_log ""
    read -p "Ready to deploy $branch to $environment. Have you made a backup? [Y/n] " yn
    case $yn in
        [Yy]* ) ;;
        [Nn]* ) exit;;
        * ) exit;;
    esac
fi

console_log "Deploying $environment via Git Push to $deploy_repo"
git commit --allow-empty -m "Deployment $deploy_timestamp"
echo "Pushing to $environment"
git push $environment master

console_log "Cleanup"
cd ../..
rm -rf .deploy

echo "Deployment Done"
