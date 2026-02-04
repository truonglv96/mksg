# MKS G - Mắt Kính Sài Gòn (Upgrade v2)

## Tổng quan
Đây là dự án website thương mại điện tử/giới thiệu sản phẩm mắt kính với đầy đủ
front-end (web) và admin panel để quản trị nội dung. Hệ thống tập trung vào:
- Trưng bày danh mục và chi tiết sản phẩm theo nhiều cấp danh mục.
- Tin tức theo chuyên mục, bài viết chi tiết, và SEO.
- Giỏ hàng, đặt hàng/checkout, lưu hóa đơn và gửi email xác nhận.
- Quản trị sản phẩm, đơn hàng, khách hàng, tin tức, slider, cài đặt hệ thống.

## Công nghệ chính
- Backend: Laravel 12, PHP 8.4
- Frontend: Blade + Vite + Tailwind CSS v4
- CSDL: MySQL (Sail), có sẵn `database/database.sqlite`
- Cache/Queue: Redis (Sail)
- Mail: SMTP (Mailpit trong dev)
- Rich text: CKEditor (public/ckeditor)

## Cấu trúc thư mục nổi bật
- `app/Http/Controllers/Web`: controller cho trang web public.
- `app/Http/Controllers/Admin`: controller cho admin panel.
- `app/Models`: model chính của hệ thống (Products, Category, Bill, News...).
- `resources/views/web`: giao diện frontend.
- `resources/views/admin`: giao diện admin.
- `routes/web.php`: routes public.
- `routes/admin.php`: routes admin.
- `database/migrations`: migrations cơ bản.
- `database/seeders/*.sql`: dữ liệu khu vực (areas).
- `public/img`, `public/upload`: tài nguyên và ảnh sản phẩm.
- `docker/`, `Dockerfile`, `compose.yaml`: cấu hình Docker/Sail.
- `RAILWAY.md`, `RUNNER-GUIDE.md`: hướng dẫn deploy/runner.

## Chức năng web (public)
Tập trung trong `routes/web.php` và `app/Http/Controllers/Web/*`:
- Trang chủ `/`:
  - Banner slider, danh mục nổi bật, sản phẩm theo danh mục.
  - Tin tức mới, thương hiệu, featured categories (có cache).
- Sản phẩm `/bai-viet-san-pham`:
  - Danh sách sản phẩm theo danh mục hoặc đường dẫn nhiều cấp.
  - Filter theo giá, màu, chất liệu, thương hiệu.
  - Chi tiết sản phẩm kèm hình ảnh, màu sắc, combo giảm giá, range độ.
- Tin tức `/tin-tuc`:
  - Danh mục theo nhiều cấp, lọc theo từ khóa.
  - Chi tiết bài viết, SEO schema, bài viết liên quan.
- Thương hiệu `/thuong-hieu`, đối tác `/doi-tac`.
- Trang tĩnh `/trang/{alias}`.
- Tìm kiếm `POST /search`.
- Giỏ hàng `/gio-hang`, checkout `POST /checkout`:
  - Lưu đơn hàng vào bảng hóa đơn.
  - Gửi email xác nhận đơn hàng.
- Xóa cache `GET /clear-cache` (chỉ dùng khi cần).

## Chức năng admin
- Auth: `/admin/login`, logout (middleware `admin.guest`/`admin.auth`).
- Dashboard và Profile.
- Products:
  - CRUD sản phẩm, upload nhiều ảnh, sắp xếp ảnh, màu sắc.
  - Gán danh mục, thương hiệu, chất liệu, màu, combo giảm giá.
  - Thiết lập giá theo category, features sản phẩm, range độ.
- Categories: tạo/sửa/xóa, reorder.
- Orders: danh sách, chi tiết, cập nhật trạng thái, xóa đơn.
- Customers: danh sách, cập nhật, xóa.
- News: CRUD bài viết.
- Brands, Sliders.
- Store information (Contacts).
- Settings (logo, meta, social, nội dung footer...).
- Materials, Colors.
- Featured Categories, Features Product.

## Routing và middleware
Middleware admin được alias trong `bootstrap/app.php`:
- `admin.auth` → `AuthenticateAdmin`
- `admin.guest` → `RedirectIfAdminAuthenticated`

Routes chính:
- Web: `routes/web.php`
- Admin: `routes/admin.php`

## Model và bảng dữ liệu chính (tham khảo)
Các model trong `app/Models` phản ánh dữ liệu:
- Sản phẩm: `Products`, `ProductImage`, `ProductCategories`, `ProductColor`,
  `ProductPriceSale`, `ProductDegreeRange`, `DiscountedCombo`, `FeaturesProduct`.
- Danh mục: `Category`, `FeaturedCategory`.
- Tin tức: `News`, `NewsCategories`.
- Đơn hàng: `ClientInformation` (bill), `Bill` (bill_details),
  `TatalBillDetail` (tổng tiền).
- Khách hàng: `Customer`, `CustomerRole`, `CustomerAttribute`.
- Thương hiệu/đối tác: `Brand`, `Partner`.
- Cấu hình: `Setting`, `Contact`, `Slider`.
- Khu vực: `Area` (seed từ SQL: `database/seeders/*.sql`).

## Cache và hiệu năng
- Trang chủ và tin tức sử dụng `Cache::remember` để giảm truy vấn.
- Có fallback query nếu cache không khả dụng.

## Mail
Checkout gửi email xác nhận bằng `App\Mail\OrderConfirmationMail`
(`resources/views/emails/order-confirmation`).

## Tài nguyên frontend (Vite)
Vite entrypoint: `vite.config.js`
- `resources/css/app.css`
- `resources/js/app.js`
- `resources/js/admin/app_admin.js`
- `resources/js/admin/api-handler.js`

Trạng thái build:
- Có `public/hot` → đang dùng dev server.
- Có `public/build/manifest.json` → đã build production.

## Cách chạy dự án (local)
### 1) Cài đặt nhanh
```bash
composer run setup
```

### 2) Chạy dev
```bash
composer run dev
```
Script này chạy đồng thời: server, queue, logs, và Vite.

### 3) Chạy test
```bash
composer run test
```

## Chạy bằng Laravel Sail (Docker)
Các service chính: `laravel.test`, `mysql`, `redis`, `meilisearch`, `mailpit`,
`selenium`, `gitlab-runner`.

Ví dụ:
```bash
./vendor/bin/sail up -d
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

## Biến môi trường quan trọng
Tối thiểu cho production:
```
APP_NAME=Laravel
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://your-domain

DB_CONNECTION=mysql
DB_HOST=...
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```
Tùy chọn:
```
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=...
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=...
```

## Deploy Railway
Xem chi tiết `RAILWAY.md`:
- Đã có `Dockerfile`, `railway.toml`, `docker/nginx.conf`,
  `docker/supervisord.conf`.
- Cần cấu hình env, tạo MySQL, chạy migrations.

## GitLab Runner
Xem `RUNNER-GUIDE.md`:
- Service `gitlab-runner` đã được cấu hình trong `compose.yaml`.
- Hướng dẫn start/stop, logs, verify runner.

## Lưu ý vận hành
- Ảnh sản phẩm lưu trong `public/img/product` và `public/upload`.
- Nếu deploy production, cân nhắc storage ngoài (S3/Volumes).
- Nếu gặp lỗi ViteManifestNotFoundException, xem `VITE-BUILD-INSTRUCTIONS.md`.