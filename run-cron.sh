#!/bin/bash
# run-cron.sh
set -e

echo "Running the scheduler..."

# Execute the Laravel scheduler command
/usr/bin/php artisan schedule:run --verbose --no-interaction &

# Wait for 60 seconds before repeating, as the scheduler checks for due tasks every minute
sleep 60
