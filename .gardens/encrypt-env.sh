#!/bin/bash

if file .env | grep "data$" &> /dev/null; then
  printf "\033[31mLooks like your .env file is already encrypted, decrypt it first before encrypting\033[0m\n"
  exit 1
fi

if ! openssl version | grep " 1\." &> /dev/null; then
  echo "Make sure you have openssl major version 1 installed and in your path, here's the version we detected:"
  openssl version
  exit 1
fi

read -p "Please enter a key to encrypt the .env file: " key
if [ ! -z $key ]; then
  openssl enc -aes-256-cbc -md sha256 -in .env -out .env.enc -k $key
  mv .env.enc .env
  printf "\033[32m\nEncrypted .env file with your new key: $key\033[0m\n"
  printf "\033[33m\nIMPORTANT: Please save these key somewhere safe\n"
  printf "You'll also need to save it to any garden that needs to deploy this project, the CI server for the garden, e.g. https://ci.moderntribe.qa, should have a job to upload the key.\n"
  printf "\033[0m\n"
else
  printf "\033[31mthe key can't be blank\033[0m\n"
  exit 1
fi
