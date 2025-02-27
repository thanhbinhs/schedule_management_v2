# Quản lý Đào tạo và Xây dựng Thời Khóa Biểu - Trường Đại học Phenikaa

## Giới thiệu
Đây là hệ thống Quản lý Đào tạo và Xây dựng Thời Khóa Biểu được phát triển bằng **Laravel 10**.  
Dự án hỗ trợ quản lý khóa học, giảng viên, sinh viên và tự động tạo thời khóa biểu cho trường đại học.

- **Sinh viên thực hiện:** Lê Thanh Bình - 22010495
- **Repository GitHub:** [schedule_management_v2](https://github.com/username/schedule_management_v2)
- **Phiên bản Public:** [schedulemanagement.rf.gd](http://schedulemanagement.rf.gd)

## Tài khoản dùng thử
Bạn có thể đăng nhập với tài khoản thử nghiệm sau:
- **User:** `pdt`
- **Password:** `123456a@`

## Công nghệ sử dụng
- **Backend:** Laravel 10, MySQL
- **Frontend:** Blade, Bootstrap
- **Authentication:** Laravel Breeze / Laravel Sanctum (tuỳ thuộc vào cài đặt)

## Cài đặt & Chạy Dự Án

### 1. Clone Repository
```bash
git clone https://github.com/username/schedule_management_v2.git
cd schedule_management_v2
```

### 2. Cấu hình môi trường
Sao chép file .env.example thành .env
```bash
cp .env.example .env
```

Sau đó, cập nhật thông tin kết nối database trong file .env:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=schedule
DB_USERNAME=root
DB_PASSWORD=
```
### 3. Cài đặt các dependency
```base
composer install
```

### 4. Tạo key ứng dụng
```base
php artisan key:generate
```

### 5. Chạy migration và seed database
```base
php artisan migrate --seed
```

### 6. Chạy server Laravel
```base
php artisan serve
```

Ứng dụng sẽ chạy trên [http://127.0.0.1:8000](http://127.0.0.1:8000)

## 📬 Liên hệ

Nếu có bất kỳ thắc mắc hoặc cần hỗ trợ, vui lòng liên hệ qua repository GitHub hoặc email của nhóm phát triển.

📧 **Email liên hệ:**
- [thanhbinhsmart@gmail.com](mailto:thanhbinhsmart@gmail.com)
- [22010495@st.phenikaa-uni.edu.vn](mailto:22010495@st.phenikaa-uni.edu.vn)

Cảm ơn bạn đã quan tâm đến dự án! 🎉





