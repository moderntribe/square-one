#!/bin/bash

SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

cd "$SCRIPTDIR";

PROJECT_ID=$(cat ./.projectID)

TARGET_DOMAIN="${PROJECT_ID}.tribe"
DB_PREFIX="tribe_"
DB_NAME="tribe_${PROJECT_ID}"

SOURCE_DOMAIN=$(docker exec tribe-mysql \
 mysql -uroot -ppassword $DB_NAME \
 -Ne "SELECT option_value FROM ${DB_PREFIX}options WHERE option_name='siteurl'" \
 | cut -d'/' -f3)

/bin/bash $SCRIPTDIR/wp.sh \
 search-replace "$SOURCE_DOMAIN" "$TARGET_DOMAIN" --all-tables-with-prefix --verbose
