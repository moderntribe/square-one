#!/bin/bash

# This is a helper script for updating and migrating an existing Square One-based project to the necessary
# structure so it can be deployed to a garden
#
# This script simply stages some changes in your repo since projects diverge from Square One. We can't be
# sure of what the work will be on any given project to merge in necessary changes.  So we're basically
# using this script to get as far as we can, then letting the project owners finalize the merge and commit
#

git checkout -b feature/garden-integration
git remote add sq1 git@github.com:moderntribe/square-one.git
git fetch sq1
git checkout sq1/master -- .gardens
git checkout sq1/master -- package.json
git checkout sq1/master -- wp-config.php
git checkout sq1/master -- composer.json
git checkout sq1/master -- .gitmodules

git reset HEAD .gitmodules composer.json wp-config.php package.json

echo ""
echo "------------------------------------------------------------------------------------------------------"
echo "Below you'll find changes that have been staged for supporting this project being deployed to a garden"
echo ""
echo "Please review, make manual fixes to the staged changes as needed for your project"
echo ""
echo "Please note the package.json changes specifically. Really all we care about here for garden support is"
echo "    that you make your 'dependencies' list as small as you possibly can in favor of moving things out"
echo "    to 'devDependencies' where possible. It's entirely possible you'll just end up reverting"
echo "    package.json to your current project's version"
echo "------------------------------------------------------------------------------------------------------"
echo ""
git diff