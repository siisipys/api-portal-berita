#!/bin/bash

echo "Running migrations..."
php artisan migrate:fresh --force --seed

echo "Installing Passport keys..."
php artisan passport:install --force

echo "Starting server..."
php artisan serve --host=0.0.0.0 --port=$PORT
