#!/bin/bash
set -e

# Get PORT from Railway (default to 80)
PORT=${PORT:-80}
echo "Starting services on port $PORT"

# Update nginx config to use PORT
sed -i "s/listen 80;/listen $PORT;/" /etc/nginx/sites-available/default

# Start PHP-FPM in background with error logging
echo "Starting PHP-FPM..."
php-fpm --daemonize || {
    echo "Error: Failed to start PHP-FPM"
    php-fpm --test 2>&1 || true
    exit 1
}

# Wait for PHP-FPM to be ready
echo "Waiting for PHP-FPM to start..."
for i in {1..30}; do
    if pgrep -f "php-fpm" > /dev/null 2>&1; then
        echo "PHP-FPM is running! (attempt $i)"
        break
    fi
    if [ $i -eq 30 ]; then
        echo "Error: PHP-FPM did not start within 30 seconds"
        ps aux | grep php || true
        exit 1
    fi
    sleep 1
done

# Test PHP-FPM is responding
if ! pgrep -f "php-fpm" > /dev/null 2>&1; then
    echo "Error: PHP-FPM process not found"
    exit 1
fi

# Test nginx config
echo "Testing nginx configuration..."
nginx -t 2>&1 || {
    echo "Error: Nginx configuration test failed"
    exit 1
}

# Start nginx in foreground
echo "Starting nginx on port $PORT..."
exec nginx -g "daemon off;"

