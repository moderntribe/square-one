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

docker exec tribe-mysql \
 mysql -uroot -ppassword $DB_NAME \
 -Ne "UPDATE ${DB_PREFIX}options SET option_value=REPLACE( 'option_value', '$SOURCE_DOMAIN', '$TARGET_DOMAIN' ) WHERE option_name='siteurl'"

/bin/bash $SCRIPTDIR/wp.sh \
 search-replace "$SOURCE_DOMAIN" "$TARGET_DOMAIN" --all-tables-with-prefix --verbose
