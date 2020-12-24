ARG SERVICE_SYMFONY_CONTAINER

FROM  $SERVICE_SYMFONY_CONTAINER as source

FROM wrkngu0/caddy:0.11.1
MAINTAINER Author="Aleksej Burdash <working.unit.0@protonmail.com>"

ARG APP_ENV=prod
ENV APP_ENV=${APP_ENV}

COPY ./docker/${APP_ENV}.Caddyfile /etc/caddy/Caddyfile

RUN mkdir -p /var/www/application/public/

COPY --from=source /var/www/application/public/ /var/www/application/public/