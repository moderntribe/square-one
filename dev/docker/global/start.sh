#!/usr/bin/env bash

SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd );

cd "$SCRIPTDIR";

echo "Starting docker-compose project: global";

if [ ! -f '/etc/resolver/tribe' ] && [[ "$OSTYPE" == "darwin"* ]]; then
	echo "Creating resolver file at /etc/resolver/tribe";
	sudo mkdir -p /etc/resolver;
	sudo bash -c 'echo "nameserver 127.0.0.1" > /etc/resolver/tribe';
fi;

if [ ! -f "${SCRIPTDIR}/.env" ]; then
	if [[ "$OSTYPE" == "darwin"* ]]; then
		cp "${SCRIPTDIR}/.env.osx.sample" "${SCRIPTDIR}/.env";
	elif [[ "$OSTYPE" == "msys"* ]]; then
		cp "${SCRIPTDIR}/.env.windows.sample" "${SCRIPTDIR}/.env";
	else
		HOSTIP=$(ifconfig docker0 | grep "inet addr:" | cut -d: -f2 | awk '{ print $1}');
		echo "HOSTIP=\"${HOSTIP}\"" > "${SCRIPTDIR}/.env"
	fi;
fi;

if [ ! -f "${SCRIPTDIR}/certs/tribeCA.key" ]; then
	echo "Generating certificate authority"

	openssl req -x509 -new -nodes -sha256 -newkey rsa:4096 -days 3650 \
		-keyout "${SCRIPTDIR}/certs/tribeCA.key" \
		-out "${SCRIPTDIR}/certs/tribeCA.pem" \
		-subj "/C=US/ST=California/L=Santa Cruz/O=Modern Tribe/OU=Dev/CN=tri.be";

	if [[ $OSTYPE == darwin* ]]; then
		sudo security add-trusted-cert -d -r trustRoot -e hostnameMismatch -k /Library/Keychains/System.keychain "${SCRIPTDIR}/certs/tribeCA.pem";
	fi;
fi;

docker-compose --project-name=global up -d