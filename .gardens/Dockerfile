FROM moderntribe/wordpress:nginx-php7-fpm

ARG admin_user
ARG admin_password
ARG admin_email
ARG title
ARG url

ARG smtp_domain
ARG smtp_host
ARG smtp_port
ARG smtp_user
ARG smtp_password

COPY . /srv/site
RUN chown -R nginx:nginx /srv/site
RUN cd /srv/site/wp && \
    if ! $(wp --allow-root core is-installed); then wp --allow-root core install --admin_user=$admin_user --admin_password=$admin_password --admin_email=$admin_email --title=$title --url=$url --skip-email; fi && \
    wp --allow-root plugin activate s3-uploads

RUN sed -i 's/^root=.*$/root=postmaster@'"$smtp_domain"'/g' /etc/ssmtp/ssmtp.conf
RUN sed -i 's/^hostname=.*$/hostname='"$smtp_domain"'/g' /etc/ssmtp/ssmtp.conf
RUN sed -i 's/^mailhub=.*$/mailhub='"$smtp_host"':'"$smtp_port"'/g' /etc/ssmtp/ssmtp.conf
RUN sed -i 's/^AuthUser=.*$/AuthUser='"$smtp_user"'/g' /etc/ssmtp/ssmtp.conf
RUN sed -i 's/^AuthPass=.*$/AuthPass='"$smtp_password"'/g' /etc/ssmtp/ssmtp.conf

RUN mkdir -p /srv/site/wp-content/uploads
RUN chown -R nginx:nginx /srv/site/wp-content/uploads
RUN chmod -R 0775 /srv/site/wp-content/uploads
