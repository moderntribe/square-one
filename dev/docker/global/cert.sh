#!/usr/bin/env bash

if [ $# -lt 1 ]; then
  echo 1>&2 "Usage: $0 domain.name"
  exit 2
fi

DOMAIN=$1
SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd );

cd "$SCRIPTDIR";

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

echo "Generating SSL certificate for $DOMAIN";

openssl req -new -nodes -sha256 -newkey rsa:4096 -days 3650 \
	-keyout "${SCRIPTDIR}/certs/${DOMAIN}.key" \
	-out "${SCRIPTDIR}/certs/${DOMAIN}.csr" \
	-subj "/C=US/ST=California/L=Santa Cruz/O=Modern Tribe/OU=Dev/CN=${DOMAIN}";

cat > "${SCRIPTDIR}/certs/${DOMAIN}.ext" <<-EOF
	authorityKeyIdentifier=keyid,issuer
	basicConstraints=CA:FALSE
	keyUsage = digitalSignature, nonRepudiation, keyEncipherment, dataEncipherment
	subjectAltName = @alt_names
	[alt_names]
	DNS.1 = ${DOMAIN}
	DNS.2 = *.${DOMAIN}
EOF

openssl x509 -req -days 3650 -sha256 \
	-in "${SCRIPTDIR}/certs/${DOMAIN}.csr" \
	-CA "${SCRIPTDIR}/certs/tribeCA.pem" \
	-CAkey "${SCRIPTDIR}/certs/tribeCA.key" \
	-CAcreateserial \
	-extfile "${SCRIPTDIR}/certs/${DOMAIN}.ext" \
	-out "${SCRIPTDIR}/certs/${DOMAIN}.crt";

rm "${SCRIPTDIR}/certs/${DOMAIN}.ext";

