####################################################
# Server Side Up - PHP 8.1 / FPM-Apache image
#####################################################
FROM serversideup/php:8.2-fpm-apache-v2.2.1

USER root

ARG APP_ENV=production

LABEL maintainer="Gerard Loriz"

ENV AUTORUN_ENABLED=false

#Node installation
RUN apt-get update \
    && curl -sL https://deb.nodesource.com/setup_21.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/* \
    && npm install -g n \
    && n stable

RUN apt-get update && apt-get -y install git \
    && git config --global --add safe.directory /var/www/html

RUN mkdir /root/.ssh
