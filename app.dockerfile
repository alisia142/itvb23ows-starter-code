FROM php:8.3-cli
COPY --from=composer /usr/bin/composer /usr/bin/composer

EXPOSE 8000

RUN apt-get update && apt-get clean

RUN docker-php-ext-install mysqli

WORKDIR /app
COPY . /app

CMD [ "php", "-S", "0.0.0.0:3000", "-t", "public"]