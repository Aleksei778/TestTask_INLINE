#!/bin/bash
# wait-for-sql.sh

set -e

host="$1"
port="$2"
shift 2

echo "Ожидание доступности MySQL на $host:$port..."

# Ожидаем запуск MySQL
until mysql -h "$host" -P "$port" -u blog_user -p"blog_password" -e "SELECT 1" &> /dev/null; do
  >&2 echo "MySQL еще не готов - ожидание..."
  sleep 2
done

>&2 echo "MySQL готов и доступен!"