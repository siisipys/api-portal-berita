#!/bin/bash

echo "Running migrations..."
php artisan migrate:fresh --force --seed

echo "Generating Passport keys..."
php artisan passport:keys --force

echo "Installing Passport clients..."
php artisan passport:client --personal --no-interaction

echo "Starting server..."
php artisan serve --host=0.0.0.0 --port=$PORT
