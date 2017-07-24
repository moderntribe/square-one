FROM moderntribe/wordpress:nginx-php7-fpm

ARG admin_user
ARG admin_password
ARG admin_email
ARG title
ARG url

COPY ../ /srv/site
RUN chown -R nginx:nginx /srv/site
RUN cd /srv/site/wp && \
    if ! $(wp --allow-root core is-installed); then wp --allow-root core install --admin_user=$admin_user --admin_password=$admin_password --admin_email=$admin_email --title=$title --url=$url --skip-email; fi && \
    wp --allow-root plugin activate S3-Uploads

RUN mkdir -p /srv/site/wp-content/uploads
RUN chown -R nginx:nginx /srv/site/wp-content/uploads
RUN chmod -R 0775 /srv/site/wp-content/uploads