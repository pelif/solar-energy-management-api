FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    zip \
    unzip 

RUN docker-php-ext-install zip  

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-enable pdo_mysql

# Install pcov for code coverage
RUN pecl install pcov && docker-php-ext-enable pcov

WORKDIR /var/www

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === 'c8b085408188070d5f52bcfe4ecfbee5f727afa458b2573b8eaaf77b3419b0bf2768dc67c86944da1544f06fa544fd47') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

EXPOSE 9000


RUN mkdir /var/www/storage
RUN mkdir storage/framework
RUN mkdir storage/framework/cache
RUN mkdir storage/framework/sessions
RUN mkdir storage/framework/views

RUN chown -R www-data:www-data /var/www
RUN chmod -R 777 /var/www/storage







