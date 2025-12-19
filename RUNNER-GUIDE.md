# Hướng dẫn sử dụng GitLab Runner

## 📋 Trạng thái Runner

Runner đã được cấu hình và đang chạy. Bạn có thể kiểm tra trạng thái bằng các lệnh sau:

## 🚀 Các lệnh quản lý Runner

### 1. Khởi động Runner
```bash
docker compose up -d gitlab-runner
```

### 2. Dừng Runner
```bash
docker compose stop gitlab-runner
```

### 3. Khởi động lại Runner
```bash
docker compose restart gitlab-runner
```

### 4. Xem logs của Runner
```bash
docker compose logs -f gitlab-runner
```

### 5. Kiểm tra trạng thái Runner
```bash
docker compose exec gitlab-runner gitlab-runner list
```

### 6. Verify Runner (kiểm tra kết nối với GitLab)
```bash
docker compose exec gitlab-runner gitlab-runner verify
```

### 7. Xem thông tin chi tiết Runner
```bash
docker compose exec gitlab-runner gitlab-runner status
```

## 🔧 Cấu hình Runner

File cấu hình runner nằm tại: `/etc/gitlab-runner/config.toml` trong container

Xem cấu hình:
```bash
docker compose exec gitlab-runner cat /etc/gitlab-runner/config.toml
```

## 📝 Sử dụng trong GitLab CI/CD

### File `.gitlab-ci.yml` đã được tạo sẵn

File `.gitlab-ci.yml` đã được tạo với các stage:
- **test**: Chạy tests PHP và linting
- **build**: Build assets (npm)
- **deploy**: Deploy lên staging/production

### Tags của Runner

Runner này có các tags:
- `docker`
- `laravel`
- `php`

Đảm bảo trong `.gitlab-ci.yml` bạn sử dụng tag phù hợp:
```yaml
tags:
  - docker
  - laravel
```

## 🐛 Troubleshooting

### Runner không nhận jobs
1. Kiểm tra runner có đang chạy:
   ```bash
   docker compose ps gitlab-runner
   ```

2. Kiểm tra runner có online trên GitLab:
   - Vào GitLab Project → Settings → CI/CD → Runners
   - Xem runner có status "Online" không

3. Kiểm tra logs:
   ```bash
   docker compose logs gitlab-runner
   ```

### Lỗi Docker trong jobs
Nếu gặp lỗi về Docker, đảm bảo runner có quyền truy cập Docker socket (đã được cấu hình trong `compose.yaml`)

### Lỗi kết nối GitLab
1. Verify runner:
   ```bash
   docker compose exec gitlab-runner gitlab-runner verify
   ```

2. Kiểm tra URL và token trong config:
   ```bash
   docker compose exec gitlab-runner cat /etc/gitlab-runner/config.toml
   ```

## 📚 Tài liệu tham khảo

- [GitLab Runner Documentation](https://docs.gitlab.com/runner/)
- [GitLab CI/CD Documentation](https://docs.gitlab.com/ee/ci/)





