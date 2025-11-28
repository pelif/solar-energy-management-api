FROM nginx:latest

ADD ./nginx/default.conf /etc/nginx/conf.d/default.conf

COPY public /var/www/public