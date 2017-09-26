#!/usr/bin/env bash

set -ex

if [[ "$TRAVIS_PHP_VERSION" != 5.3 ]]; then
	sudo apt-get -qq update
	sudo apt-get install -y ghostscript
fi

