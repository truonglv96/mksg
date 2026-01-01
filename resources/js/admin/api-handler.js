/**
 * API Handler Helper
 * Xử lý API response và hiển thị thông báo toast notification
 */

/**
 * Xử lý fetch API response và hiển thị thông báo
 * @param {Promise} fetchPromise - Promise từ fetch()
 * @param {Object} options - Các tùy chọn
 * @param {Function} options.onSuccess - Callback khi thành công
 * @param {Function} options.onError - Callback khi có lỗi
 * @param {Function} options.onValidationError - Callback khi có lỗi validation
 * @param {boolean} options.showSuccessToast - Hiển thị toast thành công (default: true)
 * @param {boolean} options.showErrorToast - Hiển thị toast lỗi (default: true)
 * @param {boolean} options.reloadOnSuccess - Tự động reload page khi thành công (default: false)
 * @param {number} options.reloadDelay - Delay trước khi reload (ms, default: 500)
 * @param {Function} options.errorFieldMapper - Function map field name sang input ID và error div ID
 */
window.handleApiResponse = async function(fetchPromise, options = {}) {
    const {
        onSuccess = null,
        onError = null,
        onValidationError = null,
        showSuccessToast = true,
        showErrorToast = true,
        reloadOnSuccess = false,
        reloadDelay = 500,
        errorFieldMapper = null
    } = options;

    try {
        const response = await fetchPromise;
        
        // Parse JSON response
        let data;
        try {
            data = await response.json();
        } catch (e) {
            // Nếu không parse được JSON, có thể là HTML error page
            throw {
                success: false,
                message: 'Không thể xử lý phản hồi từ server',
                status: response.status
            };
        }

        // Check if response is ok
        if (!response.ok || !data.success) {
            // Handle validation errors
            if (data.errors && typeof data.errors === 'object') {
                if (onValidationError) {
                    onValidationError(data.errors);
                } else {
                    handleValidationErrors(data.errors, errorFieldMapper);
                }
            }

            // Show error toast
            if (showErrorToast) {
                const errorMessage = data.message || 'Có lỗi xảy ra';
                if (typeof showError === 'function') {
                    showError(errorMessage);
                } else if (typeof showNotification === 'function') {
                    showNotification(errorMessage, 'error');
                } else {
                    alert(errorMessage);
                }
            }

            // Call error callback
            if (onError) {
                onError(data, response);
            }

            return { success: false, data, response };
        }

        // Success
        if (showSuccessToast && data.message) {
            if (typeof showSuccess === 'function') {
                showSuccess(data.message);
            } else if (typeof showNotification === 'function') {
                showNotification(data.message, 'success');
            } else {
                alert(data.message);
            }
        }

        // Call success callback
        if (onSuccess) {
            onSuccess(data, response);
        }

        // Auto reload if needed
        if (reloadOnSuccess) {
            setTimeout(() => {
                window.location.reload();
            }, reloadDelay);
        }

        return { success: true, data, response };

    } catch (error) {
        console.error('API Error:', error);

        // Handle validation errors from catch
        if (error.errors && typeof error.errors === 'object') {
            if (onValidationError) {
                onValidationError(error.errors);
            } else {
                handleValidationErrors(error.errors, errorFieldMapper);
            }
        }

        // Show error toast
        if (showErrorToast) {
            const errorMessage = error.message || error.error || 'Có lỗi xảy ra';
            if (typeof showError === 'function') {
                showError(errorMessage);
            } else if (typeof showNotification === 'function') {
                showNotification(errorMessage, 'error');
            } else {
                alert(errorMessage);
            }
        }

        // Call error callback
        if (onError) {
            onError(error, null);
        }

        return { success: false, error, response: null };
    }
};

/**
 * Xử lý validation errors và hiển thị cho từng field
 * @param {Object} errors - Object chứa errors {field: [messages]}
 * @param {Function} fieldMapper - Function map field name sang {inputId, errorDivId}
 */
function handleValidationErrors(errors, fieldMapper = null) {
    Object.keys(errors).forEach(field => {
        const errorMessages = Array.isArray(errors[field]) 
            ? errors[field] 
            : [errors[field]];
        
        if (errorMessages.length === 0) return;

        let inputId, errorDivId;

        if (fieldMapper) {
            const mapped = fieldMapper(field);
            inputId = mapped.inputId;
            errorDivId = mapped.errorDivId;
        } else {
            // Default mapping
            inputId = field;
            errorDivId = field + 'Error';
        }

        // Try to find error div by common patterns
        if (!document.getElementById(errorDivId)) {
            // Try alternative patterns
            const alternatives = [
                field + 'Error',
                field.charAt(0).toUpperCase() + field.slice(1) + 'Error',
                'error-' + field,
                field.replace(/_/g, '') + 'Error'
            ];

            for (const alt of alternatives) {
                if (document.getElementById(alt)) {
                    errorDivId = alt;
                    break;
                }
            }
        }

        // Show error for field
        showFieldError(inputId, errorDivId, errorMessages[0]);
    });
}

/**
 * Hiển thị lỗi cho một field cụ thể
 * @param {string} inputId - ID của input element
 * @param {string} errorDivId - ID của error message div
 * @param {string} message - Error message
 */
function showFieldError(inputId, errorDivId, message) {
    const input = document.getElementById(inputId);
    const errorDiv = document.getElementById(errorDivId);

    if (input) {
        input.classList.add('error');
        input.style.borderColor = '#ef4444';
    }

    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.classList.remove('hidden');
        if (errorDiv.classList.contains('d-none')) {
            errorDiv.classList.remove('d-none');
        }
    } else {
        console.warn('Error div not found:', errorDivId, 'for input:', inputId);
    }
}

/**
 * Clear tất cả validation errors
 */
window.clearValidationErrors = function() {
    document.querySelectorAll('.error-message, .text-danger, .invalid-feedback').forEach(el => {
        el.classList.add('hidden');
        if (el.classList.contains('d-none') === false) {
            el.classList.add('d-none');
        }
        el.textContent = '';
    });

    document.querySelectorAll('.form-input, .form-control, input, textarea, select').forEach(el => {
        el.classList.remove('error', 'is-invalid');
        el.style.borderColor = '';
    });
};

/**
 * Helper để tạo fetch request với CSRF token
 * @param {string} url - API URL
 * @param {Object} options - Fetch options
 * @returns {Promise} Fetch promise
 */
window.apiFetch = function(url, options = {}) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    
    if (!csrfToken) {
        console.error('CSRF token not found');
        throw new Error('CSRF token not found');
    }

    // Merge headers
    const headers = {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken.content,
        ...(options.headers || {})
    };

    // Handle method spoofing for PUT/DELETE
    let method = options.method || 'GET';
    let body = options.body;

    if (method === 'PUT' || method === 'DELETE') {
        if (body instanceof FormData) {
            body.append('_method', method);
            method = 'POST';
        } else if (typeof body === 'string') {
            try {
                const jsonBody = JSON.parse(body);
                jsonBody._method = method;
                body = JSON.stringify(jsonBody);
                method = 'POST';
            } catch (e) {
                // If not JSON, assume FormData was stringified (shouldn't happen)
            }
        }
    }

    return fetch(url, {
        ...options,
        method,
        body,
        headers
    });
};

