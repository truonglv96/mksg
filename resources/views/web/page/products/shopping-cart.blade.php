@extends('web.master')

@section('title', $title ?? config('texts.cart_page_title_default'))

@section('content')
<main class="container mx-auto px-4 py-8">

    {{-- Breadcrumb Component --}}
    @php
        $pageTitle = config('texts.cart_page_title');
    @endphp
    @include('web.partials.breadcrumb')

    <!-- Success Message -->
    <div id="order-success-message" class="order-success-message hidden">
        <div class="order-success-content">
            <div class="order-success-icon">
                <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            {!! $settings->order_success !!}
            <div class="order-success-actions">
                <a href="{{ route('product.category.index') }}" class="btn-primary">{{ config('texts.cart_continue_shopping_btn') }}</a>
            </div>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="toast-container"></div>

    <section id="order-section" class="order-section">
        <div class="order-header">
            <h1>{{ config('texts.cart_order_info_title') }}</h1>
            <p>{{ config('texts.cart_order_info_desc') }}</p>
        </div>

        <div id="order-empty-state" class="order-empty-state">
            <p>{{ config('texts.cart_empty_message') }}</p>
        </div>

        <div id="order-list" class="order-list hidden"></div>

        <div id="order-receipt" class="order-receipt hidden" aria-live="polite"></div>

        <div id="checkout-modal" class="checkout-modal" aria-modal="true" role="dialog" aria-labelledby="checkout-modal-title">
            <div class="checkout-modal__overlay" id="checkout-modal-overlay"></div>
            <div class="checkout-modal__card">
                <div class="checkout-modal__header">
                    <div>
                        <h2 class="checkout-modal__title" id="checkout-modal-title">{{ config('texts.cart_checkout_title') }}</h2>
                        <p class="checkout-modal__subtitle">{{ config('texts.cart_checkout_desc') }}</p>
                    </div>
                    <button type="button" class="checkout-modal__close" id="checkout-modal-close" aria-label="{{ config('texts.cart_close') }}">&times;</button>
                </div>
                <form id="checkout-form" class="checkout-modal__form">
                    @csrf
                    <div class="checkout-form-grid">
                        <div class="checkout-form-group">
                            <label for="checkout-name">{{ config('texts.cart_form_name') }}</label>
                            <input type="text" id="checkout-name" name="name" placeholder="{{ config('texts.cart_form_name_placeholder') }}" required>
                        </div>
                        <div class="checkout-form-group">
                            <label for="checkout-gender">{{ config('texts.cart_form_gender') }}</label>
                            <select id="checkout-gender" name="gender" required>
                                <option value="" disabled selected>{{ config('texts.cart_form_gender_placeholder') }}</option>
                                <option value="nam">{{ config('texts.cart_form_gender_male') }}</option>
                                <option value="nu">{{ config('texts.cart_form_gender_female') }}</option>
                                <option value="khac">{{ config('texts.cart_form_gender_other') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="checkout-form-grid">
                        <div class="checkout-form-group">
                            <label for="checkout-phone">{{ config('texts.cart_form_phone') }}</label>
                            <input type="tel" id="checkout-phone" name="phone" placeholder="{{ config('texts.cart_form_phone_placeholder') }}" required>
                        </div>
                        <div class="checkout-form-group">
                            <label for="checkout-email">{{ config('texts.cart_form_email') }}</label>
                            <input type="email" id="checkout-email" name="email" placeholder="{{ config('texts.cart_form_email_placeholder') }}">
                        </div>
                    </div>
                    <div class="checkout-form-grid">
                        <div class="checkout-form-group">
                            <label for="checkout-city">{{ config('texts.cart_form_city') }}</label>
                            <select id="checkout-city" name="city" required>
                                <option value="" disabled selected>{{ config('texts.cart_form_city_placeholder') }}</option>
                                @php
                                    // Kiểm tra và đảm bảo $cities là collection và có dữ liệu
                                    $hasCities = false;
                                    $citiesCount = 0;
                                    
                                    if (isset($cities) && !empty($cities)) {
                                        if ($cities instanceof \Illuminate\Support\Collection) {
                                            $citiesCount = $cities->count();
                                            $hasCities = $citiesCount > 0;
                                        } elseif (is_array($cities)) {
                                            $citiesCount = count($cities);
                                            $hasCities = $citiesCount > 0;
                                        }
                                    }
                                    
                                    // Debug: Log để kiểm tra
                                    if (config('app.debug')) {
                                        \Log::info('Shopping Cart View - Cities check', [
                                            'isset_cities' => isset($cities),
                                            'cities_type' => gettype($cities),
                                            'is_collection' => $cities instanceof \Illuminate\Support\Collection,
                                            'cities_count' => $citiesCount,
                                            'has_cities' => $hasCities,
                                            'first_city' => $hasCities ? (is_object($cities->first()) ? $cities->first()->name : $cities[0]['name'] ?? null) : null
                                        ]);
                                    }
                                @endphp
                                @if($hasCities)
                                    @foreach($cities as $city)
                                        <option value="{{ is_object($city) ? ($city->id ?? '') : ($city['id'] ?? '') }}">
                                            {{ is_object($city) ? ($city->name ?? '') : ($city['name'] ?? '') }}
                                        </option>
                                    @endforeach
                                @else
                                    {{-- Fallback cities nếu không có dữ liệu từ database --}}
                                    <option value="1">TP. Hồ Chí Minh</option>
                                    <option value="2">Hà Nội</option>
                                    <option value="3">Đà Nẵng</option>
                                    <option value="4">Bà Rịa - Vũng Tàu</option>
                                    <option value="5">Khác</option>
                                @endif
                            </select>
                        </div>
                        <div class="checkout-form-group">
                            <label for="checkout-district">{{ config('texts.cart_form_district') }}</label>
                            <select id="checkout-district" name="district" required disabled>
                                <option value="" disabled selected>{{ config('texts.cart_form_district_placeholder') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="checkout-form-group checkout-form-group--full">
                        <label for="checkout-address">{{ config('texts.cart_form_address') }}</label>
                        <textarea id="checkout-address" name="address" placeholder="{{ config('texts.cart_form_address_placeholder') }}" required></textarea>
                    </div>
                    <div class="checkout-form-group checkout-form-group--full">
                        <label for="checkout-note">{{ config('texts.cart_form_note') }}</label>
                        <textarea id="checkout-note" name="note" placeholder="{{ config('texts.cart_form_note_placeholder') }}"></textarea>
                    </div>
                    <div class="checkout-form-group checkout-form-group--full">
                        <label>{{ config('texts.cart_payment_method_label') }}</label>
                        <div class="checkout-payment-options" id="checkout-payment-options">
                            <label class="checkout-payment-option">
                                <input type="radio" name="payment-method" value="bank" checked>
                                <div>
                                    <span>{{ config('texts.cart_payment_bank') }}</span>
                                    <span>{{ config('texts.cart_payment_bank_desc') }}</span>
                                </div>
                            </label>
                            <label class="checkout-payment-option">
                                <input type="radio" name="payment-method" value="cod">
                                <div>
                                    <span>{{ config('texts.cart_payment_cod') }}</span>
                                    <span>{{ config('texts.cart_payment_cod_desc') }}</span>
                                </div>
                            </label>
                            <label class="checkout-payment-option">
                                <input type="radio" name="payment-method" value="store">
                                <div>
                                    <span>{{ config('texts.cart_payment_store') }}</span>
                                    <span>{{ config('texts.cart_payment_store_desc') }}</span>
                                </div>
                            </label>
                        </div>
                        <div id="checkout-payment-note" class="checkout-payment-note" role="status"></div>
                    </div>
                    <div class="checkout-modal__actions">
                        <button type="button" class="secondary" id="checkout-modal-cancel">{{ config('texts.cart_form_cancel') }}</button>
                        <button type="submit" class="primary">{{ config('texts.cart_form_submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

</main>

<style>
    .order-success-message {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        padding: 3rem 2rem;
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .order-success-message.hidden {
        display: none;
    }
    
    .order-success-content {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .order-success-icon {
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: center;
    }
    
    .order-success-title {
        font-size: 1.75rem;
        font-weight: bold;
        color: #ed1c24;
        margin-bottom: 1.5rem;
        text-transform: uppercase;
    }
    
    .order-success-text {
        text-align: left;
        color: #333;
        line-height: 1.8;
        margin-bottom: 2rem;
    }
    
    .order-success-text p {
        margin-bottom: 1rem;
    }
    
    .order-success-text p:last-child {
        margin-bottom: 0;
    }
    
    .order-success-text strong {
        color: #000;
        font-weight: 600;
    }
    
    .order-success-actions {
        margin-top: 2rem;
    }
    
    .order-success-actions .btn-primary {
        display: inline-block;
        background-color: #ed1c24;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        font-weight: 600;
        text-decoration: none;
        transition: background-color 0.2s;
    }
    
    .order-success-actions .btn-primary:hover {
        background-color: #c41e3a;
    }

    /* Toast Notification Styles - Góc dưới bên phải */
    .toast-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 10000;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        pointer-events: none;
    }

    .toast {
        min-width: 320px;
        max-width: 450px;
        animation: slideInUp 0.3s ease-out;
        pointer-events: auto;
    }

    @keyframes slideInUp {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes slideOutDown {
        from {
            transform: translateY(0);
            opacity: 1;
        }
        to {
            transform: translateY(100%);
            opacity: 0;
        }
    }

    .toast.hiding {
        animation: slideOutDown 0.3s ease-out forwards;
    }

    .toast-content {
        background: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        padding: 1rem 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .toast.toast-success .toast-content {
        border-left: 4px solid #10b981;
    }

    .toast.toast-error .toast-content {
        border-left: 4px solid #ef4444;
    }

    .toast.toast-info .toast-content {
        border-left: 4px solid #3b82f6;
    }

    .toast.toast-warning .toast-content {
        border-left: 4px solid #f59e0b;
    }

    .toast-icon {
        flex-shrink: 0;
        margin-top: 0.125rem;
    }

    .toast.toast-success .toast-icon {
        color: #10b981;
    }

    .toast.toast-error .toast-icon {
        color: #ef4444;
    }

    .toast.toast-info .toast-icon {
        color: #3b82f6;
    }

    .toast.toast-warning .toast-icon {
        color: #f59e0b;
    }

    .toast-message {
        flex: 1;
        color: #1f2937;
        font-size: 0.875rem;
        line-height: 1.5;
        word-wrap: break-word;
    }

    .toast-close {
        flex-shrink: 0;
        background: transparent;
        border: none;
        color: #6b7280;
        cursor: pointer;
        padding: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s;
        margin-top: -0.25rem;
    }

    .toast-close:hover {
        color: #1f2937;
    }

    @media (max-width: 640px) {
        .toast-container {
            bottom: 10px;
            right: 10px;
            left: 10px;
        }

        .toast {
            min-width: auto;
            max-width: none;
        }
    }
</style>

@endsection

@push('scripts')
<script>
    // Shopping Cart Page Logic
    document.addEventListener('DOMContentLoaded', function () {
        const orderSection = document.getElementById('order-section');
        const orderList = document.getElementById('order-list');
        const checkoutModal = document.getElementById('checkout-modal');
        const checkoutOverlay = document.getElementById('checkout-modal-overlay');
        const checkoutClose = document.getElementById('checkout-modal-close');
        const checkoutCancel = document.getElementById('checkout-modal-cancel');
        const checkoutForm = document.getElementById('checkout-form');
        const paymentNoteEl = document.getElementById('checkout-payment-note');
        const paymentOptionsEl = document.getElementById('checkout-payment-options');
        const orderEmptyState = document.getElementById('order-empty-state');
        const orderReceipt = document.getElementById('order-receipt');
        const orderSuccessMessage = document.getElementById('order-success-message');
        const toastContainer = document.getElementById('toast-container');

        let cart = [];

        // Function to show toast notification (success, error, info, warning)
        function showToast(message, type = 'info') {
            if (!toastContainer) return;

            // Tạo toast element
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            
            // Icon theo type
            let iconSvg = '';
            switch(type) {
                case 'success':
                    iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                    break;
                case 'error':
                    iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                    break;
                case 'warning':
                    iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
                    break;
                default: // info
                    iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            }

            toast.innerHTML = `
                <div class="toast-content">
                    <div class="toast-icon">${iconSvg}</div>
                    <div class="toast-message">${message}</div>
                    <button type="button" class="toast-close" aria-label="Đóng">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;

            // Thêm vào container
            toastContainer.appendChild(toast);

            // Xử lý nút đóng
            const closeBtn = toast.querySelector('.toast-close');
            const hideToast = () => {
                toast.classList.add('hiding');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            };

            if (closeBtn) {
                closeBtn.addEventListener('click', hideToast);
            }

            // Tự động ẩn sau 5 giây (hoặc 3 giây cho success)
            const autoHideDelay = type === 'success' ? 3000 : 5000;
            setTimeout(hideToast, autoHideDelay);
        }

        // Alias functions for convenience
        function showErrorNotification(message) {
            showToast(message, 'error');
        }

        function showSuccessNotification(message) {
            showToast(message, 'success');
        }

        const paymentNotes = {
            bank: `<strong>{{ config('texts.cart_payment_bank_note') }}</strong><br>{{ config('texts.cart_payment_bank_note_detail') }}<br>– {{ config('texts.cart_payment_bank_name') }}<br>– {{ config('texts.cart_payment_bank_account') }} <strong>{{ config('texts.cart_payment_bank_account_value') }}</strong><br>– {{ config('texts.cart_payment_bank_owner') }} <strong>{{ config('texts.cart_payment_bank_owner_value') }}</strong>`,
            cod: `<strong>{{ config('texts.cart_payment_cod_note') }}</strong><br>{{ config('texts.cart_payment_cod_note_detail') }}`,
            store: `<strong>{{ config('texts.cart_payment_store_note') }}</strong><br>– {{ config('texts.cart_payment_store_note_detail_1') }}<br>– {{ config('texts.cart_payment_store_note_detail_2') }}<br>– {{ config('texts.cart_payment_store_note_detail_3') }}`
        };

        function updatePaymentNote() {
            if (!checkoutForm || !paymentNoteEl) return;
            const selected = checkoutForm.querySelector('input[name="payment-method"]:checked');
            const value = selected ? selected.value : 'bank';
            paymentNoteEl.innerHTML = paymentNotes[value] || '';
            if (paymentOptionsEl) {
                paymentOptionsEl.querySelectorAll('.checkout-payment-option').forEach(option => {
                    const input = option.querySelector('input[name="payment-method"]');
                    option.classList.toggle('active', input && input.checked);
                });
            }
        }

        function openCheckoutModal() {
            if (!checkoutModal) return;
            updatePaymentNote();
            checkoutModal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Reset dropdown quận/huyện khi mở modal
            const districtSelect = document.getElementById('checkout-district');
            if (districtSelect) {
                districtSelect.innerHTML = '<option value="" disabled selected>{{ config('texts.cart_form_district_placeholder') }}</option>';
                districtSelect.disabled = true;
            }
            
            const nameInput = checkoutForm?.querySelector('#checkout-name');
            if (nameInput) {
                setTimeout(() => nameInput.focus(), 100);
            }
        }

        function closeCheckoutModal() {
            if (!checkoutModal) return;
            checkoutModal.classList.remove('active');
            document.body.style.overflow = '';
            
            // Reset dropdown quận/huyện khi đóng modal
            const districtSelect = document.getElementById('checkout-district');
            if (districtSelect) {
                districtSelect.innerHTML = '<option value="" disabled selected>{{ config('texts.cart_form_district_placeholder') }}</option>';
                districtSelect.disabled = true;
            }
        }

        if (checkoutOverlay) {
            checkoutOverlay.addEventListener('click', closeCheckoutModal);
        }

        if (checkoutClose) {
            checkoutClose.addEventListener('click', closeCheckoutModal);
        }

        if (checkoutCancel) {
            checkoutCancel.addEventListener('click', closeCheckoutModal);
        }

        // Dữ liệu quận/huyện từ server
        const rawDistrictsData = @json($districts ?? []);
        console.log('Raw districts data from server:', rawDistrictsData);
        console.log('Raw districts data type:', typeof rawDistrictsData);
        console.log('Raw districts data is array?', Array.isArray(rawDistrictsData));
        
        let districtsData = rawDistrictsData;
        if (districtsData && typeof districtsData === 'object' && !Array.isArray(districtsData)) {
            console.log('Converting object to array...');
            districtsData = Object.keys(districtsData).map(key => districtsData[key]);
        }
        districtsData = Array.isArray(districtsData) ? districtsData : [];
        
        // Dữ liệu thành phố để debug
        const citiesData = @json($cities ?? []);
        console.log('Cities data loaded:', citiesData.length, 'cities');
        console.log('Raw cities data:', citiesData);
        if (citiesData.length > 0) {
            console.log('Sample cities:', citiesData.slice(0, 3).map(c => ({ id: c.id, name: c.name, parent_id: c.parent_id })));
        }
        
        console.log('Districts data loaded:', districtsData.length, 'districts');
        console.log('Districts data:', districtsData);
        if (districtsData.length > 0) {
            console.log('Sample district:', districtsData[0]);
            // Lấy danh sách unique parent_ids trong districts
            const uniqueParentIds = [...new Set(districtsData.map(d => parseInt(d.parent_id)).filter(id => !isNaN(id)))].sort((a, b) => a - b);
            console.log('Unique parent_ids in districts (first 20):', uniqueParentIds.slice(0, 20));
            
            // Lấy danh sách city IDs từ citiesData
            const cityIds = citiesData.map(c => parseInt(c.id)).filter(id => !isNaN(id));
            console.log('City IDs from cities data:', cityIds);
            
            // Kiểm tra xem có city ID nào match với district parent_ids không
            const matchingIds = cityIds.filter(cityId => uniqueParentIds.includes(cityId));
            console.log('Matching city IDs in district parent_ids:', matchingIds);
            
            // Kiểm tra xem có district parent_id nào match với city IDs không
            const matchingParentIds = uniqueParentIds.filter(parentId => cityIds.includes(parentId));
            console.log('Matching district parent_ids in city IDs:', matchingParentIds);
        } else {
            console.warn('Districts data is empty');
            console.warn('Raw districts data was:', rawDistrictsData);
        }
        
        // Hàm xử lý load quận/huyện khi chọn thành phố
        function handleCityChange(cityId) {
            console.log('City changed, cityId:', cityId);
            
            const districtSelect = document.getElementById('checkout-district');
            if (!districtSelect) {
                console.error('District select element not found');
                return;
            }
            
            // Reset dropdown quận/huyện
            districtSelect.innerHTML = '<option value="" disabled selected>{{ config('texts.cart_form_district_placeholder') }}</option>';
            districtSelect.disabled = true;
            
            if (!cityId || isNaN(cityId)) {
                console.log('Invalid cityId:', cityId);
                return;
            }
            
            // Kiểm tra districtsData có tồn tại và là array không
            if (!districtsData || !Array.isArray(districtsData)) {
                console.error('districtsData is not available or not an array', districtsData);
                districtSelect.innerHTML = '<option value="" disabled selected>{{ config('texts.cart_district_no_data') }}</option>';
                return;
            }
            
            // Kiểm tra nếu districtsData rỗng
            if (districtsData.length === 0) {
                console.warn('districtsData is empty, cannot filter districts');
                districtSelect.innerHTML = '<option value="" disabled selected>{{ config('texts.cart_district_no_data') }}</option>';
                return;
            }
            
            console.log('Filtering districts for cityId:', cityId, 'type:', typeof cityId);
            console.log('Total districts available:', districtsData.length);
            
            // Debug: Kiểm tra sample districts để xem parent_id có giá trị gì
            const sampleDistricts = districtsData.slice(0, 5);
            console.log('Sample districts parent_ids:', sampleDistricts.map(d => ({ id: d.id, name: d.name, parent_id: d.parent_id })));
            
            // Lấy danh sách city IDs từ citiesData
            const cityIds = citiesData.map(c => parseInt(c.id)).filter(id => !isNaN(id));
            const cityIdInt = parseInt(cityId);
            
            // Tạo mapping: city ID -> index trong mảng cities
            // Districts có parent_id = 1, 2, 3... tương ứng với city index 0, 1, 2...
            const cityIndexMap = new Map();
            citiesData.forEach((city, index) => {
                const cityId = parseInt(city.id);
                if (!isNaN(cityId)) {
                    cityIndexMap.set(cityId, index + 1); // parent_id bắt đầu từ 1
                }
            });
            
            // Lấy parent_id tương ứng với cityId được chọn
            const expectedParentId = cityIndexMap.get(cityIdInt);
            console.log('City ID:', cityIdInt, '-> Expected parent_id:', expectedParentId);
            
            // Filter quận/huyện theo parent_id
            // Nếu cityId match trực tiếp với parent_id, dùng cityId
            // Nếu không, dùng mapping dựa trên index
            const filteredDistricts = districtsData.filter(district => {
                if (!district || district.parent_id === undefined || district.parent_id === null) {
                    return false;
                }
                const districtParentId = parseInt(district.parent_id);
                
                // Thử match trực tiếp trước
                if (districtParentId === cityIdInt) {
                    return true;
                }
                
                // Nếu không match, dùng mapping dựa trên index
                if (expectedParentId && districtParentId === expectedParentId) {
                    return true;
                }
                
                return false;
            });
            
            console.log('Filtered districts count:', filteredDistricts.length);
            if (filteredDistricts.length === 0) {
                // Debug: Kiểm tra xem có districts nào có parent_id gần với cityId không
                const uniqueParentIds = [...new Set(districtsData.map(d => parseInt(d.parent_id)).filter(id => !isNaN(id)))].sort((a, b) => a - b);
                console.warn('No districts found for cityId:', cityId);
                console.log('Available parent_ids in districts (first 20):', uniqueParentIds.slice(0, 20));
                console.log('City IDs from cities data:', cityIds);
                console.log('Matching check - cityIdInt in uniqueParentIds?', cityIds.includes(cityIdInt));
                
                // Kiểm tra xem có districts nào có parent_id match với bất kỳ city ID nào không
                const districtsWithMatchingParentIds = districtsData.filter(d => {
                    const parentId = parseInt(d.parent_id);
                    return !isNaN(parentId) && cityIds.includes(parentId);
                });
                console.log('Districts with parent_ids matching any city ID:', districtsWithMatchingParentIds.length);
                if (districtsWithMatchingParentIds.length > 0) {
                    console.log('Sample districts with matching parent_ids:', districtsWithMatchingParentIds.slice(0, 3).map(d => ({
                        id: d.id,
                        name: d.name,
                        parent_id: d.parent_id
                    })));
                }
            }
            
            // Cập nhật dropdown quận/huyện
            if (filteredDistricts.length > 0) {
                filteredDistricts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.id || '';
                    option.textContent = district.name || '';
                    districtSelect.appendChild(option);
                });
                districtSelect.disabled = false;
                console.log('District dropdown updated with', filteredDistricts.length, 'options');
            } else {
                console.warn('No districts found for cityId:', cityId);
                districtSelect.innerHTML = '<option value="" disabled selected>{{ config('texts.cart_district_no_data') }}</option>';
            }
        }
        
        if (checkoutForm) {
            const paymentRadios = checkoutForm.querySelectorAll('input[name="payment-method"]');
            paymentRadios.forEach(radio => {
                radio.addEventListener('change', updatePaymentNote);
            });
            updatePaymentNote();

            // Sử dụng event delegation để xử lý change event của city select
            checkoutForm.addEventListener('change', function(e) {
                // Kiểm tra nếu là city select
                if (e.target && e.target.id === 'checkout-city') {
                    const cityId = parseInt(e.target.value);
                    handleCityChange(cityId);
                }
            });
            
            // Đảm bảo attach event listener trực tiếp nếu element đã tồn tại
            const citySelect = document.getElementById('checkout-city');
            if (citySelect) {
                citySelect.addEventListener('change', function() {
                    const cityId = parseInt(this.value);
                    handleCityChange(cityId);
                });
            }

            checkoutForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                
                // Kiểm tra giỏ hàng có sản phẩm không
                if (!cart || cart.length === 0) {
                    showErrorNotification('{{ config('texts.cart_empty_alert') }}');
                    return;
                }

                // Lấy dữ liệu từ form
                const formData = new FormData(checkoutForm);
                const formObject = {};
                formData.forEach((value, key) => {
                    formObject[key] = value;
                });

                // Chuẩn bị dữ liệu cart items - đơn giản, không filter quá nhiều
                const cartItems = cart.map(item => ({
                    id: parseInt(item.id || item.productId || 0) || 0,
                    name: item.name || '',
                    price: parseInt(item.price) || 0,
                    quantity: parseInt(item.quantity) || 1,
                    category: item.category || null,
                    sale_off: item.saleOff || item.sale_off || null,
                    color_id: item.colorId || item.color_id || null,
                    lensLabel: item.lensLabel || item.lens || null,
                    selectedOptions: item.selectedOptions || null,
                    selectedPriceSale: item.selectedPriceSale || null,
                    selectedDegreeRange: item.selectedDegreeRange || null,
                    unit: item.unit || null,
                    origin: item.origin || null,
                    brand: item.brand || null,
                    color: item.color || null,
                }));

                // Chuẩn bị dữ liệu gửi lên server - ĐẢM BẢO cart luôn được gửi
                const submitData = {
                    ...formObject,
                    cart: cartItems
                };

                // Disable submit button và hiển thị loading
                const submitButton = checkoutForm.querySelector('button[type="submit"]');
                const originalButtonText = submitButton ? submitButton.textContent : '';
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent = '{{ config('texts.cart_processing') }}';
                }

                try {
                    // Lấy CSRF token từ form
                    const csrfToken = checkoutForm.querySelector('input[name="_token"]')?.value || 
                                      document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                    // Gửi dữ liệu đến server
                    const response = await fetch('/checkout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(submitData)
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Hiển thị toast thông báo thành công
                        showSuccessNotification(result.message || 'Đơn hàng đã được tạo thành công!');
                        
                        // Xóa giỏ hàng sau khi đặt hàng thành công
                        localStorage.removeItem('cart');
                        cart = [];
                        renderOrderSection();
                        updateCartCount();

                        // Đóng modal
                        closeCheckoutModal();
                        
                        // Ẩn order section và hiển thị success message
                        if (orderSection) {
                            orderSection.classList.add('hidden');
                        }
                        if (orderSuccessMessage) {
                            orderSuccessMessage.classList.remove('hidden');
                            // Scroll to top để hiển thị success message
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                        
                        // Reset form
                        checkoutForm.reset();
                        updatePaymentNote();
                        
                        // Reset dropdown quận/huyện
                        const districtSelect = document.getElementById('checkout-district');
                        if (districtSelect) {
                            districtSelect.innerHTML = '<option value="" disabled selected>{{ config('texts.cart_form_district_placeholder') }}</option>';
                            districtSelect.disabled = true;
                        }
                    } else {
                        // Hiển thị lỗi bằng toast notification
                        showErrorNotification(result.message || '{{ config('texts.cart_error') }}');
                    }
                } catch (error) {
                    console.error('Checkout error:', error);
                    showErrorNotification('{{ config('texts.cart_error') }}');
                } finally {
                    // Enable lại submit button
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.textContent = originalButtonText;
                    }
                }
            });
        }

        const buildCartItemKey = (item) => {
            // Sử dụng selectedOptions nếu có (multi-select), nếu không thì dùng lens hoặc lensLabel
            const optionsKey = item.selectedOptions && item.selectedOptions.length > 0 
                ? item.selectedOptions.sort().join(',') 
                : (item.lens || item.lensLabel || '');
            // Thêm chiết suất và độ khúc xạ vào key để phân biệt các sản phẩm
            const priceSaleKey = item.selectedPriceSale || '';
            const degreeRangeKey = item.selectedDegreeRange || '';
            return [item.name, item.color || '', optionsKey, priceSaleKey, degreeRangeKey].join('||');
        };

        // Hàm chuyển đổi giá trị sang string hợp lệ
        const toSafeString = (value) => {
            if (value == null) return '';
            if (typeof value === 'object') return ''; // Không cho phép object
            return String(value);
        };

        // Hàm làm sạch cart item - chỉ giữ lại các trường cần thiết (không filter quá strict)
        function cleanCartItem(item) {
            if (!item || typeof item !== 'object') {
                return null;
            }
            
            // Xử lý brand đặc biệt - nếu là object thì lấy name hoặc alias
            let brandValue = item.brand;
            if (brandValue && typeof brandValue === 'object') {
                brandValue = brandValue.name || brandValue.alias || '';
            } else if (typeof brandValue === 'string') {
                // Nếu là JSON string, thử parse
                try {
                    const parsed = JSON.parse(brandValue);
                    if (parsed && typeof parsed === 'object') {
                        brandValue = parsed.name || parsed.alias || '';
                    }
                } catch (e) {
                    // Không phải JSON, giữ nguyên
                }
            }
            
            return {
                id: parseInt(item.id || item.productId || 0) || 0,
                productId: parseInt(item.productId || item.id || 0) || 0,
                name: toSafeString(item.name || ''),
                brand: toSafeString(brandValue),
                price: parseInt(item.price) || 0,
                image: toSafeString(item.image),
                color: toSafeString(item.color),
                lens: toSafeString(item.lens),
                lensLabel: toSafeString(item.lensLabel),
                selectedOptions: Array.isArray(item.selectedOptions) 
                    ? item.selectedOptions.map(opt => toSafeString(opt)).filter(opt => opt !== '')
                    : [],
                selectedPriceSale: toSafeString(item.selectedPriceSale || ''),
                selectedDegreeRange: toSafeString(item.selectedDegreeRange || ''),
                unit: toSafeString(item.unit || ''),
                origin: toSafeString(item.origin || ''),
                quantity: parseInt(item.quantity) || 1
            };
        }

        // Load giỏ hàng từ localStorage
        function loadCart() {
            const savedCart = localStorage.getItem('cart');
            if (savedCart) {
                try {
                    const parsedCart = JSON.parse(savedCart);
                    // Làm sạch tất cả items để loại bỏ các trường không mong muốn (không filter quá strict)
                    const cleanedCart = Array.isArray(parsedCart) 
                        ? parsedCart.map(cleanCartItem).filter(item => item !== null) 
                        : [];
                    
                    cart = cleanedCart;
                    
                    // Lưu lại cart đã được làm sạch (ngay cả khi rỗng để xóa dữ liệu cũ)
                    saveCart();
                    renderOrderSection();
                    updateCartCount(); // Cập nhật count trên header khi load trang
                    
                    // Ẩn success message nếu có sản phẩm trong giỏ hàng
                    if (cart.length > 0 && orderSuccessMessage) {
                        orderSuccessMessage.classList.add('hidden');
                        if (orderSection) {
                            orderSection.classList.remove('hidden');
                        }
                    }
                } catch (e) {
                    console.error('Error loading cart:', e);
                    cart = [];
                    localStorage.removeItem('cart');
                    updateCartCount();
                }
            } else {
                updateCartCount(); // Cập nhật count ngay cả khi cart rỗng
            }
        }

        // Lưu giỏ hàng vào localStorage
        function saveCart() {
            localStorage.setItem('cart', JSON.stringify(cart));
        }

        // Cập nhật cart count trên header
        function updateCartCount() {
            const cartCount = document.getElementById('cart-count');
            const cartCountDesktop = document.getElementById('cart-count-desktop');
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);

            if (cartCount) {
                cartCount.textContent = totalItems;
                if (totalItems > 0) {
                    cartCount.classList.remove('hidden');
                } else {
                    cartCount.classList.add('hidden');
                }
            }
            
            if (cartCountDesktop) {
                cartCountDesktop.textContent = totalItems;
                if (totalItems > 0) {
                    cartCountDesktop.classList.remove('hidden');
                } else {
                    cartCountDesktop.classList.add('hidden');
                }
            }
        }

        // Xóa sản phẩm khỏi giỏ hàng
        function removeFromCart(itemKey) {
            cart = cart.filter(item => buildCartItemKey(item) !== itemKey);
            saveCart();
            renderOrderSection();
            updateCartCount(); // Cập nhật count trên header
        }

        // Cập nhật số lượng sản phẩm
        function updateQuantity(itemKey, newQuantity) {
            const item = cart.find(item => buildCartItemKey(item) === itemKey);
            if (item) {
                // Giá trị tối thiểu là 1
                if (newQuantity < 1) {
                    newQuantity = 1;
                }
                item.quantity = newQuantity;
                saveCart();
                renderOrderSection();
                updateCartCount(); // Cập nhật count trên header
            }
        }

        function renderOrderSection() {
            if (!orderSection) return;

            const hasItems = cart.length > 0;
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

            if (orderEmptyState) {
                orderEmptyState.classList.toggle('hidden', hasItems);
            }
            if (orderList) {
                orderList.classList.toggle('hidden', !hasItems);
            }
            if (orderReceipt) {
                orderReceipt.classList.toggle('hidden', !hasItems);
            }
            if (!hasItems) {
                if (orderList) orderList.innerHTML = '';
                if (orderReceipt) orderReceipt.innerHTML = '';
                return;
            }

            const toCurrency = (value) => (value || 0).toLocaleString('vi-VN') + ' VNĐ';

            if (orderList) {
                orderList.innerHTML = cart.map((item, index) => {
                    const itemKey = encodeURIComponent(buildCartItemKey(item));

                    // Escape HTML để tránh hiển thị raw HTML/JSON
                    const escapeHtml = (text) => {
                        if (!text) return '';
                        const div = document.createElement('div');
                        div.textContent = text;
                        return div.innerHTML;
                    };

                    // Xây dựng danh sách chi tiết sản phẩm
                    const productDetails = [];
                    
                    // Thương hiệu
                    const brandValue = item.brand ? String(item.brand).trim() : '';
                    if (brandValue !== '') {
                        productDetails.push({
                            icon: '🏷️',
                            label: 'Thương hiệu',
                            value: brandValue
                        });
                    }
                    
                    // Đơn vị - luôn hiển thị nếu có giá trị
                    const unitValue = item.unit ? String(item.unit).trim() : '';
                    if (unitValue !== '') {
                        productDetails.push({
                            icon: '📦',
                            label: 'Đơn vị',
                            value: unitValue
                        });
                    }
                    
                    // Màu gọng
                    if (item.color && item.color.trim() !== '') {
                        productDetails.push({
                            icon: '🎨',
                            label: 'Màu gọng',
                            value: item.color
                        });
                    }
                    
                    // Chiếc xuất (nếu có)
                    if (item.origin && item.origin.trim() !== '') {
                        productDetails.push({
                            icon: '🌍',
                            label: 'Chiếc xuất',
                            value: item.origin
                        });
                    }
                    
                    // Độ khúc xạ (Chiết suất)
                    if (item.selectedPriceSale && item.selectedPriceSale.trim() !== '') {
                        productDetails.push({
                            icon: '🔍',
                            label: 'Chiết suất',
                            value: item.selectedPriceSale
                        });
                    }
                    
                    // Độ khúc xạ (nếu có)
                    if (item.selectedDegreeRange && item.selectedDegreeRange.trim() !== '') {
                        productDetails.push({
                            icon: '👓',
                            label: 'Độ khúc xạ',
                            value: item.selectedDegreeRange
                        });
                    }
                    
                    // Gói tròng kính
                    if (item.selectedOptions && Array.isArray(item.selectedOptions) && item.selectedOptions.length > 0) {
                        const lensOptions = item.selectedOptions
                            .filter(opt => opt != null && typeof opt !== 'object' && String(opt).trim() !== '')
                            .map(opt => escapeHtml(String(opt)))
                            .join(', ');
                        if (lensOptions) {
                            productDetails.push({
                                icon: '🔬',
                                label: 'Gói tròng kính',
                                value: lensOptions
                            });
                        }
                    } else if (item.lensLabel && item.lensLabel.trim() !== '') {
                        productDetails.push({
                            icon: '🔬',
                            label: 'Gói tròng kính',
                            value: item.lensLabel
                        });
                    } else if (item.lens && item.lens.trim() !== '') {
                        productDetails.push({
                            icon: '🔬',
                            label: 'Gói tròng kính',
                            value: item.lens
                        });
                    }

                    // Render chi tiết sản phẩm
                    const productDetailsHtml = productDetails.length > 0
                        ? `<div class="order-card__details">
                            <div class="order-card__details-title">
                                <svg class="order-card__details-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Thông tin sản phẩm</span>
                            </div>
                            <div class="order-card__details-list">
                                ${productDetails.map(detail => `
                                    <div class="order-card__detail-item">
                                        <span class="order-card__detail-icon">${detail.icon}</span>
                                        <div class="order-card__detail-content">
                                            <span class="order-card__detail-label">${escapeHtml(detail.label)}</span>
                                            <span class="order-card__detail-value">${escapeHtml(detail.value)}</span>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                           </div>`
                        : `<div class="order-card__details order-card__details--empty">
                            <p>{{ config('texts.cart_no_options') }}</p>
                           </div>`;

                    const brandLine = item.brand ? `<p class="order-card__brand">${escapeHtml(item.brand)}</p>` : '';
                    const indexLabel = `#${String(index + 1).padStart(2, '0')}`;

                    return `
                        <article class="order-card" data-item-key="${itemKey}">
                            <div class="order-card__header">
                                <img src="${escapeHtml(item.image)}" alt="${escapeHtml(item.name)}">
                                <div class="order-card__title">
                                    <span class="order-card__index">${indexLabel}</span>
                                    <h3>${escapeHtml(item.name)}</h3>
                                    ${brandLine}
                                </div>
                            </div>
                            ${productDetailsHtml}
                            <div class="order-card__body">
                                <div class="order-card__price-block">
                                    <span>{{ config('texts.cart_unit_price') }}</span>
                                    <strong>${toCurrency(item.price)}</strong>
                                </div>
                                <div class="order-quantity" role="group" aria-label="Điều chỉnh số lượng">
                                    <button type="button" class="order-decrease" aria-label="{{ config('texts.cart_decrease') }}">-</button>
                                    <span>${item.quantity}</span>
                                    <button type="button" class="order-increase" aria-label="{{ config('texts.cart_increase') }}">+</button>
                                </div>
                            </div>
                            <div class="order-card__footer">
                                <div class="order-card__total">
                                    <span>{{ config('texts.cart_subtotal') }}</span>
                                    <strong>${toCurrency(item.price * item.quantity)}</strong>
                                </div>
                                <button type="button" class="order-remove-btn order-remove-btn--subtle">
                                    <span>✕</span> {{ config('texts.cart_remove') }}
                                </button>
                            </div>
                        </article>
                    `;
                }).join('');
            }

            if (orderReceipt) {
                const now = new Date();
                const receiptNumber = Math.random().toString(36).substring(2, 8).toUpperCase();
                const formatDateTime = (date) => {
                    return new Intl.DateTimeFormat('vi-VN', {
                        dateStyle: 'medium',
                        timeStyle: 'short'
                    }).format(date);
                };

                const shippingFee = 0;
                const discountValue = 0;
                const grandTotal = totalPrice + shippingFee - discountValue;

                const receiptItems = cart.map(item => {
                    // Escape HTML để tránh hiển thị raw HTML/JSON
                    const escapeHtml = (text) => {
                        if (!text) return '';
                        const div = document.createElement('div');
                        div.textContent = text;
                        return div.innerHTML;
                    };

                    // Xây dựng danh sách chi tiết sản phẩm đầy đủ
                    const productDetails = [];
                    
                    // Thương hiệu
                    if (item.brand && String(item.brand).trim() !== '') {
                        productDetails.push({
                            label: 'Thương hiệu',
                            value: String(item.brand).trim()
                        });
                    }
                    
                    // Đơn vị
                    if (item.unit && String(item.unit).trim() !== '') {
                        productDetails.push({
                            label: 'Đơn vị',
                            value: String(item.unit).trim()
                        });
                    }
                    
                    // Màu gọng
                    if (item.color && String(item.color).trim() !== '') {
                        productDetails.push({
                            label: 'Màu gọng',
                            value: String(item.color).trim()
                        });
                    }
                    
                    // Chiết suất
                    if (item.selectedPriceSale && String(item.selectedPriceSale).trim() !== '') {
                        productDetails.push({
                            label: 'Chiết suất',
                            value: String(item.selectedPriceSale).trim()
                        });
                    }
                    
                    // Độ khúc xạ
                    if (item.selectedDegreeRange && String(item.selectedDegreeRange).trim() !== '') {
                        productDetails.push({
                            label: 'Độ khúc xạ',
                            value: String(item.selectedDegreeRange).trim()
                        });
                    }
                    
                    // Gói tròng kính
                    if (item.selectedOptions && Array.isArray(item.selectedOptions) && item.selectedOptions.length > 0) {
                        const lensOptions = item.selectedOptions
                            .filter(opt => opt != null && typeof opt !== 'object' && String(opt).trim() !== '')
                            .map(opt => escapeHtml(String(opt).trim()))
                            .join(', ');
                        if (lensOptions) {
                            productDetails.push({
                                label: 'Gói tròng kính',
                                value: lensOptions
                            });
                        }
                    } else if (item.lensLabel && String(item.lensLabel).trim() !== '') {
                        productDetails.push({
                            label: 'Gói tròng kính',
                            value: String(item.lensLabel).trim()
                        });
                    } else if (item.lens && String(item.lens).trim() !== '') {
                        productDetails.push({
                            label: 'Gói tròng kính',
                            value: String(item.lens).trim()
                        });
                    }

                    // Render meta lines
                    const metaLines = productDetails.length > 0
                        ? productDetails.map(detail => 
                            `<span><strong>${escapeHtml(detail.label)}:</strong> ${escapeHtml(detail.value)}</span>`
                          ).join('')
                        : '<span><strong>Thông tin:</strong> Chưa có</span>';

                    return `
                        <div class="receipt-product">
                            <div class="receipt-product__info">
                                <img src="${escapeHtml(item.image)}" alt="${escapeHtml(item.name)}">
                                <div>
                                    <h3>${escapeHtml(item.name)}</h3>
                                    <div class="receipt-product__meta">
                                        ${metaLines}
                                    </div>
                                </div>
                            </div>
                            <div class="text-sm text-gray-600">x${item.quantity}</div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">${toCurrency(item.price * item.quantity)}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ config('texts.cart_unit_price') }}: ${toCurrency(item.price)}</p>
                            </div>
                        </div>
                    `;
                }).join('');

                orderReceipt.innerHTML = `
                    <div class="order-receipt__header">
                        <div>
                            <h2>{{ config('texts.cart_receipt_title') }}</h2>
                            <p class="text-sm opacity-80">{{ config('texts.cart_receipt_desc') }}</p>
                        </div>
                        <div class="order-receipt__meta">
                            <span>🧾 <strong>{{ config('texts.cart_receipt_code') }}</strong> #${receiptNumber}</span>
                            <span>📅 <strong>{{ config('texts.cart_receipt_time') }}</strong> ${formatDateTime(now)}</span>
                            <span>👜 <strong>{{ config('texts.cart_receipt_total_items') }}</strong> ${totalItems}</span>
                        </div>
                    </div>
                    <div class="order-receipt__body">
                        <section class="receipt-product-list" aria-label="{{ config('texts.cart_receipt_product_list_label') }}">
                            <header>
                                <span>{{ config('texts.cart_receipt_product') }}</span>
                                <span>{{ config('texts.cart_receipt_quantity') }}</span>
                                <span>{{ config('texts.cart_receipt_amount') }}</span>
                            </header>
                            ${receiptItems}
                        </section>
                        <section class="receipt-totals" aria-label="{{ config('texts.cart_receipt_totals_label') }}">
                            <div class="receipt-total-row">
                                <span>{{ config('texts.cart_receipt_subtotal') }}</span>
                                <strong>${toCurrency(totalPrice)}</strong>
                            </div>
                            <div class="receipt-total-row">
                                <span>{{ config('texts.cart_receipt_shipping') }}</span>
                                <strong>${shippingFee === 0 ? '{{ config('texts.cart_receipt_shipping_free') }}' : toCurrency(shippingFee)}</strong>
                            </div>
                            <div class="receipt-total-row">
                                <span>{{ config('texts.cart_receipt_discount') }}</span>
                                <strong>${discountValue === 0 ? '{{ config('texts.cart_receipt_discount_none') }}' : '-' + toCurrency(discountValue)}</strong>
                            </div>
                            <div class="receipt-total-row grand">
                                <span>{{ config('texts.cart_receipt_total') }}</span>
                                <strong>${toCurrency(grandTotal)}</strong>
                            </div>
                        </section>
                        <div class="receipt-actions">
                            <button class="secondary" type="button" id="order-download-pdf">
                                <span class="receipt-download-icon">⬇</span>
                                <span class="receipt-download-text">{{ config('texts.cart_receipt_download_pdf') }}</span>
                            </button>
                            <button class="secondary" type="button" id="order-continue-shopping">
                                <span>↩</span> {{ config('texts.cart_receipt_continue') }}
                            </button>
                            <button class="primary" type="button" id="order-checkout">
                                <span>🛒</span> {{ config('texts.cart_receipt_checkout') }}
                            </button>
                        </div>
                    </div>
                `;

                const downloadButton = orderReceipt.querySelector('#order-download-pdf');
                if (downloadButton) {
                    downloadButton.addEventListener('click', () => {
                        showErrorNotification('{{ config('texts.cart_receipt_pdf_soon') }}');
                    });
                }

                const continueButton = orderReceipt.querySelector('#order-continue-shopping');
                if (continueButton) {
                    continueButton.addEventListener('click', () => {
                        if (checkoutModal?.classList.contains('active')) {
                            closeCheckoutModal();
                        }
                        window.location.href = '{{ route("home") }}';
                    });
                }

                const checkoutButton = orderReceipt.querySelector('#order-checkout');
                if (checkoutButton) {
                    checkoutButton.addEventListener('click', openCheckoutModal);
                }
            }
        }

        // Event delegation cho các buttons trong order section - chỉ gắn một lần
        if (orderSection) {
            orderSection.addEventListener('click', function (e) {
                const actionBtn = e.target.closest('.order-decrease, .order-increase, .order-remove-btn');
                if (!actionBtn) return;

                const itemContainer = e.target.closest('[data-item-key]');
                if (!itemContainer) return;

                const encodedKey = itemContainer.dataset.itemKey;
                if (!encodedKey) return;

                const itemKey = decodeURIComponent(encodedKey);
                const item = cart.find(i => buildCartItemKey(i) === itemKey);
                if (!item) return;

                // Đọc số lượng hiện tại từ cart để đảm bảo chính xác
                const currentQuantity = item.quantity;

                if (actionBtn.classList.contains('order-decrease')) {
                    // Giảm số lượng, giá trị tối thiểu là 1
                    if (currentQuantity > 1) {
                        updateQuantity(itemKey, currentQuantity - 1);
                    }
                } else if (actionBtn.classList.contains('order-increase')) {
                    // Tăng số lượng dần dần
                    updateQuantity(itemKey, currentQuantity + 1);
                } else if (actionBtn.classList.contains('order-remove-btn')) {
                    removeFromCart(itemKey);
                }
            });
        }

        // Load giỏ hàng khi trang tải
        loadCart();
    });
</script>
@endpush
