## Passwords

### Basic Authorization Password for Prometheus and AlertManager

make caddy-password password=YOUR_PASSWORD

### Google Account App Password
Password for receiving alerts by email

https://support.google.com/accounts/answer/185833?hl=en

## Alert rules

### Rules test

make check-alert-rules

### Severities:
- critical
- error
- warning
- info

### Examples:
https://awesome-prometheus-alerts.grep.to/rules.html

## Other

### Telegram bot Chat ID

Create a bot using BotFather. Copy the bot token and put it in the link https://api.telegram.org/botYOUR_BOT_TOKEN/getUpdates. If there are messages in the chat, it will be possible to get the "id" field from JSON.

### Metric for Pushgateway
echo "some_metric 3.14" | curl --data-binary @- http://user:password@localhost:9091/metrics/job/some_job