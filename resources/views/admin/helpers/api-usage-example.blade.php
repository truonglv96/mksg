{{-- 
    HƯỚNG DẪN SỬ DỤNG API HELPER
    
    File này là tài liệu hướng dẫn cách sử dụng API Helper để xử lý API response
    và hiển thị thông báo toast notification tự động.
--}}

<script>
// ============================================
// VÍ DỤ 1: Save/Update Form đơn giản
// ============================================
function saveData() {
    const form = document.getElementById('myForm');
    const formData = new FormData(form);
    const id = formData.get('id');
    const url = id ? `/admin/resource/${id}` : '/admin/resource';
    const method = id ? 'PUT' : 'POST';
    
    // Disable submit button
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Đang lưu...';
    
    // Use API helper
    handleApiResponse(
        apiFetch(url, {
            method: method === 'PUT' ? 'POST' : method,
            body: formData
        }),
        {
            onSuccess: (data) => {
                // Close modal or reset form
                closeModal();
            },
            reloadOnSuccess: true,
            reloadDelay: 500,
            errorFieldMapper: (field) => {
                // Map field name to input ID and error div ID
                return {
                    inputId: 'my' + field.charAt(0).toUpperCase() + field.slice(1),
                    errorDivId: field + 'Error'
                };
            },
            onError: () => {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        }
    );
}

// ============================================
// VÍ DỤ 2: Delete với xác nhận
// ============================================
function deleteItem(id, name) {
    if (!confirm(`Bạn có chắc chắn muốn xóa "${name}"?`)) {
        return;
    }
    
    handleApiResponse(
        apiFetch(`/admin/resource/${id}`, { method: 'DELETE' }),
        {
            reloadOnSuccess: true,
            reloadDelay: 500
        }
    );
}

// ============================================
// VÍ DỤ 3: Custom success/error handling
// ============================================
function customSave() {
    handleApiResponse(
        apiFetch('/admin/resource', {
            method: 'POST',
            body: formData
        }),
        {
            onSuccess: (data, response) => {
                // Custom success handling
                console.log('Success!', data);
                // Do something custom
                updateUI(data);
            },
            onError: (error, response) => {
                // Custom error handling
                console.error('Error!', error);
                // Do something custom
            },
            showSuccessToast: true,  // Default: true
            showErrorToast: true,    // Default: true
            reloadOnSuccess: false   // Default: false
        }
    );
}

// ============================================
// VÍ DỤ 4: Với validation errors mapping
// ============================================
function saveWithValidationMapping() {
    handleApiResponse(
        apiFetch('/admin/resource', {
            method: 'POST',
            body: formData
        }),
        {
            errorFieldMapper: (field) => {
                // Map các field name khác nhau
                const mapping = {
                    'name': { inputId: 'productName', errorDivId: 'nameError' },
                    'image': { inputId: 'productImageInput', errorDivId: 'imageError' },
                    'price': { inputId: 'productPrice', errorDivId: 'priceError' }
                };
                
                return mapping[field] || {
                    inputId: field,
                    errorDivId: field + 'Error'
                };
            }
        }
    );
}

// ============================================
// VÍ DỤ 5: Clear validation errors
// ============================================
function resetForm() {
    // Clear all validation errors
    clearValidationErrors();
    
    // Reset form
    document.getElementById('myForm').reset();
}
</script>

