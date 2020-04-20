#!/bin/bash

function console_log(){
  echo ""
  echo "#########################################################################"
  echo "### $1"
  echo ""
}

if [ ! -f "./deploy_hosted_git.sh" ]; then
    echo "deploy_hosted_git.sh is not being run from the /dev/deploy/ directory. Aborting..."
    exit 1
fi

SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
cd "$SCRIPTDIR";

BUILD_DIR='.deploy/build'
DEPLOY_DIR='.deploy/deploy'
CONFIG_DIR='.host/config'

environment=$1; shift
forceyes=false
branch=server/$environment

if [ ! -f "$CONFIG_DIR/$environment.cfg" ]; then
    echo "Unknown enviroment defined. Environment: $environment. Aborting..."
    exit 1
fi

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

if [ $forceyes == false ]; then
    console_log ""
    read -p "This will Deploy branch $branch to the $environment environment. Please confirm you want to deploy [Y/n] " yn
    case $yn in
        [Yy]* ) ;;
        [Nn]* ) exit;;
        * ) exit;;
    esac
fi

### Load Environment Info ################################################
source "$CONFIG_DIR/common.cfg"
source "$CONFIG_DIR/$environment.cfg"
deploy_timestamp=`date +%Y%m%d%H%M%S`

console_log "Preparing to deploy $branch to $environment"


### Checkout Project SCM ################################################
console_log "Clone $dev_repo to $BUILD_DIR"

if [ ! -d $BUILD_DIR ]; then
  git clone $dev_repo $BUILD_DIR
fi

cd $BUILD_DIR
git reset --hard HEAD
git checkout $branch
git pull origin $branch
commit_hash=$(git rev-parse HEAD)

### Build Project ################################################
console_log "Build $deploy_repo in $BUILD_DIR"
# Note, this should only run on local env deploys. Jenkins should pre-build the project.

if [ ! -d $BUILD_DIR/vendor ]; then
  cp ../../../../.env ./.env
  composer install --ignore-platform-reqs --no-dev
fi

if [ ! -d $BUILD_DIR/node_modules ]; then
  cp local-config-sample.json local-config.json
  nvm use
  yarn install
  gulp server_dist
fi

cd "$SCRIPTDIR"

### Checkout Deploy Repo ################################################
console_log "Clone $deploy_repo to $DEPLOY_DIR"

GIT_SSH_COMMAND="ssh -i .host/ansible_rsa -F /dev/null"

if [ ! -d $DEPLOY_DIR ]; then
    git clone $deploy_repo $DEPLOY_DIR
fi

echo "Set $deploy_repo as remote for push"

cd $DEPLOY_DIR

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
cd "$SCRIPTDIR"

console_log "Set syncing WordPress core files from src to build"
rsync -rpv --delete $BUILD_DIR/wp/ $DEPLOY_DIR \
    --exclude=.git \
    --exclude=.gitmodules \
    --exclude=.gitignore \
    --exclude=.htaccess \
    --exclude=wp-content

console_log "Syncing wp-content dir from src to build"
rsync -rpv --delete .deploy/build/wp-content .deploy/deploy \
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
    --exclude=wp-content/object-cache.php \
    --exclude=wp-content/plugins/core/assets/templates/cli

console_log "Syncing configuration files from src to build"
# not wp-config.php. Host manages that
rsync -rpv .deploy/build/ .deploy/deploy \
    --include=build-process.php \
    --include=vendor/*** \
    --exclude=*

cd $DEPLOY_DIR

console_log "Git add build to $deploy_repo"

git add -Av

### Deploy via Git Push Deploy SCM ################################################
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
cd "$SCRIPTDIR"
# rm -rf .deploy

console_log "
DONE.\n
Deploy $environment via Git Push to $deploy_repo\n
Branch: $branch\n
Commit: $commit_hash\n"
