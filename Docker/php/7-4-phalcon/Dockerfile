FROM php:7.4-apache

ENV PHALCON_VERSION=4.1.3
ENV XDEBUG_VERSION=2.9.2

RUN apt-get update
RUN apt-get upgrade -y

RUN apt-get install -y \
  apt-utils \
  mc \
  nano \
  telnet \
  iputils-ping \
  netcat \
  cowsay \
  git

RUN a2enmod rewrite
RUN docker-php-ext-configure opcache --enable-opcache
RUN docker-php-ext-install opcache gettext

# Curl
RUN apt-get install libcurl4-gnutls-dev

# OAuth
RUN apt-get install -y libpcre3-dev && pecl install oauth \
    && echo "extension=oauth.so" > /usr/local/etc/php/conf.d/docker-php-ext-oauth.ini

RUN apt-get install g++ \
        autoconf \
        make && \
    pecl install xdebug-${XDEBUG_VERSION} && \
    docker-php-ext-enable xdebug && \
    rm -r /tmp/pear/* && \
    echo "xdebug.remote_enable=1\n\
        xdebug.remote_autostart=1\n\
        xdebug.remote_connect_back=0\n\
        xdebug.remote_port=9001\n\
        xdebug.idekey=\"PHPSTORM\"\n\
        xdebug.remote_log=/var/www/html/xdebug.log\n\
        xdebug.log_level=0\n\
        xdebug.remote_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN pecl install igbinary-3.0.1

RUN docker-php-ext-enable igbinary
# PHP-PSR
RUN git clone https://github.com/jbboehr/php-psr.git /root/psr && \
    cd /root/psr && \
    phpize && \
    ./configure && \
    make && \
    make test && \
    make install && \
    echo "extension=psr.so" > /usr/local/etc/php/conf.d/29-psr.ini && \
    cd && rm -Rf /root/psr

# Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Get phalcon source
RUN git clone -b v${PHALCON_VERSION} --single-branch https://github.com/phalcon/cphalcon.git /root/buildphalcon

# Compile phalcon
RUN cd /root/buildphalcon/build/php7/64bits && \
    phpize && export CFLAGS="-O2 -s" && \
    ./configure && make -j 8 && make install && \
    echo "extension=phalcon.so" > /usr/local/etc/php/conf.d/30-phalcon.ini && \
    rm -Rf /root/buildphalcon

# Phalcon devtools
RUN cd /root && \
    git clone https://github.com/phalcon/phalcon-devtools.git && \
    cd phalcon-devtools && \
    ln -s ~/phalcon-devtools/phalcon /usr/bin/phalcon && \
    chmod ugo+x /usr/bin/phalcon

RUN pecl install redis && docker-php-ext-enable redis

# Clean
RUN rm -Rf /root/buildphalcon 

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

EXPOSE 9001
