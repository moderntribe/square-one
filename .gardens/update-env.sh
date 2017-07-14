#!/bin/bash

grep -q '^'"$1"'=' .env && sed -i 's@^'"$1"'=.*@'"$1"'='"$2"'@' .env || printf "\n$1=$2" >> .env