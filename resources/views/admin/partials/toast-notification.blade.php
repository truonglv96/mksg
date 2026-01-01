<!-- Toast Notification Container -->
<div id="toastContainer" class="fixed bottom-4 right-4 z-50 flex flex-col gap-3" style="max-width: 400px;"></div>

<style>
.toast {
    display: flex;
    align-items: center;
    padding: 16px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    animation: slideInRight 0.3s ease-out;
    min-width: 300px;
    max-width: 400px;
    position: relative;
    overflow: hidden;
}

.toast::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
}

.toast-success {
    background: white;
    border: 1px solid #d1fae5;
    color: #065f46;
}

.toast-success::before {
    background: #10b981;
}

.toast-error {
    background: white;
    border: 1px solid #fee2e2;
    color: #991b1b;
}

.toast-error::before {
    background: #ef4444;
}

.toast-info {
    background: white;
    border: 1px solid #dbeafe;
    color: #1e40af;
}

.toast-info::before {
    background: #3b82f6;
}

.toast-warning {
    background: white;
    border: 1px solid #fef3c7;
    color: #92400e;
}

.toast-warning::before {
    background: #f59e0b;
}

.toast-icon {
    font-size: 20px;
    margin-right: 12px;
    flex-shrink: 0;
}

.toast-content {
    flex: 1;
    font-size: 14px;
    font-weight: 500;
    line-height: 1.5;
}

.toast-close {
    background: transparent;
    border: none;
    color: inherit;
    cursor: pointer;
    padding: 4px;
    margin-left: 12px;
    opacity: 0.6;
    transition: opacity 0.2s;
    flex-shrink: 0;
}

.toast-close:hover {
    opacity: 1;
}

.toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: rgba(0, 0, 0, 0.1);
    animation: progressBar linear forwards;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

@keyframes progressBar {
    from {
        width: 100%;
    }
    to {
        width: 0%;
    }
}

.toast-removing {
    animation: slideOutRight 0.3s ease-out forwards;
}
</style>

<script>
// Toast Notification System
(function() {
    'use strict';
    
    const toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        console.error('Toast container not found');
        return;
    }
    
    /**
     * Show a toast notification
     * @param {string} message - The message to display
     * @param {string} type - The type of toast: 'success', 'error', 'info', 'warning'
     * @param {number} duration - Duration in milliseconds (default: 3000)
     */
    window.showNotification = function(message, type = 'info', duration = 3000) {
        if (!message) return;
        
        // Validate type
        const validTypes = ['success', 'error', 'info', 'warning'];
        if (!validTypes.includes(type)) {
            type = 'info';
        }
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        // Set icon based on type
        let icon = 'fa-info-circle';
        switch(type) {
            case 'success':
                icon = 'fa-check-circle';
                break;
            case 'error':
                icon = 'fa-exclamation-circle';
                break;
            case 'warning':
                icon = 'fa-exclamation-triangle';
                break;
            case 'info':
            default:
                icon = 'fa-info-circle';
                break;
        }
        
        // Create toast content
        toast.innerHTML = `
            <i class="fas ${icon} toast-icon"></i>
            <div class="toast-content">${escapeHtml(message)}</div>
            <button class="toast-close" onclick="this.closest('.toast').remove()">
                <i class="fas fa-times"></i>
            </button>
            ${duration > 0 ? '<div class="toast-progress" style="animation-duration: ' + duration + 'ms;"></div>' : ''}
        `;
        
        // Add to container
        toastContainer.appendChild(toast);
        
        // Auto remove after duration
        if (duration > 0) {
            setTimeout(() => {
                removeToast(toast);
            }, duration);
        }
        
        return toast;
    };
    
    /**
     * Remove toast with animation
     */
    function removeToast(toast) {
        if (!toast || !toast.parentNode) return;
        
        toast.classList.add('toast-removing');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    }
    
    /**
     * Escape HTML to prevent XSS
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Helper functions for convenience
    window.showSuccess = function(message, duration = 3000) {
        return showNotification(message, 'success', duration);
    };
    
    window.showError = function(message, duration = 5000) {
        return showNotification(message, 'error', duration);
    };
    
    window.showInfo = function(message, duration = 3000) {
        return showNotification(message, 'info', duration);
    };
    
    window.showWarning = function(message, duration = 4000) {
        return showNotification(message, 'warning', duration);
    };
})();
</script>
