#!/bin/bash
set -e

# Create the log file with appropriate permissions
# touch /var/www/html/supervisord.log
# chown root:root /var/www/html/supervisord.log
# chmod -R 777 /var/www/html/supervisord.log

# Start Supervisor
exec supervisord -c /etc/supervisor/supervisord.conf

# Start the scheduler
while [ true ]
do
  php artisan schedule:work
  sleep 60
done