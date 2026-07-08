#!/bin/bash
set -e

# Render กำหนด PORT มาให้ผ่าน environment variable — ต้องตั้ง Apache ให้ฟังที่ port นั้น
PORT="${PORT:-10000}"
sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf
sed -i "s/80/${PORT}/g" /etc/apache2/sites-available/000-default.conf

# cache config ตาม environment variables ปัจจุบันที่ Render ตั้งไว้
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# รัน migration อัตโนมัติทุกครั้งที่ container เริ่มทำงาน (แทน Pre-Deploy Command ที่ต้องเสียเงิน)
php artisan migrate --force

exec apache2-foreground