FROM composer AS composer

# copying the source directory and install the dependencies with composer
COPY www/ /app

# run composer install to install the dependencies
RUN composer install \
  --optimize-autoloader \
  --no-interaction \
  --no-progress

# continue stage build with the desired image and copy the source including the
# dependencies downloaded by composer
FROM trafex/alpine-nginx-php7
USER root
RUN apk --no-cache add php7-sqlite3 php7-exif
USER nobody
COPY --chown=nobody --from=composer /app /var/www/html
