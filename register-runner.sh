#!/bin/bash

echo "=========================================="
echo "GitLab Runner Registration Helper"
echo "=========================================="
echo ""
echo "Lưu ý: URL phải có http:// hoặc https://"
echo ""

# Nhập GitLab URL
read -p "Nhập GitLab URL (ví dụ: https://gitlab.com): " GITLAB_URL

# Kiểm tra URL có scheme chưa
if [[ ! $GITLAB_URL =~ ^https?:// ]]; then
    echo "⚠️  URL thiếu scheme! Đang thêm https://..."
    GITLAB_URL="https://${GITLAB_URL}"
fi

echo ""
read -p "Nhập Registration Token: " REGISTRATION_TOKEN

echo ""
echo "Đang đăng ký runner với:"
echo "  URL: $GITLAB_URL"
echo "  Token: ${REGISTRATION_TOKEN:0:10}..."
echo ""

docker compose exec gitlab-runner gitlab-runner register \
    --non-interactive \
    --url "$GITLAB_URL" \
    --registration-token "$REGISTRATION_TOKEN" \
    --executor "docker" \
    --docker-image "docker:latest" \
    --docker-volumes "/var/run/docker.sock:/var/run/docker.sock" \
    --description "Laravel CI Runner" \
    --tag-list "docker,laravel,php" \
    --run-untagged="true" \
    --locked="false"

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Đăng ký thành công!"
    echo ""
    echo "Kiểm tra runner:"
    docker compose exec gitlab-runner gitlab-runner list
else
    echo ""
    echo "❌ Đăng ký thất bại. Vui lòng kiểm tra lại URL và token."
fi














