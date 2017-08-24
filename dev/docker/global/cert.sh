#!/usr/bin/env bash

if [ $# -lt 1 ]; then
  echo 1>&2 "Usage: $0 domain.name"
  exit 2
fi

DOMAIN=$1
SCRIPTDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd );

cd "$SCRIPTDIR";

echo "Generating SSL certificate for $DOMAIN";

openssl req -new -nodes -sha256 -newkey rsa:4096 -days 3650 \
	-keyout "${SCRIPTDIR}/certs/${DOMAIN}.key" \
	-out "${SCRIPTDIR}/certs/${DOMAIN}.csr" \
	-subj "/C=US/ST=California/L=Santa Cruz/O=Modern Tribe/OU=Dev/CN=proxy.tribe";

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

