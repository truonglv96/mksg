{{--
    Delete Confirmation Modal Component
    
    Usage Examples:
    
    1. For Products:
       @include('admin.helpers.delete-modal', [
           'id' => 'deleteModal',
           'title' => 'Xác nhận xóa sản phẩm',
           'message' => 'Bạn có chắc chắn muốn xóa sản phẩm "{name}"?',
           'confirmText' => 'Xóa sản phẩm'
       ])
       
       In JavaScript:
       confirmDelete(productId, '/admin/products/' + productId, productName);
       OR
       openDeleteModal('deleteModal', '/admin/products/' + productId, productName);
    
    2. For News/Posts:
       @include('admin.helpers.delete-modal', [
           'id' => 'deleteNewsModal',
           'title' => 'Xác nhận xóa tin tức',
           'message' => 'Bạn có chắc chắn muốn xóa tin tức "{name}"?',
           'confirmText' => 'Xóa tin tức'
       ])
       
       In JavaScript:
       openDeleteModal('deleteNewsModal', '/admin/news/' + newsId, newsTitle);
    
    3. Generic usage:
       @include('admin.helpers.delete-modal')
       
       In JavaScript:
       // Option 1: With auto-detected URL (from current page path)
       confirmDelete(itemId, itemName);
       // Example: confirmDelete(6651, 'Product Name');
       
       // Option 2: With explicit URL
       confirmDelete(itemId, itemName, deleteUrl);
       // Example: confirmDelete(6651, 'Product Name', '/admin/products/6651');
       
       // Option 3: Direct URL (alternative syntax)
       confirmDelete(itemId, deleteUrl, itemName);
--}}

@props([
    'id' => 'deleteModal',
    'title' => 'Xác nhận xóa',
    'message' => 'Bạn có chắc chắn muốn xóa mục này?',
    'confirmText' => 'Xóa',
    'cancelText' => 'Hủy',
    'itemName' => null, // Tên của item để hiển thị trong message
])

