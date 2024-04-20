FROM nginx:1.25.5

RUN rm /etc/nginx/conf.d/default.conf

COPY ./nginx.conf /etc/nginx/nginx.conf
COPY ./conf.d /etc/nginx/conf.d

WORKDIR /app

EXPOSE 8082 8443
