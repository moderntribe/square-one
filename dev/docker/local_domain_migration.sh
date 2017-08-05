#!/bin/bash

SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

cd "$SCRIPTDIR";

SOURCE_DOMAIN="production.com"
TARGET_DOMAIN="square1.tribe"
DB_PREFIX="tribe_"
DB_NAME="tribe_square1"

docker run --rm -i \
 --link="global_mysql_1:mysql" \
 --entrypoint="/usr/local/mysql/bin/mysql" \
 moderntribe/mysql:5.5 \
 -uroot -ppassword -hmysql $DB_NAME \
 -e "UPDATE ${DB_PREFIX}blogs SET domain='$TARGET_DOMAIN' WHERE blog_id=1"

/bin/bash $SCRIPTDIR/wp.sh \
 search-replace "$SOURCE_DOMAIN" "$TARGET_DOMAIN" --all-tables-with-prefix --verbose
