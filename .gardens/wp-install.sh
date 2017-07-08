#!/bin/bash
# you modify this to suit the needs of your project, whatever needs to happen to build your project

multisite_enabled="$1"
domain="$2"

cd /srv/site/wp
if ! $(wp --allow-root core is-installed); then
  if $multisite_enabled; then
    sed -i 's/%%PRIMARY_DOMAIN%%/'"${domain}"'/g' /srv/site/wp-config.php
    wp --allow-root core multisite-install --admin_user=$admin_user --admin_password=$admin_password --admin_email=$admin_email --title=$title --url=$url --skip-email
  else
    wp --allow-root core install --admin_user=$admin_user --admin_password=$admin_password --admin_email=$admin_email --title=$title --url=$url --skip-email
  fi
fi
wp --allow-root plugin activate s3-uploads