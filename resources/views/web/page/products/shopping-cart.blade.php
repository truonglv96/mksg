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
                <a href="{{ route('product.category') }}" class="btn-primary">{{ config('texts.cart_continue_shopping_btn') }}</a>
            </div>
        </div>
    </div>

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
                                @if(isset($cities) && $cities->count() > 0)
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}">
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="hcm">TP. Hồ Chí Minh</option>
                                    <option value="hn">Hà Nội</option>
                                    <option value="dn">Đà Nẵng</option>
                                    <option value="brvt">Bà Rịa - Vũng Tàu</option>
                                    <option value="other">Khác</option>
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

        let cart = [];

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
        const districtsData = @json($districts ?? []);
        
        if (checkoutForm) {
            const paymentRadios = checkoutForm.querySelectorAll('input[name="payment-method"]');
            paymentRadios.forEach(radio => {
                radio.addEventListener('change', updatePaymentNote);
            });
            updatePaymentNote();

            // Xử lý load quận/huyện khi chọn thành phố
            const citySelect = document.getElementById('checkout-city');
            const districtSelect = document.getElementById('checkout-district');
            
            if (citySelect && districtSelect) {
                citySelect.addEventListener('change', function() {
                    const cityId = parseInt(this.value);
                    
                    // Reset dropdown quận/huyện
                    districtSelect.innerHTML = '<option value="" disabled selected>{{ config('texts.cart_form_district_placeholder') }}</option>';
                    districtSelect.disabled = true;
                    
                    if (!cityId) {
                        return;
                    }
                    
                    // Filter quận/huyện theo parent_id (cityId)
                    const filteredDistricts = districtsData.filter(district => {
                        return parseInt(district.parent_id) === cityId;
                    });
                    
                    // Cập nhật dropdown quận/huyện
                    if (filteredDistricts.length > 0) {
                        filteredDistricts.forEach(district => {
                            const option = document.createElement('option');
                            option.value = district.id;
                            option.textContent = district.name;
                            districtSelect.appendChild(option);
                        });
                        districtSelect.disabled = false;
                    } else {
                        districtSelect.innerHTML = '<option value="" disabled selected>{{ config('texts.cart_district_no_data') }}</option>';
                    }
                });
            }

            checkoutForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                
                // Kiểm tra giỏ hàng có sản phẩm không
                if (cart.length === 0) {
                    alert('{{ config('texts.cart_empty_alert') }}');
                    return;
                }

                // Lấy dữ liệu từ form
                const formData = new FormData(checkoutForm);
                const formObject = {};
                formData.forEach((value, key) => {
                    formObject[key] = value;
                });

                // Chuẩn bị dữ liệu cart items
                const cartItems = cart.map(item => ({
                    id: item.id || item.productId || 0,
                    name: item.name || '',
                    price: parseInt(item.price) || 0,
                    quantity: parseInt(item.quantity) || 1,
                    category: item.category || null,
                    sale_off: item.saleOff || item.sale_off || null,
                    color_id: item.colorId || item.color_id || null,
                    lensLabel: item.lensLabel || null,
                    selectedOptions: item.selectedOptions || null,
                }));

                // Chuẩn bị dữ liệu gửi lên server
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
                        if (districtSelect) {
                            districtSelect.innerHTML = '<option value="" disabled selected>{{ config('texts.cart_form_district_placeholder') }}</option>';
                            districtSelect.disabled = true;
                        }
                    } else {
                        alert(result.message || '{{ config('texts.cart_error') }}');
                    }
                } catch (error) {
                    console.error('Checkout error:', error);
                    alert('{{ config('texts.cart_error') }}');
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
            return [item.name, item.color || '', optionsKey].join('||');
        };

        // Load giỏ hàng từ localStorage
        function loadCart() {
            const savedCart = localStorage.getItem('cart');
            if (savedCart) {
                cart = JSON.parse(savedCart);
                renderOrderSection();
                updateCartCount(); // Cập nhật count trên header khi load trang
                
                // Ẩn success message nếu có sản phẩm trong giỏ hàng
                if (cart.length > 0 && orderSuccessMessage) {
                    orderSuccessMessage.classList.add('hidden');
                    if (orderSection) {
                        orderSection.classList.remove('hidden');
                    }
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
                    const optionEntries = [];
                    if (item.color) {
                        optionEntries.push({ label: '{{ config('texts.cart_option_color') }}', value: item.color });
                    }
                    const lensValue = item.lensLabel || item.lens;
                    if (lensValue) {
                        optionEntries.push({ label: '{{ config('texts.cart_option_lens') }}', value: lensValue });
                    }
                    if (item.options && typeof item.options === 'object') {
                        Object.entries(item.options).forEach(([label, value]) => {
                            if (value) {
                                optionEntries.push({ label, value });
                            }
                        });
                    }

                    const optionBadges = optionEntries.length
                        ? `<div class="order-card__options">
                            ${optionEntries.map(opt => `<span class="order-card__option">${opt.label}: <span>${opt.value}</span></span>`).join('')}
                           </div>`
                        : `<p class="order-card__options--empty">{{ config('texts.cart_no_options') }}</p>`;

                    const brandLine = item.brand ? `<p class="order-card__brand">${item.brand}</p>` : '';
                    const indexLabel = `#${String(index + 1).padStart(2, '0')}`;

                    return `
                        <article class="order-card" data-item-key="${itemKey}">
                            <div class="order-card__header">
                                <img src="${item.image}" alt="${item.name}">
                                <div class="order-card__title">
                                    <span class="order-card__index">${indexLabel}</span>
                                    <h3>${item.name}</h3>
                                    ${brandLine}
                                </div>
                            </div>
                            ${optionBadges}
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
                    const optionEntries = [];
                    if (item.color) {
                        optionEntries.push({ label: '{{ config('texts.cart_option_color') }}', value: item.color });
                    }
                    const lensValue = item.lensLabel || item.lens;
                    if (lensValue) {
                        optionEntries.push({ label: '{{ config('texts.cart_option_lens') }}', value: lensValue });
                    }
                    if (item.options && typeof item.options === 'object') {
                        Object.entries(item.options).forEach(([label, value]) => {
                            if (value) {
                                optionEntries.push({ label, value });
                            }
                        });
                    }

                    const metaLines = [
                        item.brand ? `<span><strong>{{ config('texts.cart_brand') }}</strong> ${item.brand}</span>` : null,
                        ...optionEntries.map(opt => `<span><strong>${opt.label}:</strong> ${opt.value}</span>`)
                    ].filter(Boolean).join('');

                    return `
                        <div class="receipt-product">
                            <div class="receipt-product__info">
                                <img src="${item.image}" alt="${item.name}">
                                <div>
                                    <h3>${item.name}</h3>
                                    <div class="receipt-product__meta">
                                        ${metaLines || '<span><strong>{{ config('texts.cart_option_label') }}</strong> {{ config('texts.cart_option_none') }}</span>'}
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
                        alert('{{ config('texts.cart_receipt_pdf_soon') }}');
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
