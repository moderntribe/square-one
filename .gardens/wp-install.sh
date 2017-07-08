#!/bin/bash

is_multisite="$1"
multisite_use_subdomains="$2"

multisite_subdomains_flag=""
if $multisite_use_subdomains; then
  multisite_subdomains_flag="--subdomains"
fi

cd /srv/site/wp
if ! $(wp --allow-root core is-installed); then
  if $is_multisite; then
    wp --allow-root core multisite-install --admin_user=$admin_user --admin_password=$admin_password --admin_email=$admin_email --title=$title --url=$url --skip-email
  else
    wp --allow-root core install --admin_user=$admin_user --admin_password=$admin_password --admin_email=$admin_email --title=$title --url=$url --skip-email "$multisite_subdomains_flag"
  fi
fi
wp --allow-root plugin activate s3-uploads