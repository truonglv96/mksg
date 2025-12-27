@push('styles')
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
    
    .category-checkbox-item {
        padding: 8px 12px;
        margin: 4px 0;
        border-radius: 6px;
        transition: all 0.2s;
    }
    .category-checkbox-item:hover {
        background-color: #f3f4f6;
    }
    .category-checkbox-item.level-0 {
        font-weight: 600;
        padding-left: 12px;
    }
    .category-checkbox-item.level-1 {
        padding-left: 32px;
        color: #4b5563;
    }
    .category-checkbox-item.level-2 {
        padding-left: 52px;
        color: #6b7280;
        font-size: 13px;
    }
    .color-checkbox-item {
        display: inline-flex;
        align-items: center;
        padding: 10px 14px;
        margin: 6px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        background: white;
    }
    .color-checkbox-item:hover {
        border-color: #3b82f6;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .color-checkbox-item input:checked + label {
        font-weight: 600;
    }
    .color-checkbox-item input:checked ~ .color-checkbox-item {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    .color-square {
        width: 24px;
        height: 24px;
        border-radius: 4px;
        border: 2px solid #e5e7eb;
        margin-right: 8px;
        display: inline-block;
    }
    
    /* Sale Price Row Animation */
    .sale-price-row.fade-in {
        animation: fadeInSlide 0.3s ease-out;
    }
    
    @keyframes fadeInSlide {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .sale-price-row {
        transition: all 0.2s ease;
    }
    
    /* Combo Row Styles */
    .combo-row.fade-in {
        animation: fadeInSlide 0.3s ease-out;
    }
    
    .combo-row {
        transition: all 0.2s ease;
    }
    
    /* Drag & Drop Zone Styles */
    #multipleImagesContainer {
        position: relative;
        overflow: hidden;
    }
    
    #multipleImagesContainer:hover {
        border-color: #3b82f6 !important;
        background-color: #f0f9ff !important;
    }
    
    #multipleImagesContainer.drag-over {
        border-color: #3b82f6 !important;
        background-color: #dbeafe !important;
        transform: scale(1.02);
    }
    
    #multipleImagesContainer .fa-cloud-upload-alt {
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-5px);
        }
    }
    
    /* Color Selector Modal */
    .color-selector-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .color-selector-modal.active {
        opacity: 1;
        visibility: visible;
    }
    
    .color-selector-content {
        background: white;
        border-radius: 16px;
        padding: 24px;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        transform: scale(0.9);
        transition: transform 0.3s ease;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .color-selector-modal.active .color-selector-content {
        transform: scale(1);
    }
    
    .color-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
        gap: 12px;
        margin-top: 16px;
    }
    
    .color-option {
        aspect-ratio: 1;
        border-radius: 8px;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .color-option img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .color-option:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        border-color: #3b82f6;
    }
    
    .color-option.selected {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3);
    }
    
    .color-option.selected::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-weight: bold;
        font-size: 18px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    }
</style>
@endpush

