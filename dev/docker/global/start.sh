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
	elif [[ $(which docker.exe) ]]; then
		cp "${SCRIPTDIR}/.env.windows.sample" "${SCRIPTDIR}/.env";
	else
		HOSTIP=$(ifconfig docker0 | grep "inet addr:" | cut -d: -f2 | awk '{ print $1}');
		echo "HOSTIP=\"${HOSTIP}\"" > "${SCRIPTDIR}/.env"
	fi;
fi;

# Newer versions of Docker change the Host IP address. Replace in place on start
if [[ "$OSTYPE" == "darwin"* ]]; then
    HOSTIP=`docker run --rm -it alpine nslookup docker.for.mac.localhost | grep "Address 1" | awk  '{ print $3 }' | tail -1`
    perl -pi -e "s/HOSTIP=.*?$/HOSTIP=${HOSTIP}/" "$SCRIPTDIR/.env"
fi;

if [[ "$OSTYPE" == "darwin"* ]]; then
	DC_COMMAND="docker-compose"
elif [[ $(which docker.exe) ]]; then
	DC_COMMAND="docker-compose.exe"
else
	DC_COMMAND="docker-compose"
fi;

${DC_COMMAND} --project-name=global up -d