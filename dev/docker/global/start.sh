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
		HOSTIP=$(ip addr show docker0 | grep "inet\b" | awk '{print $2}' | cut -d/ -f1);
		echo "HOSTIP=\"${HOSTIP}\"" > "${SCRIPTDIR}/.env"
	fi;
fi;

# Newer versions of Docker change the Host IP address. Replace in place on start
if [[ "$OSTYPE" == "darwin"* ]]; then
    HOSTIP=`docker run --rm -it alpine nslookup docker.for.mac.localhost | grep "Address 1" | awk  '{ print $3 }' | tail -1`
    perl -pi -e "s/HOSTIP=.*?$/HOSTIP=${HOSTIP}/" "$SCRIPTDIR/.env"
fi;

if [[ "$OSTYPE" == "darwin"* ]]; then
	D_COMMAND="docker"
	DC_COMMAND="docker-compose"
elif [[ $(which docker.exe) ]]; then
	D_COMMAND="docker.exe"
	DC_COMMAND="docker-compose.exe"
else
	D_COMMAND="docker"
	DC_COMMAND="docker-compose"
fi;

# synchronize VM time with system time
${D_COMMAND} run --privileged --rm phpdockerio/php7-fpm date -s "$(date -u "+%Y-%m-%d %H:%M:%S")"

# start the containers
${DC_COMMAND} --project-name=global up -d
