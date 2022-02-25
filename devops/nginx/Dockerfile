########################
### Stage - "base"
########################
FROM nginx:1.19.2 as base

ENV TZ=Europe/Oslo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN rm /etc/nginx/conf.d/default.conf
COPY nginx.conf /etc/nginx/nginx.conf



########################
### Stage - "local"
########################
FROM base as local
COPY local/nginx-back.conf /etc/nginx/conf.d/back.conf
COPY local/nginx-front.conf /etc/nginx/conf.d/front.conf



########################
### Stage - "dev"
########################
FROM base as dev
COPY dev/nginx-back.conf /etc/nginx/conf.d/back.conf
COPY dev/nginx-front.conf /etc/nginx/conf.d/front.conf



########################
### Stage - "stage"
########################
FROM base as stage
COPY stage/nginx-back.conf /etc/nginx/conf.d/back.conf
COPY stage/nginx-front.conf /etc/nginx/conf.d/front.conf



########################
### Stage - "production"
########################
FROM base as production
#COPY production/nginx-back.conf /etc/nginx/conf.d/back.conf
COPY prod-test/nginx-back.conf /etc/nginx/conf.d/back.conf
#COPY production/nginx-front.conf /etc/nginx/conf.d/front.conf
