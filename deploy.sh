#!/bin/bash
# Habit Tracker PHP - Deployment Script
# Run this on the VPS to set up the project

set -e

echo "=== Habit Tracker PHP Deployment ==="

# Check PHP
if ! command -v php &> /dev/null; then
    echo "PHP not found. Installing..."
    sudo apt-get update
    sudo apt-get install -y php php-sqlite3 php-mbstring
fi

echo "PHP version: $(php -v | head -1)"

# Check SQLite extension
if php -m | grep -qi sqlite; then
    echo "SQLite extension: OK"
else
    echo "Installing PHP SQLite extension..."
    sudo apt-get install -y php-sqlite3
fi

# Create data directory
mkdir -p data
chmod 755 data

# Initialize database
php db.php

echo ""
echo "=== Deployment Complete ==="
echo ""
echo "Files should be placed in your web root, e.g.:"
echo "  /var/www/html/habit-tracker/"
echo ""
echo "Make sure nginx/Apache is configured to serve PHP files."
echo "The data/ directory must be writable by the web server."
echo ""
echo "Example nginx config:"
echo "  location /habit-tracker/ {"
echo "      try_files \$uri \$uri/ /habit-tracker/index.php;\n"
echo "  }"
echo ""
