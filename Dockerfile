FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libmariadb-dev \
    libmariadb-dev-compat \
    default-mysql-client \
    libcurl4-openssl-dev \
    && docker-php-ext-install mysqli pdo_mysql

COPY wait-for-sql.sh /usr/local/bin/wait-for-sql.sh
COPY init-app.sh /usr/local/bin/init-app.sh

RUN chmod +x /usr/local/bin/wait-for-sql.sh
RUN chmod +x /usr/local/bin/init-app.sh

CMD ["/usr/local/bin/init-app.sh"]