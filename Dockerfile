FROM php:8.3-apache

# ติดตั้ง system dependencies ที่จำเป็น
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# ติดตั้ง PHP extensions ที่ Laravel ต้องใช้
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring zip gd bcmath

# เปิดใช้งาน Apache mod_rewrite (จำเป็นสำหรับ Laravel pretty URLs)
RUN a2enmod rewrite

# ตั้งให้ Apache มองไปที่โฟลเดอร์ public/ ของ Laravel เป็น webroot
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# ติดตั้ง Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# copy โค้ดทั้งหมดเข้า container
COPY . .

# ติดตั้ง PHP dependencies แบบ production (ไม่เอา dev packages)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# สร้าง symlink สำหรับไฟล์ที่อัปโหลด (ใช้งานได้จนกว่าจะ deploy รอบถัดไป)
RUN php artisan storage:link || true

# ตั้งสิทธิ์ให้ Apache เขียนไฟล์ใน storage/bootstrap-cache ได้
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# สคริปต์ entrypoint สำหรับตั้งค่า PORT ที่ Render กำหนดให้แบบไดนามิก
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]
