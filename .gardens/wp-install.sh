#!/bin/bash

multisite_enabled="$1"
multisite_use_subdomains="$2"
domain="$3"

multisite_subdomains_flag=""
if $multisite_use_subdomains; then
  multisite_subdomains_flag="--subdomains"
fi

cd /srv/site/wp
if ! $(wp --allow-root core is-installed); then
  if $multisite_enabled; then
    sed -i 's/%%PRIMARY_DOMAIN%%/'"${domain}"'/g' /srv/site/wp-config.php
    wp --allow-root core multisite-install --admin_user=$admin_user --admin_password=$admin_password --admin_email=$admin_email --title=$title --url=$url --skip-email
  else
    wp --allow-root core install --admin_user=$admin_user --admin_password=$admin_password --admin_email=$admin_email --title=$title --url=$url --skip-email "$multisite_subdomains_flag"
  fi
fi
wp --allow-root plugin activate s3-uploads