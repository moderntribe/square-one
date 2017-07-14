#!/bin/bash

grep -q '^'"$1"'=' .env && sed -i 's@^'"$1"'=.*@'"$1"'='"$2"'@' .env || echo "$1=$2" >> .env