### Запуск тестов
Перейти в директорию с проектом и выполнить:
```
docker run -it --rm --name date -v "$PWD":/usr/src/myapp -w /usr/src/myapp sergeyyanchuk/php:fpm-7.1.3 composer install
docker run -it --rm --name date -v "$PWD":/usr/src/myapp -w /usr/src/myapp sergeyyanchuk/php:fpm-7.1.3 ./vendor/phpunit/phpunit/phpunit --bootstrap  CustomDate.php tests/CustomDateTest
```
