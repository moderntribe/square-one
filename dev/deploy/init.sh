#!/bin/bash

echo "Creating configuration files"
cp .host/config/staging.cfg.sample .host/config/staging.cfg
cp .host/config/production.cfg.sample .host/config/production.cfg
cp .host/config/common.cfg.sample .host/config/common.cfg

PASSWORD=$(LC_ALL=C tr -dc 'A-Za-z0-9!"#$%&'\''()*+,-./:;<=>?@[\]^_`{|}~' </dev/urandom | head -c 13)
echo $PASSWORD > .wpengine/ansible_rsa_password

ssh-keygen -t rsa -b 4096 -N "$PASSWORD" -f .host/ansible_rsa -C "servers@tri.be"

echo "Encrypting files"
source encrypt.sh

echo "Setup complete. Edit the .cfg files in the .host/config directory and run encrypt.sh."
