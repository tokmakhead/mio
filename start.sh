#!/bin/bash

# Create installed flag
touch storage/installed

# Create storage directories if they don't exist
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs

# Set permissions
chmod -R 775 storage bootstrap/cache

# Clear config cache to pick up new APP_URL
php artisan config:clear

# Start the application
php artisan serve --host=0.0.0.0 --port=$PORT
