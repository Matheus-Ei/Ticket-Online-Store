FROM alpine:latest

RUN apk add --no-cache dcron postgresql-client

WORKDIR /app

COPY ./cron/expire_tickets.sh .
RUN chmod +x expire_tickets.sh

COPY ./cron/crontab /etc/crontabs/root

RUN touch /var/log/cron.log

CMD crond -f && tail -f /var/log/cron.log
