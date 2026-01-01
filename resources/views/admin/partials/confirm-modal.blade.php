<!-- Confirm Modal - Modal xác nhận chung cho tất cả chức năng -->
<div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);">
    <div class="modal-content bg-white rounded-xl shadow-xl max-w-md w-full mx-4 transform transition-all" style="transform: scale(0.95);">
        <div class="p-6">
            <!-- Icon -->
            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full" id="confirmModalIcon" style="background: #fee2e2;">
                <i class="fas fa-exclamation-triangle text-3xl" id="confirmModalIconClass" style="color: #ef4444;"></i>
            </div>
            
            <!-- Title -->
            <h3 class="text-xl font-semibold text-gray-900 text-center mb-2" id="confirmModalTitle">Xác nhận</h3>
            
            <!-- Message -->
            <p class="text-gray-600 text-center mb-6" id="confirmModalMessage">Bạn có chắc chắn muốn thực hiện hành động này?</p>
            
            <!-- Actions -->
            <div class="flex gap-3">
                <button type="button" id="confirmModalCancelBtn" class="flex-1 px-4 py-2.5 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Hủy
                </button>
                <button type="button" id="confirmModalConfirmBtn" class="flex-1 px-4 py-2.5 rounded-lg transition-colors font-medium text-white" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                    Xác nhận
                </button>
            </div>
        </div>
    </div>
</div>

<style>
#confirmModal.show {
    display: flex !important;
}

#confirmModal.show .modal-content {
    transform: scale(1) !important;
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

#confirmModalIcon.info {
    background: #dbeafe;
}

#confirmModalIcon.info #confirmModalIconClass {
    color: #3b82f6;
}

#confirmModalIcon.warning {
    background: #fef3c7;
}

#confirmModalIcon.warning #confirmModalIconClass {
    color: #f59e0b;
}

#confirmModalIcon.success {
    background: #d1fae5;
}

#confirmModalIcon.success #confirmModalIconClass {
    color: #10b981;
}

#confirmModalIcon.danger {
    background: #fee2e2;
}

#confirmModalIcon.danger #confirmModalIconClass {
    color: #ef4444;
}
</style>

<script>
/**
 * Confirm Modal Helper - Sử dụng chung cho tất cả các chức năng
 * @param {Object} options - Các tùy chọn
 * @param {string} options.title - Tiêu đề modal (default: 'Xác nhận')
 * @param {string} options.message - Nội dung thông báo
 * @param {string} options.type - Loại modal: 'danger', 'warning', 'info', 'success' (default: 'danger')
 * @param {string} options.confirmText - Text nút xác nhận (default: 'Xác nhận')
 * @param {string} options.cancelText - Text nút hủy (default: 'Hủy')
 * @param {Function} options.onConfirm - Callback khi xác nhận
 * @param {Function} options.onCancel - Callback khi hủy
 * @returns {Promise} Promise resolve với true nếu confirm, false nếu cancel
 */
window.showConfirmModal = function(options = {}) {
    return new Promise((resolve, reject) => {
        const modal = document.getElementById('confirmModal');
        const titleEl = document.getElementById('confirmModalTitle');
        const messageEl = document.getElementById('confirmModalMessage');
        const iconEl = document.getElementById('confirmModalIcon');
        const iconClassEl = document.getElementById('confirmModalIconClass');
        const confirmBtn = document.getElementById('confirmModalConfirmBtn');
        const cancelBtn = document.getElementById('confirmModalCancelBtn');
        
        if (!modal || !titleEl || !messageEl || !confirmBtn || !cancelBtn) {
            console.error('Confirm modal elements not found');
            reject(new Error('Confirm modal not found'));
            return;
        }
        
        // Default values
        const {
            title = 'Xác nhận',
            message = 'Bạn có chắc chắn muốn thực hiện hành động này?',
            type = 'danger',
            confirmText = 'Xác nhận',
            cancelText = 'Hủy',
            onConfirm = null,
            onCancel = null
        } = options;
        
        // Set content
        titleEl.textContent = title;
        messageEl.textContent = message;
        confirmBtn.textContent = confirmText;
        cancelBtn.textContent = cancelText;
        
        // Set icon based on type
        iconEl.className = 'flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full ' + type;
        
        const iconMap = {
            'danger': 'fa-exclamation-triangle',
            'warning': 'fa-exclamation-triangle',
            'info': 'fa-info-circle',
            'success': 'fa-check-circle'
        };
        
        iconClassEl.className = 'fas ' + (iconMap[type] || 'fa-exclamation-triangle') + ' text-3xl';
        
        // Remove all existing event listeners by replacing buttons
        const confirmBtnParent = confirmBtn.parentNode;
        const cancelBtnParent = cancelBtn.parentNode;
        const newConfirmBtn = confirmBtn.cloneNode(true);
        const newCancelBtn = cancelBtn.cloneNode(true);
        confirmBtnParent.replaceChild(newConfirmBtn, confirmBtn);
        cancelBtnParent.replaceChild(newCancelBtn, cancelBtn);
        
        // Create handlers that will be removed later
        let handlersRemoved = false;
        
        const confirmHandler = function() {
            if (handlersRemoved) return;
            handlersRemoved = true;
            closeConfirmModal();
            cleanup();
            if (onConfirm) {
                onConfirm();
            }
            resolve(true);
        };
        
        const cancelHandler = function() {
            if (handlersRemoved) return;
            handlersRemoved = true;
            closeConfirmModal();
            cleanup();
            if (onCancel) {
                onCancel();
            }
            resolve(false);
        };
        
        const backdropHandler = function(e) {
            if (e.target === modal && !handlersRemoved) {
                handlersRemoved = true;
                closeConfirmModal();
                cleanup();
                if (onCancel) {
                    onCancel();
                }
                resolve(false);
            }
        };
        
        const escapeHandler = function(e) {
            if (e.key === 'Escape' && !handlersRemoved) {
                handlersRemoved = true;
                closeConfirmModal();
                cleanup();
                if (onCancel) {
                    onCancel();
                }
                resolve(false);
            }
        };
        
        const cleanup = function() {
            newConfirmBtn.removeEventListener('click', confirmHandler);
            newCancelBtn.removeEventListener('click', cancelHandler);
            modal.removeEventListener('click', backdropHandler);
            document.removeEventListener('keydown', escapeHandler);
        };
        
        // Add event listeners
        newConfirmBtn.addEventListener('click', confirmHandler);
        newCancelBtn.addEventListener('click', cancelHandler);
        modal.addEventListener('click', backdropHandler);
        document.addEventListener('keydown', escapeHandler);
        
        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    });
};

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
        document.body.style.overflow = '';
    }
}

// Export for use in other scripts
window.closeConfirmModal = closeConfirmModal;
</script>

