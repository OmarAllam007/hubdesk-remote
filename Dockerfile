FROM ubuntu:xenial
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update -qq && apt-get install -y -qq openssl ca-certificates php7.0 php7.0-imap php7.0-xml php7.0-intl php7.0-mysql php7.0-gd php-tidy php7.0-mcrypt php7.0-mbstring \
    && apt-get clean all

WORKDIR /var/www/html

ENTRYPOINT [ "php", "artisan", "serve", "--host=0.0.0.0", "--port=3000" ]