FROM phpdockerio/php71-fpm:latest

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install git subversion php7.1-memcached php7.1-mysql php7.1-redis php7.1-bz2 php7.1-gd php7.1-igbinary php7.1-imagick php7.1-imap php7.1-mbstring php7.1-soap php7.1-xdebug php7.1-bcmath ssmtp mysql-client \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Tideways
RUN echo 'deb http://s3-eu-west-1.amazonaws.com/qafoo-profiler/packages debian main' > /etc/apt/sources.list.d/tideways.list && \
    curl -sS 'https://s3-eu-west-1.amazonaws.com/qafoo-profiler/packages/EEB5E8F4.gpg' | apt-key add - && \
    apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get -yq install tideways-php && \
    apt-get autoremove --assume-yes && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

#COPY /usr/lib/tideways/tideways.ini /usr/local/etc/php/conf.d/40-tideways.ini

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# WP CLI
RUN echo "installing WP-CLI" \
    && curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp

WORKDIR "/application"