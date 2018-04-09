#!/bin/bash

file='.env.example'
newfile='.env'
echo 'Вас приветствует мастер разворачивания проекта'
echo 'Введите минимально необходимые для разворачивания данные окружения'
echo -n 'DB_DATABASE='
read DB_DATABASE
echo -n 'DB_USERNAME='
read DB_USERNAME
echo -n 'DB_PASSWORD='
read -s DB_PASSWORD
clear

cat $file | sed "s/^DB_DATABASE=.*/DB_DATABASE=$DB_DATABASE/" | sed "s/^DB_USERNAME=.*/DB_USERNAME=$DB_USERNAME/" | sed "s/^DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" > $newfile
composer install

php artisan key:generate
php artisan migrate
php artisan passport:install
