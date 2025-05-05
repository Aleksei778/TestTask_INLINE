#!/bin/bash
# init-app.sh

# Ждем готовности базы данных
/usr/local/bin/wait-for-sql.sh db 3306

# После успешного подключения к базе данных, заполняем ее данными
echo "Запуск скрипта заполнения базы данных..."
php /var/www/html/fill_database.php

# Запускаем Apache в фоновом режиме
echo "Запуск веб-сервера..."
apache2-foreground