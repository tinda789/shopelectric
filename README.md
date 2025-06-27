# ShopElectrics - Hệ thống bán hàng điện tử

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-8892BF.svg)](https://php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Docker](https://img.shields.io/badge/Docker-✓-blue.svg?style=flat&logo=docker)](https://www.docker.com/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-blue.svg)](https://www.mysql.com/)

Hệ thống quản lý bán hàng điện tử với đầy đủ tính năng từ quản lý sản phẩm, đơn hàng, khách hàng đến báo cáo thống kê.

## 📋 Yêu cầu hệ thống

- Docker 20.10+
- Docker Compose 2.0+
- Git
- Ổ cứng trống tối thiểu 2GB
- RAM tối thiểu 2GB (khuyến nghị 4GB+)

## 🚀 Cài đặt nhanh

1. **Clone dự án**
   ```bash
   git clone https://github.com/yourusername/shopelectrics.git
   cd shopelectrics
   ```

2. **Sao chép file cấu hình**
   ```bash
   cp .env.example .env
   ```

3. **Khởi động Docker**
   ```bash
   docker-compose up -d
   ```

4. **Cài đặt dependencies**
   ```bash
   docker-compose exec app composer install
   docker-compose exec app npm install
   ```

5. **Tạo key ứng dụng**
   ```bash
   docker-compose exec app php artisan key:generate
   ```

6. **Chạy migrations và seed dữ liệu**
   ```bash
   docker-compose exec app php artisan migrate --seed
   ```

7. **Tạo liên kết lưu trữ**
   ```bash
   docker-compose exec app php artisan storage:link
   ```

8. **Truy cập ứng dụng**
   - Frontend: http://localhost:8000
   - Admin: http://localhost:8000/admin
   - phpMyAdmin: http://localhost:8080
     - Tài khoản: root
     - Mật khẩu: root

## 🔧 Cấu hình

### Biến môi trường chính

Tất cả các cấu hình đều nằm trong file `.env`:

```env
# Ứng dụng
APP_NAME="ShopElectrics"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Cơ sở dữ liệu
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=shopelectrics
DB_USERNAME=root
DB_PASSWORD=root

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## 🛠 Công nghệ sử dụng

- **Backend**: PHP 8.2, Laravel 10.x
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5, Vue.js 3
- **Cơ sở dữ liệu**: MySQL 8.0, Redis (cache)
- **Server**: Nginx, PHP-FPM
- **Công cụ phát triển**: Docker, Composer, NPM
- **Khác**: Git, GitHub Actions (CI/CD)

## 📂 Cấu trúc thư mục

```
shopelectrics/
├── app/                  # Ứng dụng chính
│   ├── Console/          # Lệnh Artisan
│   ├── Exceptions/       # Xử lý ngoại lệ
│   ├── Http/             # Controllers, Middleware, Requests
│   ├── Models/           # Eloquent Models
│   └── Providers/        # Service Providers
├── bootstrap/            # Khởi tạo ứng dụng
├── config/               # File cấu hình
├── database/             # Migrations, Seeders, Factories
├── public/               # Thư mục public
│   ├── css/              # CSS đã biên dịch
│   ├── js/               # JavaScript đã biên dịch
│   └── index.php         # File vào ứng dụng
├── resources/            # Views, assets chưa biên dịch
│   ├── js/               # JavaScript nguồn
│   ├── sass/             # SASS nguồn
│   └── views/            # Blade templates
├── routes/               # Định tuyến
│   ├── api.php           # API routes
│   ├── channels.php      # Broadcasting channels
│   ├── console.php       # Console commands
│   └── web.php           # Web routes
├── storage/              # File tạm, cache, logs
└── tests/                # Unit tests, Feature tests
```

## 🔒 Bảo mật

- Xác thực 2 yếu tố (2FA)
- Bảo vệ CSRF
- Xác thực JWT cho API
- Giới hạn đăng nhập sai
- Mã hóa dữ liệu nhạy cảm
- Bảo vệ chống tấn công XSS, SQL Injection

## 📄 Giấy phép

Dự án được phát triển dưới giấy phép [MIT](https://opensource.org/licenses/MIT).

## 👥 Đóng góp

1. Fork dự án
2. Tạo branch mới (`git checkout -b feature/AmazingFeature`)
3. Commit các thay đổi (`git commit -m 'Add some AmazingFeature'`)
4. Push lên branch (`git push origin feature/AmazingFeature`)
5. Mở Pull Request

## 📞 Liên hệ

- **Tác giả**: [Tên của bạn]
- **Email**: your.email@example.com
- **Website**: https://yourwebsite.com

## 🙏 Cảm ơn

Cảm ơn đã sử dụng dự án của chúng tôi! ⭐️
