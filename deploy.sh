#!/bin/sh

# Bật chế độ hiển thị lỗi nếu có lỗi xảy ra
set -e

# Xóa file nén cũ nếu tồn tại
[ -f app.zip ] && rm app.zip

# Cài đặt composer mà không có gói dev để tối ưu hóa
composer install --optimize-autoloader --no-dev

# Dọn dẹp cache Laravel
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Tạo file `.env.production` nếu cần (chỉ chạy khi triển khai)
cp .env .env.production

# Đặt quyền cho storage và bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Đóng gói dự án thành file zip, loại trừ các thư mục không cần thiết
zip -r app.zip . -x \
    "client/*" \
    "dump/*" \
    "img/*" \
    "node_modules/*" \
    "storage/logs/*" \
    "vendor/*" \
    ".git/*" \
    "composer.lock" \
    "deploy.sh"

echo "✅ Deploy package created successfully!"
