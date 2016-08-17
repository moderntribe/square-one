FROM alpine:3.2
MAINTAINER Modern Tribe

RUN apk --update add mysql && rm -f /var/cache/apk/*

ADD run.sh /scripts/run.sh
RUN mkdir /scripts/pre-exec.d && mkdir /scripts/pre-init.d && chmod -R 755 /scripts

RUN sed -Ei 's/^(bind-address|log)/#&/' /etc/mysql/my.cnf \
	&& echo 'skip-name-resolve' | awk '{ print } $1 == "[mysqld]" && c == 0 { c = 1; system("cat") }' /etc/mysql/my.cnf > /tmp/my.cnf \
		&& mv /tmp/my.cnf /etc/mysql/my.cnf && cp /etc/mysql/my.cnf /etc/my.cnf

EXPOSE 3306

VOLUME ["/var/lib/mysql"]

ENTRYPOINT ["/scripts/run.sh"]

