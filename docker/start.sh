#!/bin/bash

# Start PHP-FPM in background
php-fpm --daemonize || {
    echo "Error: Failed to start PHP-FPM"
    exit 1
}

# Wait for PHP-FPM to be ready
echo "Waiting for PHP-FPM to start..."
for i in {1..30}; do
    if pgrep -x "php-fpm" > /dev/null 2>&1; then
        echo "PHP-FPM is running!"
        break
    fi
    if [ $i -eq 30 ]; then
        echo "Warning: PHP-FPM did not start within 30 seconds"
        exit 1
    fi
    sleep 1
done

# Start nginx in foreground
echo "Starting nginx..."
exec nginx -g "daemon off;"

