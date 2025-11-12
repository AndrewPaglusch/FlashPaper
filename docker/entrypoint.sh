#!/usr/bin/env ash

# This function will handle graceful shutdown of the container
function StopContainer {
  echo Gracefully stopping FlashPaper container
  nginx -s stop

  exit 0
}

# # Define handlers for system traps:
# # - TERM or SIGTEM for a clean exit
trap StopContainer SIGTERM

# Change owner of flashpaper tmpfs directory, mounted from docker
mkdir /opt/flashpaper
chown nginx: /opt/flashpaper

# Start php-fpm and nginx
chown -R nginx: /var/www/html/data/
touch /var/www/html/data/index.php
php-fpm83
nginx -c /etc/nginx/nginx.conf -e stderr

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

# Prevents 'entrypoint.sh' script from terminating,
# so it can receive the SIGTERM(15) trap and run the 'StopContainer' function
tail -f /dev/null & wait ${!}