FROM phpdockerio/php72-fpm:latest

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install git subversion php7.2-memcached php7.2-mysql php7.2-redis php7.2-bz2 php7.2-gd php7.2-igbinary php7.2-imagick php7.2-imap php7.2-mbstring php7.2-soap php7.2-xdebug php7.2-bcmath php7.2-zip ssmtp mysql-client \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer config --global repo.packagist composer https://packagist.org
RUN composer global require hirak/prestissimo

# WP CLI
RUN echo "installing WP-CLI" \
    && curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp

WORKDIR "/application"
