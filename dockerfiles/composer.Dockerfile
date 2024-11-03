FROM composer:latest

WORKDIR /var/www/laravel

ENTRYPOINT ["composer", "--ignore-platrom-reqs"]
