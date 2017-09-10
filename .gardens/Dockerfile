FROM moderntribe/wordpress:nginx-php7-fpm

ARG multisite_enabled

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
WORKDIR /srv/site
RUN /srv/site/.gardens/wp-install.sh $multisite_enabled

RUN sed -i 's/^root=.*$/root=postmaster@'"$smtp_domain"'/g' /etc/ssmtp/ssmtp.conf
RUN sed -i 's/^hostname=.*$/hostname='"$smtp_domain"'/g' /etc/ssmtp/ssmtp.conf
RUN sed -i 's/^mailhub=.*$/mailhub='"$smtp_host"':'"$smtp_port"'/g' /etc/ssmtp/ssmtp.conf
RUN sed -i 's/^AuthUser=.*$/AuthUser='"$smtp_user"'/g' /etc/ssmtp/ssmtp.conf
RUN sed -i 's/^AuthPass=.*$/AuthPass='"$smtp_password"'/g' /etc/ssmtp/ssmtp.conf

RUN mkdir -p /srv/site/wp-content/uploads
RUN chown -R nginx:nginx /srv/site/wp-content/uploads
RUN chmod -R 0775 /srv/site/wp-content/uploads

RUN chown -R nginx:nginx /srv/site