<!-- Delete Confirmation Modal -->
<div id="{{ $id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity modal-overlay"></div>

    <!-- Modal container -->
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg modal-content">
            <!-- Modal header -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4 rounded-t-xl">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-white" id="modal-title">
                        {{ $title }}
                    </h3>
                </div>
            </div>

            <!-- Modal body -->
            <div class="bg-white px-6 py-6">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-trash-alt text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="deleteModalMessage_{{ $id }}">
                                @if($itemName)
                                    {{ str_replace('{name}', $itemName, $message) }}
                                @else
                                    {{ $message }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 gap-3">
                <button type="button" 
                        onclick="closeDeleteModal('{{ $id }}')" 
                        class="inline-flex w-full justify-center items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-smooth sm:mt-0 sm:w-auto">
                    <i class="fas fa-times mr-2"></i>
                    {{ $cancelText }}
                </button>
                <form id="deleteForm_{{ $id }}" method="POST" action="#" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex w-full justify-center items-center rounded-lg bg-gradient-to-r from-red-600 to-red-700 px-4 py-2.5 text-base font-medium text-white shadow-md hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-lg sm:w-auto">
                        <i class="fas fa-trash mr-2"></i>
                        {{ $confirmText }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Ensure functions are available globally
    window.openDeleteModal = function(modalId, deleteUrl, itemName = null) {
        const modal = document.getElementById(modalId);
        const form = document.getElementById('deleteForm_' + modalId);
        const messageElement = document.getElementById('deleteModalMessage_' + modalId);
        
        if (!modal) {
            console.error('Modal not found with ID:', modalId);
            return;
        }
        
        if (!form) {
            console.error('Form not found with ID:', 'deleteForm_' + modalId);
            return;
        }
        
        // Set the form action (ensure it's a valid URL)
        if (!deleteUrl) {
            console.error('Delete URL is required');
            return;
        }
        form.action = deleteUrl;
        console.log('Delete form action set to:', deleteUrl);
        
        // Reset form state (enable submit button, reset loading state)
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = false;
            // Save original button text if not already saved
            if (!submitButton.getAttribute('data-original-text')) {
                submitButton.setAttribute('data-original-text', submitButton.innerHTML);
            }
        }
        
        // Update message if item name is provided
        if (itemName && messageElement) {
            const originalMessage = messageElement.getAttribute('data-original-message') || messageElement.textContent.trim();
            if (!messageElement.getAttribute('data-original-message')) {
                messageElement.setAttribute('data-original-message', originalMessage);
            }
            messageElement.textContent = originalMessage.replace(/{name}/g, itemName);
        }
        
        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Add animation
        setTimeout(() => {
            const overlay = modal.querySelector('.modal-overlay');
            const content = modal.querySelector('.modal-content');
            if (overlay) overlay.style.opacity = '1';
            if (content) {
                content.style.opacity = '1';
                content.style.transform = 'scale(1)';
            }
        }, 10);
    };
    
    // Function to close delete modal
    window.closeDeleteModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        
        // Hide modal with animation
        const overlay = modal.querySelector('.modal-overlay');
        const content = modal.querySelector('.modal-content');
        if (overlay) overlay.style.opacity = '0';
        if (content) {
            content.style.opacity = '0';
            content.style.transform = 'scale(0.95)';
        }
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    };
    
    // Generic confirmDelete function
    // Usage patterns:
    // - confirmDelete(itemId, itemName) - will try to infer URL from current page
    // - confirmDelete(itemId, itemName, deleteUrl) - with explicit URL (if itemName is provided)
    // - confirmDelete(itemId, deleteUrl, itemName) - alternative order (if deleteUrl is provided)
    window.confirmDelete = function(itemId, param2, param3) {
        let deleteUrl, itemName;
        const defaultModalId = 'deleteModal';
        
        // Determine parameters based on type and pattern
        if (typeof param2 === 'string') {
            // If param2 starts with '/' or 'http', it's a URL
            if (param2.startsWith('/') || param2.startsWith('http')) {
                deleteUrl = param2;
                itemName = param3 || null;
            } else {
                // param2 is itemName
                itemName = param2;
                
                // Check if param3 is a URL
                if (param3 && (param3.startsWith('/') || param3.startsWith('http'))) {
                    deleteUrl = param3;
                } else {
                    // Need to construct URL from current page context
                    const currentPath = window.location.pathname;
                    
                    // Remove trailing slashes and split
                    const pathParts = currentPath.replace(/\/$/, '').split('/').filter(p => p);
                    
                    // Try to find resource name from path
                    // Pattern: /admin/{resource}/{id}/...
                    if (pathParts.length >= 2 && pathParts[0] === 'admin') {
                        const resource = pathParts[1]; // e.g., 'products', 'news', etc.
                        deleteUrl = `/admin/${resource}/${itemId}`;
                    } else if (currentPath.includes('/admin/products')) {
                        // Fallback: explicitly check for products
                        deleteUrl = `/admin/products/${itemId}`;
                    } else {
                        console.error('Cannot infer delete URL from current path:', currentPath);
                        console.error('Please provide URL: confirmDelete(itemId, itemName, deleteUrl)');
                        return;
                    }
                }
            }
        } else {
            console.error('confirmDelete: Invalid parameters. Expected: confirmDelete(itemId, itemName, [deleteUrl])');
            return;
        }
        
        // Use the default modal
        if (window.openDeleteModal) {
            window.openDeleteModal(defaultModalId, deleteUrl, itemName);
        } else {
            console.error('openDeleteModal function not found. Make sure delete-modal component is included.');
        }
    };
    
    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[id$="deleteModal"]').forEach(modal => {
            const modalId = modal.id;
            const form = document.getElementById('deleteForm_' + modalId);
            
            // Handle form submit
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Ensure form action is set
                    if (!form.action || form.action === window.location.href) {
                        console.error('Form action not set. Cannot submit delete request.');
                        e.preventDefault();
                        return false;
                    }
                    
                    // Show loading state (optional - you can add a spinner here)
                    const submitButton = form.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xóa...';
                    }
                    
                    // Form will submit normally and page will redirect
                    // Modal will be closed when page reloads
                });
            }
            
            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === this || e.target.classList.contains('modal-overlay')) {
                    window.closeDeleteModal(modal.id);
                }
            });
        });
        
        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('[id$="deleteModal"]').forEach(modal => {
                    if (!modal.classList.contains('hidden')) {
                        window.closeDeleteModal(modal.id);
                    }
                });
            }
        });
    });
</script>

<style>
    [id$="deleteModal"] .modal-overlay {
        transition: opacity 0.2s ease-in-out;
        opacity: 0;
    }
    
    [id$="deleteModal"] .modal-content {
        transition: all 0.2s ease-in-out;
        opacity: 0;
        transform: scale(0.95);
    }
    
    [id$="deleteModal"]:not(.hidden) .modal-overlay {
        opacity: 1;
    }
    
    [id$="deleteModal"]:not(.hidden) .modal-content {
        opacity: 1;
        transform: scale(1);
    }
</style>
@endpush

