# syntax=docker/dockerfile:1

FROM php:5.6
COPY . /src
WORKDIR /src
RUN docker-php-ext-install mysqli
CMD ["php", "-S", "0.0.0.0:3000"]
EXPOSE 3000