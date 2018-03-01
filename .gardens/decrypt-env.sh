#!/bin/bash

key="$1"
automated=false
if [ -z $key ]; then
  read -p "Please enter the key to decrypt the .env file: " key
else
  automated=true
fi
if [ ! -z $key ]; then
  if openssl enc -aes-256-cbc -md sha256 -d -in .env -out .env.dec -k $key; then
    mv .env.dec .env
    if ! $automated; then
      printf "\033[32m.env file decrypted\n"
      printf "\033[33mPlease remember to re-encrypt it when you're done editing or viewing it\033[0m\n"
    fi
  else
    printf "\033[31mdecryption failed\033[0m\n"
    exit 1
  fi
else
  printf "\033[31mplease enter a key\033[0m\n"
  exit 1
fi
