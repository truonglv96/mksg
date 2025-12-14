@extends('web.master')

@section('title', $title ?? 'Giỏ Hàng - Mắt Kính Sài Gòn')

@section('content')
<main class="container mx-auto px-4 py-8">

    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-500 flex items-center gap-1 mb-6 overflow-x-auto whitespace-nowrap"
        aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-red-600 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 9.75L12 4l9 5.75V20a1 1 0 01-1 1h-5.5a0.5 0.5 0 01-0.5-0.5V15a1 1 0 00-1-1h-4a1 1 0 00-1 1v5.5a0.5 0.5 0 01-0.5 0.5H4a1 1 0 01-1-1V9.75z" />
            </svg>
            Trang chủ
        </a>
        <span>/</span>
        <span class="text-gray-700 font-medium">Giỏ hàng</span>
    </nav>

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
                <a href="{{ route('product.category') }}" class="btn-primary">Tiếp tục mua sắm</a>
            </div>
        </div>
    </div>

    <section id="order-section" class="order-section">
        <div class="order-header">
            <h1>Thông tin đơn hàng</h1>
            <p>Quản lý các sản phẩm đã chọn, cập nhật số lượng hoặc loại bỏ sản phẩm trước khi thanh toán.</p>
        </div>

        <div id="order-empty-state" class="order-empty-state">
            <p>Giỏ hàng của bạn đang trống. Hãy tiếp tục mua sắm để thêm sản phẩm vào giỏ.</p>
        </div>

        <div id="order-list" class="order-list hidden"></div>

        <div id="order-receipt" class="order-receipt hidden" aria-live="polite"></div>

        <div id="checkout-modal" class="checkout-modal" aria-modal="true" role="dialog" aria-labelledby="checkout-modal-title">
            <div class="checkout-modal__overlay" id="checkout-modal-overlay"></div>
            <div class="checkout-modal__card">
                <div class="checkout-modal__header">
                    <div>
                        <h2 class="checkout-modal__title" id="checkout-modal-title">Thông tin giao hàng</h2>
                        <p class="checkout-modal__subtitle">Vui lòng nhập chính xác thông tin để chúng tôi giao hàng nhanh chóng.</p>
                    </div>
                    <button type="button" class="checkout-modal__close" id="checkout-modal-close" aria-label="Đóng">&times;</button>
                </div>
                <form id="checkout-form" class="checkout-modal__form">
                    @csrf
                    <div class="checkout-form-grid">
                        <div class="checkout-form-group">
                            <label for="checkout-name">Họ và tên</label>
                            <input type="text" id="checkout-name" name="name" placeholder="Nguyễn Văn A" required>
                        </div>
                        <div class="checkout-form-group">
                            <label for="checkout-gender">Giới tính</label>
                            <select id="checkout-gender" name="gender" required>
                                <option value="" disabled selected>Chọn giới tính</option>
                                <option value="nam">Nam</option>
                                <option value="nu">Nữ</option>
                                <option value="khac">Khác</option>
                            </select>
                        </div>
                    </div>
                    <div class="checkout-form-grid">
                        <div class="checkout-form-group">
                            <label for="checkout-phone">Số điện thoại</label>
                            <input type="tel" id="checkout-phone" name="phone" placeholder="0909 999 999" required>
                        </div>
                        <div class="checkout-form-group">
                            <label for="checkout-email">Email</label>
                            <input type="email" id="checkout-email" name="email" placeholder="email@domain.com">
                        </div>
                    </div>
                    <div class="checkout-form-grid">
                        <div class="checkout-form-group">
                            <label for="checkout-city">Thành phố</label>
                            <select id="checkout-city" name="city" required>
                                <option value="" disabled selected>Chọn thành phố</option>
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
                            <label for="checkout-district">Xã / Quận</label>
                            <select id="checkout-district" name="district" required disabled>
                                <option value="" disabled selected>Chọn quận / huyện</option>
                            </select>
                        </div>
                    </div>
                    <div class="checkout-form-group checkout-form-group--full">
                        <label for="checkout-address">Địa chỉ liên hệ</label>
                        <textarea id="checkout-address" name="address" placeholder="Số nhà, tên đường, phường/xã..." required></textarea>
                    </div>
                    <div class="checkout-form-group checkout-form-group--full">
                        <label for="checkout-note">Ghi chú cho đơn hàng</label>
                        <textarea id="checkout-note" name="note" placeholder="Ví dụ: giao giờ hành chính, gọi trước khi giao..."></textarea>
                    </div>
                    <div class="checkout-form-group checkout-form-group--full">
                        <label>Hình thức thanh toán</label>
                        <div class="checkout-payment-options" id="checkout-payment-options">
                            <label class="checkout-payment-option">
                                <input type="radio" name="payment-method" value="bank" checked>
                                <div>
                                    <span>Chuyển khoản ngân hàng</span>
                                    <span>Thanh toán nhanh qua Vietcombank.</span>
                                </div>
                            </label>
                            <label class="checkout-payment-option">
                                <input type="radio" name="payment-method" value="cod">
                                <div>
                                    <span>Thanh toán khi nhận hàng (COD)</span>
                                    <span>Giao hàng và thu tiền tận nơi.</span>
                                </div>
                            </label>
                            <label class="checkout-payment-option">
                                <input type="radio" name="payment-method" value="store">
                                <div>
                                    <span>Đặt hàng và thanh toán tại shop</span>
                                    <span>Đến showroom gần nhất để thanh toán.</span>
                                </div>
                            </label>
                        </div>
                        <div id="checkout-payment-note" class="checkout-payment-note" role="status"></div>
                    </div>
                    <div class="checkout-modal__actions">
                        <button type="button" class="secondary" id="checkout-modal-cancel">Hủy</button>
                        <button type="submit" class="primary">Xác nhận đặt hàng</button>
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
            bank: `<strong>Chuyển khoản ngân hàng:</strong><br>Quý khách chuyển khoản vui lòng để lại SĐT trong phần ghi chú để bộ phận kế toán hỗ trợ nhanh nhất.<br>– Ngân hàng Vietcombank<br>– Số tài khoản: <strong>8888888.301</strong><br>– Tên chủ TK: <strong>Vũ Thị Hảo</strong>`,
            cod: `<strong>Thanh toán khi nhận hàng (COD):</strong><br>Miễn phí giao hàng COD cho hóa đơn trên 500.000đ. Phí giao hàng đơn dưới 500k sẽ được thông báo khi nhân viên xác nhận đơn hàng.`,
            store: `<strong>Đặt hàng và thanh toán tại shop:</strong><br>– 301B Điện Biên Phủ, Quận 3<br>– 245C Xô Viết Nghệ Tĩnh, Quận Bình Thạnh<br>– 90 Nguyễn Hữu Thọ, Bà Rịa`
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
                districtSelect.innerHTML = '<option value="" disabled selected>Chọn quận / huyện</option>';
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
                districtSelect.innerHTML = '<option value="" disabled selected>Chọn quận / huyện</option>';
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
                    districtSelect.innerHTML = '<option value="" disabled selected>Chọn quận / huyện</option>';
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
                        districtSelect.innerHTML = '<option value="" disabled selected>Không có dữ liệu</option>';
                    }
                });
            }

            checkoutForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                
                // Kiểm tra giỏ hàng có sản phẩm không
                if (cart.length === 0) {
                    alert('Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm trước khi thanh toán.');
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
                    submitButton.textContent = 'Đang xử lý...';
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
                            districtSelect.innerHTML = '<option value="" disabled selected>Chọn quận / huyện</option>';
                            districtSelect.disabled = true;
                        }
                    } else {
                        alert(result.message || 'Có lỗi xảy ra khi tạo đơn hàng. Vui lòng thử lại.');
                    }
                } catch (error) {
                    console.error('Checkout error:', error);
                    alert('Có lỗi xảy ra khi tạo đơn hàng. Vui lòng thử lại.');
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
                        optionEntries.push({ label: 'Màu', value: item.color });
                    }
                    const lensValue = item.lensLabel || item.lens;
                    if (lensValue) {
                        optionEntries.push({ label: 'Gói tròng', value: lensValue });
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
                        : `<p class="order-card__options--empty">Không có tùy chọn bổ sung</p>`;

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
                                    <span>Đơn giá</span>
                                    <strong>${toCurrency(item.price)}</strong>
                                </div>
                                <div class="order-quantity" role="group" aria-label="Điều chỉnh số lượng">
                                    <button type="button" class="order-decrease" aria-label="Giảm số lượng">-</button>
                                    <span>${item.quantity}</span>
                                    <button type="button" class="order-increase" aria-label="Tăng số lượng">+</button>
                                </div>
                            </div>
                            <div class="order-card__footer">
                                <div class="order-card__total">
                                    <span>Tạm tính</span>
                                    <strong>${toCurrency(item.price * item.quantity)}</strong>
                                </div>
                                <button type="button" class="order-remove-btn order-remove-btn--subtle">
                                    <span>✕</span> Xóa
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
                        optionEntries.push({ label: 'Màu', value: item.color });
                    }
                    const lensValue = item.lensLabel || item.lens;
                    if (lensValue) {
                        optionEntries.push({ label: 'Gói tròng', value: lensValue });
                    }
                    if (item.options && typeof item.options === 'object') {
                        Object.entries(item.options).forEach(([label, value]) => {
                            if (value) {
                                optionEntries.push({ label, value });
                            }
                        });
                    }

                    const metaLines = [
                        item.brand ? `<span><strong>Thương hiệu:</strong> ${item.brand}</span>` : null,
                        ...optionEntries.map(opt => `<span><strong>${opt.label}:</strong> ${opt.value}</span>`)
                    ].filter(Boolean).join('');

                    return `
                        <div class="receipt-product">
                            <div class="receipt-product__info">
                                <img src="${item.image}" alt="${item.name}">
                                <div>
                                    <h3>${item.name}</h3>
                                    <div class="receipt-product__meta">
                                        ${metaLines || '<span><strong>Tùy chọn:</strong> Không có</span>'}
                                    </div>
                                </div>
                            </div>
                            <div class="text-sm text-gray-600">x${item.quantity}</div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">${toCurrency(item.price * item.quantity)}</p>
                                <p class="text-xs text-gray-400 mt-1">Đơn giá: ${toCurrency(item.price)}</p>
                            </div>
                        </div>
                    `;
                }).join('');

                orderReceipt.innerHTML = `
                    <div class="order-receipt__header">
                        <div>
                            <h2>Hóa đơn tạm tính</h2>
                            <p class="text-sm opacity-80">Đây là tóm tắt chi tiết cho giỏ hàng hiện tại</p>
                        </div>
                        <div class="order-receipt__meta">
                            <span>🧾 <strong>Mã đơn tạm:</strong> #${receiptNumber}</span>
                            <span>📅 <strong>Thời gian:</strong> ${formatDateTime(now)}</span>
                            <span>👜 <strong>Tổng sản phẩm:</strong> ${totalItems}</span>
                        </div>
                    </div>
                    <div class="order-receipt__body">
                        <section class="receipt-product-list" aria-label="Danh sách sản phẩm trong giỏ">
                            <header>
                                <span>Sản phẩm</span>
                                <span>Số lượng</span>
                                <span>Thành tiền</span>
                            </header>
                            ${receiptItems}
                        </section>
                        <section class="receipt-totals" aria-label="Chi tiết thanh toán">
                            <div class="receipt-total-row">
                                <span>Tạm tính</span>
                                <strong>${toCurrency(totalPrice)}</strong>
                            </div>
                            <div class="receipt-total-row">
                                <span>Phí vận chuyển</span>
                                <strong>${shippingFee === 0 ? 'Miễn phí' : toCurrency(shippingFee)}</strong>
                            </div>
                            <div class="receipt-total-row">
                                <span>Mã giảm giá</span>
                                <strong>${discountValue === 0 ? 'Chưa áp dụng' : '-' + toCurrency(discountValue)}</strong>
                            </div>
                            <div class="receipt-total-row grand">
                                <span>Tổng thanh toán</span>
                                <strong>${toCurrency(grandTotal)}</strong>
                            </div>
                        </section>
                        <div class="receipt-actions">
                            <button class="secondary" type="button" id="order-download-pdf">
                                <span class="receipt-download-icon">⬇</span>
                                <span class="receipt-download-text">Tải xuống PDF</span>
                            </button>
                            <button class="secondary" type="button" id="order-continue-shopping">
                                <span>↩</span> Tiếp tục mua sắm
                            </button>
                            <button class="primary" type="button" id="order-checkout">
                                <span>🛒</span> Tiến hành thanh toán
                            </button>
                        </div>
                    </div>
                `;

                const downloadButton = orderReceipt.querySelector('#order-download-pdf');
                if (downloadButton) {
                    downloadButton.addEventListener('click', () => {
                        alert('Tính năng tải PDF sẽ được cập nhật sớm!');
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
