#!/usr/bin/env ash

# Env Variables

[ -z "$SITE_TITLE" ] && export SITE_TITLE="FlashPaper :: Self-Destructing Message"
[ -z "$RETURN_FULL_URL" ] && export RETURN_FULL_URL="true"
[ -z "$MAX_SECRET_LENGTH" ] && export MAX_SECRET_LENGTH="3000"
[ -z "$MESSAGES_ERROR_SECRET_TOO_LONG" ] && export MESSAGES_ERROR_SECRET_TOO_LONG="Input length too long"
[ -z "$MESSAGES_SUBMIT_SECRET_HEADER" ] && export MESSAGES_SUBMIT_SECRET_HEADER="Create A Self-Destructing Message"
[ -z "$MESSAGES_SUBMIT_SECRET_SUBHEADER" ] && export MESSAGES_SUBMIT_SECRET_SUBHEADER=""
[ -z "$MESSAGES_SUBMIT_SECRET_BUTTON" ] && export MESSAGES_SUBMIT_SECRET_BUTTON="Encrypt Message"
[ -z "$MESSAGES_VIEW_CODE_HEADER" ] && export MESSAGES_VIEW_CODE_HEADER="Self-Destructing URL"
[ -z "$MESSAGES_VIEW_CODE_SUBHEADER" ] && export MESSAGES_VIEW_CODE_SUBHEADER="Share this URL via email, chat, or another messaging service. It will self-destruct after being viewed once."
[ -z "$MESSAGES_CONFIRM_VIEW_SECRET_HEADER" ] && export MESSAGES_CONFIRM_VIEW_SECRET_HEADER="View this secret?"
[ -z "$MESSAGES_CONFIRM_VIEW_SECRET_BUTTON" ] && export MESSAGES_CONFIRM_VIEW_SECRET_BUTTON="View Secret"
[ -z "$MESSAGES_VIEW_SECRET_HEADER" ] && export MESSAGES_VIEW_SECRET_HEADER="Self-Destructing Message"
[ -z "$MESSAGES_VIEW_SECRET_SUBHEADER" ] && export MESSAGES_VIEW_SECRET_SUBHEADER="This message has been destroyed"
[ -z "$PRUNE_ENABLED" ] && export PRUNE_ENABLED="true"
[ -z "$PRUNE_MIN_DAYS" ] && export PRUNE_MIN_DAYS="365"
[ -z "$PRUNE_MAX_DAYS" ] && export PRUNE_MAX_DAYS="730"


# Create settings.php from environment and clean up the docker folder
DOLLAR='$' envsubst < /var/www/html/docker/settings.php.TEMPLATE > /var/www/html/settings.php
rm -rf /var/www/html/docker

# Start php-fpm and nginx
chown -R nginx: /var/www/html/data/
touch /var/www/html/data/index.php
php-fpm7
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
tail -f /var/log/nginx/error.log /var/log/php7/error.log
