#!/usr/bin/env ash

# Start php-fpm and nginx
chown -R nginx: /var/www/html/data/
touch /var/www/html/data/index.php
php-fpm83
nginx -c /etc/nginx/nginx.conf

# Ready to serve?
for i in 1 2 3; do
  echo "Checking to see if FlashPaper is ready. ($i of 3)"
  curl -sm3 localhost | grep -q "AndrewPaglusch/FlashPaper"
  if [[ $? -eq 0 ]]; then
    echo "FlashPaper is ready."
    break
  fi
  sleep 2
  echo "FlashPaper is not ready."
done

echo "Access logging is disabled for production use. Tailing error logs..."
tail -f /var/log/nginx/error.log /var/log/php83/error.log