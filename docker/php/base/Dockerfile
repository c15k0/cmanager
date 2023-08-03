# 746792098073.dkr.ecr.eu-west-1.amazonaws.com/saas_php_base
FROM php:8.2-fpm

LABEL 'author'='Fran Lopez <fran.lopez84@hotmail.es>'
LABEL 'version'='1.0.1'

RUN apt-get update && apt-get upgrade -y
RUN apt-get update && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    libmcrypt-dev libbz2-dev libgmp-dev libcurl4-gnutls-dev libicu-dev libxml2-dev libxslt-dev \
    libzip-dev libonig-dev git unzip vim
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg
RUN docker-php-ext-install bz2 curl gd gmp gettext iconv intl mbstring opcache mysqli pcntl pdo pdo_mysql soap sockets xml xsl zip

RUN pecl install mongodb \
        &&  echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongo.ini

RUN pecl install redis \
        &&  echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini

#RUN pecl install xdebug && echo "zend_extension=xdebug.so" > /usr/local/etc/php/conf.d/xdebug.ini

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/bin  --filename=composer
RUN php -r "unlink('composer-setup.php');"
