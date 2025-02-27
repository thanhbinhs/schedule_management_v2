# Chương trình Quản lý Đào tạo và Xây dựng Thời Khóa Biểu của Trường Đại học Phenikaa


Sinh viên: Lê Thanh Bình - 22010495
## 📌 Giới thiệu
Ứng dụng giúp quản lý đào tạo và lập thời khóa biểu cho Trường Đại học Phenikaa, giúp nhà trường tối ưu hóa việc tổ chức giảng dạy.

## 🔗 Liên kết quan trọng
- **Repository GitHub**: [schedule_management_v2](https://github.com/thanhbinhs/schedule_management_v2)
- **Phiên bản Public**: [schedulemanagement.rf.gd](http://schedulemanagement.rf.gd)

## 🧑‍💻 Tài khoản dùng thử
Người dùng có thể đăng nhập vào hệ thống bằng tài khoản thử nghiệm:
- **User**: `pdt`
- **Password**: `123456a@`

## 📖 Hướng dẫn sử dụng
1. Truy cập [schedulemanagement.rf.gd](http://schedulemanagement.rf.gd)
2. Đăng nhập bằng tài khoản thử nghiệm
3. Khám phá các chức năng quản lý đào tạo và lập thời khóa biểu

## 🚀 Công nghệ sử dụng
- **Backend**: Laravel (PHP Framework)
- **Frontend**: Blade Template, Bootstrap
- **Database**: MySQL

## ⚙️ Hướng dẫn chạy Laravel (Backend)

Để chạy ứng dụng Laravel trên máy tính của bạn, hãy làm theo các bước sau:

**Yêu cầu:**

- **PHP**: Phiên bản >= 8.1
- **Composer**: [Cài đặt Composer](https://getcomposer.org/doc/00-intro.md#installation-globally)
- **MySQL**: Đã cài đặt và cấu hình MySQL server
- **Node.js và npm (hoặc yarn)**: Để biên dịch assets (nếu cần)

**Các bước:**

1. **Clone Repository:**
   ```bash
   git clone https://github.com/thanhbinhs/schedule_management_v2.git
   cd schedule_management_v2
Use code with caution.
Markdown
Cài đặt Composer Dependencies:

composer install
Use code with caution.
Bash
Sao chép file .env.example thành .env và cấu hình:

cp .env.example .env
Use code with caution.
Bash
Mở file .env và cấu hình các thông tin sau:

DB_CONNECTION=mysql

DB_HOST=127.0.0.1 (hoặc hostname MySQL server của bạn)

DB_PORT=3306 (hoặc port MySQL server của bạn)

DB_DATABASE=your_database_name (Tên database bạn muốn tạo)

DB_USERNAME=your_database_username (Username MySQL)

DB_PASSWORD=your_database_password (Password MySQL)

APP_URL=http://localhost:8000 (Hoặc URL bạn muốn sử dụng)

Tạo Application Key:

php artisan key:generate
Use code with caution.
Bash
Tạo Database và chạy Migrations và Seeders (nếu có):

Tạo database với tên bạn đã cấu hình trong .env (ví dụ: your_database_name) bằng công cụ quản lý MySQL (ví dụ: phpMyAdmin, MySQL Workbench, command line).

Chạy migrations:

php artisan migrate
Use code with caution.
Bash
Chạy seeders (nếu có dữ liệu mẫu):

php artisan db:seed
Use code with caution.
Bash
Biên dịch Assets (nếu có thay đổi frontend):

npm install
npm run dev  # Hoặc npm run watch, npm run production tùy theo nhu cầu
Use code with caution.
Bash
Nếu bạn không có npm, hãy thử yarn install và yarn dev.
Bước này có thể không cần thiết nếu bạn chỉ muốn chạy backend và không có thay đổi frontend.

Khởi chạy Development Server:

php artisan serve
Use code with caution.
Bash
Truy cập ứng dụng:
Mở trình duyệt và truy cập http://localhost:8000 (hoặc URL bạn đã cấu hình trong APP_URL).

Lưu ý:

Đảm bảo bạn đã cài đặt và cấu hình MySQL server trước khi chạy các lệnh liên quan đến database.

Nếu bạn gặp lỗi, hãy kiểm tra log file của Laravel tại storage/logs/laravel.log để biết thêm chi tiết.

Các lệnh npm (hoặc yarn) ở bước 6 chỉ cần thiết nếu bạn có thay đổi ở frontend hoặc muốn biên dịch lại assets.

📬 Liên hệ
Nếu có bất kỳ thắc mắc hoặc cần hỗ trợ, vui lòng liên hệ qua repository GitHub hoặc email của nhóm phát triển.
Email: thanhbinhsmart@gmail.com hoặc 22010495@st.phenikaa-uni.edu.vn

Cảm ơn bạn đã quan tâm đến dự án! 🎉

**Những thay đổi chính đã được thực hiện:**

* **Thêm phần "⚙️ Hướng dẫn chạy Laravel (Backend)"**: Phần này cung cấp các bước chi tiết để chạy ứng dụng Laravel trên máy cục bộ.
* **Liệt kê các yêu cầu**:  Đề cập đến các phần mềm và công cụ cần thiết (PHP, Composer, MySQL, Node.js/npm).
* **Cung cấp các lệnh cụ thể**:  Hướng dẫn sử dụng lệnh `git clone`, `composer install`, `cp`, `php artisan key:generate`, `php artisan migrate`, `php artisan db:seed`, `npm install`, `npm run dev`, `php artisan serve`.
* **Giải thích các bước cấu hình**:  Hướng dẫn cấu hình file `.env` và database.
* **Thêm lưu ý**:  Đề cập đến việc kiểm tra log file và sự cần thiết của bước biên dịch assets.

Bản README đã chỉnh sửa này sẽ giúp người dùng dễ dàng cài đặt và chạy dự án Laravel của bạn trên máy tính của họ. Hãy chắc chắn rằng các hướng dẫn này phù hợp với cấu hình dự án thực tế của bạn.
