#!/bin/bash

multisite_enabled="$1"

cd /srv/site/wp
if ! $(wp --allow-root core is-installed); then
  if $multisite_enabled; then
    wp --allow-root core multisite-install --admin_user=$admin_user --admin_password=$admin_password --admin_email=$admin_email --title=$title --url=$url --skip-email
  else
    wp --allow-root core install --admin_user=$admin_user --admin_password=$admin_password --admin_email=$admin_email --title=$title --url=$url --skip-email
  fi
fi
wp --allow-root plugin activate s3-uploads