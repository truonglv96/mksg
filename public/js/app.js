        // 9. LOGIC JAVASCRIPT
        document.addEventListener('DOMContentLoaded', function () {

            // --- TÍNH TOÁN VỊ TRÍ TOP CHO MEGA MENU ---
            function updateMegaMenuPosition() {
                const nav = document.querySelector('nav.hidden.lg\\:block');
                if (nav) {
                    const navRect = nav.getBoundingClientRect();
                    const navBottom = navRect.bottom;
                    document.documentElement.style.setProperty('--nav-bottom', navBottom + 'px');
                }
            }

            // Tính toán khi load và khi resize
            updateMegaMenuPosition();
            window.addEventListener('resize', updateMegaMenuPosition);
            window.addEventListener('scroll', updateMegaMenuPosition);

            // --- LOGIC MOBILE SIDEBAR ---
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');

            // Mở Sidebar
            if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileSidebar.classList.remove('-translate-x-full');
            });
            }

            // Đóng Sidebar
            if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', () => {
                mobileSidebar.classList.add('-translate-x-full');
            });
            }

            // Toggle Sub-menu cho tất cả categories và children (Event Delegation)
            if (mobileSidebar) {
                mobileSidebar.addEventListener('click', (e) => {
                    // Xử lý toggle cho category (level 1)
                    const categoryToggleBtn = e.target.closest('.toggle-category-btn');
                    if (categoryToggleBtn) {
                        e.preventDefault();
                        e.stopPropagation();
                        const submenuId = categoryToggleBtn.getAttribute('data-submenu-id');
                        const submenu = document.getElementById(submenuId);
                        const arrow = categoryToggleBtn.querySelector('.toggle-arrow');
                        
                        // Tìm category text link từ parent container
                        const parentLi = categoryToggleBtn.closest('li');
                        const categoryText = parentLi ? parentLi.querySelector('.category-text') : null;

                        if (submenu && arrow) {
                            const isHidden = submenu.classList.toggle('hidden');

                            // Toggle arrow rotation
                            if (isHidden) {
                                arrow.classList.remove('rotate-180');
                            } else {
                                arrow.classList.add('rotate-180');
                            }

                            // Toggle active state (màu đỏ) cho link
                            if (categoryText) {
                                // Remove active từ tất cả category links khác
                                const allCategoryLinks = mobileSidebar.querySelectorAll('.category-text');
                                allCategoryLinks.forEach(link => {
                                    link.classList.remove('text-red-600');
                                });

                                // Add active cho link hiện tại
                                if (!isHidden) {
                                    categoryText.classList.add('text-red-600');
                                } else {
                                    categoryText.classList.remove('text-red-600');
                                }
                            }
                        }
                        return;
                    }

                    // Xử lý toggle cho child (level 2)
                    const childToggleBtn = e.target.closest('.toggle-child-btn');
                    if (childToggleBtn) {
                        e.preventDefault();
                        e.stopPropagation();
                        const submenuId = childToggleBtn.getAttribute('data-submenu-id');
                        const submenu = document.getElementById(submenuId);
                        const arrow = childToggleBtn.querySelector('.toggle-arrow');
                        
                        // Tìm child text link từ parent container
                        const parentLi = childToggleBtn.closest('li');
                        const childText = parentLi ? parentLi.querySelector('.child-text') : null;

                        if (submenu && arrow) {
                            const isHidden = submenu.classList.toggle('hidden');

                            // Toggle arrow rotation
                    if (isHidden) {
                        arrow.classList.remove('rotate-180');
                    } else {
                        arrow.classList.add('rotate-180');
                            }

                            // Toggle active state (màu đỏ) cho link
                            if (childText) {
                                // Remove active từ tất cả child links khác trong cùng parent
                                const parentSubmenu = childToggleBtn.closest('ul');
                                if (parentSubmenu) {
                                    const allChildLinks = parentSubmenu.querySelectorAll('.child-text');
                                    allChildLinks.forEach(link => {
                                        link.classList.remove('text-red-600');
                                    });
                                }

                                // Add active cho link hiện tại
                                if (!isHidden) {
                                    childText.classList.add('text-red-600');
                                } else {
                                    childText.classList.remove('text-red-600');
                                }
                            }
                        }
                        return;
                    }
                });

                // Khởi tạo: Đảm bảo tất cả sub-menu ẩn khi mới load
                const allCategorySubmenus = mobileSidebar.querySelectorAll('[id^="submenu-category-"]');
                allCategorySubmenus.forEach(submenu => {
                    submenu.classList.add('hidden');
                });

                const allChildSubmenus = mobileSidebar.querySelectorAll('[id^="submenu-child-"]');
                allChildSubmenus.forEach(submenu => {
                    submenu.classList.add('hidden');
                });

                // Khởi tạo arrows (SVG không cần textContent)
                const allArrows = mobileSidebar.querySelectorAll('.toggle-arrow');
                allArrows.forEach(arrow => {
                arrow.classList.remove('rotate-180');
                });
            }

            // --- LOGIC FILTER SẢN PHẨM THEO DANH MỤC VÀ KHỞI TẠO SWIPER ---
            let categorySwipers = {};

            function filterProducts(category) {
                // Lấy tất cả các product-groups và tabs
                const productGroups = document.querySelectorAll('.product-group');
                const tabs = document.querySelectorAll('.product-tab');

                // Cập nhật active state cho tabs
                tabs.forEach(tab => {
                    if (tab.getAttribute('data-category') === category) {
                        tab.classList.add('active');
                        tab.classList.remove('border-transparent');
                        tab.style.borderColor = '#ed1c24';
                        tab.style.color = '#ed1c24';
                    } else {
                        tab.classList.remove('active');
                        tab.style.borderColor = 'transparent';
                        tab.style.color = '';
                    }
                });

                // Hiển thị product-group tương ứng với category và khởi tạo Swiper nếu chưa có
                productGroups.forEach(group => {
                    if (group.getAttribute('data-category') === category) {
                        group.classList.remove('hidden');

                        // Khởi tạo Swiper cho category này nếu chưa có
                        if (!categorySwipers[category]) {
                            const swiperContainer = group.querySelector('.category-swiper');
                            if (swiperContainer) {
                setTimeout(() => {
                                    categorySwipers[category] = new Swiper(swiperContainer, {
                        slidesPerView: 2,
                                        spaceBetween: 10,
                        watchOverflow: true,
                        loop: false,
                        navigation: {
                                            nextEl: swiperContainer.querySelector('.swiper-button-next'),
                                            prevEl: swiperContainer.querySelector('.swiper-button-prev'),
                        },
                        breakpoints: {
                            768: {
                                slidesPerView: 3,
                                                spaceBetween: 10,
                            },
                            1024: {
                                slidesPerView: 4,
                                                spaceBetween: 10,
                            }
                        }
                    });
                }, 100);
                            }
                        }
                    } else {
                        group.classList.add('hidden');
                    }
                });
            }

            // Thêm event listeners cho các tab
            const productTabs = document.querySelectorAll('.product-tab');
            productTabs.forEach(tab => {
                tab.addEventListener('click', function (e) {
                    e.preventDefault();
                    const category = this.getAttribute('data-category');
                    filterProducts(category);
                });
            });

            // Khởi tạo với danh mục mặc định (tab đầu tiên có class active)
            const firstActiveTab = document.querySelector('.product-tab.active');
            if (firstActiveTab) {
                const defaultCategory = firstActiveTab.getAttribute('data-category');
                filterProducts(defaultCategory);
            } else if (productTabs.length > 0) {
                // Nếu không có tab nào active, lấy tab đầu tiên
                const firstTab = productTabs[0];
                const defaultCategory = firstTab.getAttribute('data-category');
                filterProducts(defaultCategory);
            }

            // --- LOGIC GIỎ HÀNG ---
            let cart = [];

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
                    updateCartUI();
                }
            }

            // Lưu giỏ hàng vào localStorage
            function saveCart() {
                localStorage.setItem('cart', JSON.stringify(cart));
            }

            // Thêm sản phẩm vào giỏ hàng
            function addToCart(product) {
                const productKey = buildCartItemKey(product);
                const existingItem = cart.find(item => buildCartItemKey(item) === productKey);

                if (existingItem) {
                    existingItem.quantity += 1;
                } else {
                    cart.push({
                        ...product,
                        quantity: 1
                    });
                }

                saveCart();
                updateCartUI();
                showCartNotification();
            }

            // Xóa sản phẩm khỏi giỏ hàng
            function removeFromCart(itemKey) {
                cart = cart.filter(item => buildCartItemKey(item) !== itemKey);
                saveCart();
                updateCartUI();
            }

            // Cập nhật số lượng sản phẩm
            function updateQuantity(itemKey, newQuantity) {
                const item = cart.find(item => buildCartItemKey(item) === itemKey);
                if (item) {
                    if (newQuantity <= 0) {
                        removeFromCart(itemKey);
                    } else {
                        item.quantity = newQuantity;
                        saveCart();
                        updateCartUI();
                    }
                }
            }

            // Cập nhật giao diện giỏ hàng
            function updateCartUI() {
                const cartCount = document.getElementById('cart-count');
                const cartCountDesktop = document.getElementById('cart-count-desktop');
                const cartTotalItems = document.getElementById('cart-total-items');
                const cartItems = document.getElementById('cart-items');
                const cartTotalPrice = document.getElementById('cart-total-price');

                const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

                // Cập nhật số lượng cho cả mobile và desktop
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
                
                if (cartTotalItems) {
                    cartTotalItems.textContent = totalItems;
                }

                // Cập nhật tổng tiền
                if (cartTotalPrice) {
                    cartTotalPrice.textContent = totalPrice.toLocaleString('vi-VN') + ' VNĐ';
                }

                // Cập nhật danh sách sản phẩm
                if (cartItems) {
                    if (cart.length === 0) {
                        cartItems.innerHTML = '<p class="text-gray-500 text-center py-8">Giỏ hàng trống</p>';
                    } else {
                        cartItems.innerHTML = cart.map(item => {
                            const itemKey = encodeURIComponent(buildCartItemKey(item));
                            
                            // Xây dựng option details - ưu tiên selectedOptions (multi-select), sau đó fallback về lensLabel
                            const optionParts = [];
                            if (item.color) {
                                optionParts.push(`Màu: ${item.color}`);
                            }
                            
                            // Sử dụng selectedOptions nếu có (multi-select), nếu không thì dùng lensLabel hoặc lens
                            if (item.selectedOptions && item.selectedOptions.length > 0) {
                                optionParts.push(`Gói tròng: ${item.selectedOptions.join(', ')}`);
                            } else if (item.lensLabel) {
                                optionParts.push(`Gói tròng: ${item.lensLabel}`);
                            } else if (item.lens) {
                                optionParts.push(`Gói tròng: ${item.lens}`);
                            }
                            
                            const optionDetails = optionParts.join(' • ');

                            return `
                                <div class="cart-item flex gap-3 mb-4 pb-4 border-b" data-item-key="${itemKey}">
                                <img src="${item.image}" alt="${item.name}" class="w-20 h-20 object-cover rounded">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-sm mb-1">${item.name}</h4>
                                        <p class="text-xs text-gray-500">${item.brand || ''}</p>
                                        ${optionDetails ? `<p class="text-xs text-gray-500 mt-1">${optionDetails}</p>` : ''}
                                        <div class="flex justify-between items-center mt-2">
                                        <div class="flex items-center gap-2">
                                            <button class="decrease-btn w-6 h-6 flex items-center justify-center bg-gray-200 rounded hover:bg-gray-300">-</button>
                                            <span class="text-sm font-medium">${item.quantity}</span>
                                            <button class="increase-btn w-6 h-6 flex items-center justify-center bg-gray-200 rounded hover:bg-gray-300">+</button>
                                        </div>
                                        <span class="text-red-600 font-bold text-sm">${(item.price * item.quantity).toLocaleString('vi-VN')} VNĐ</span>
                                    </div>
                                </div>
                                <button class="remove-btn text-gray-400 hover:text-red-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            `;
                        }).join('');
                    }
                }
            }

            // Hiển thị thông báo đã thêm vào giỏ
            function showCartNotification() {
                const notification = document.createElement('div');
                notification.className = 'fixed top-20 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-[80] animate-bounce';
                notification.textContent = '✓ Đã thêm vào giỏ hàng';
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 2000);
            }

            // Toggle giỏ hàng
            const cartBtn = document.getElementById('cart-btn');
            const cartBtnDesktop = document.getElementById('cart-btn-desktop');
            const closeCart = document.getElementById('close-cart');
            const cartDropdown = document.getElementById('cart-dropdown');
            const cartOverlay = document.getElementById('cart-overlay');

            function openCart() {
                if (cartDropdown && cartOverlay) {
                    cartDropdown.classList.remove('translate-x-full');
                    cartOverlay.classList.remove('hidden');
                    document.body.style.overflow = 'hidden'; // Khóa scroll
                }
            }

            function closeCartFunc() {
                if (cartDropdown && cartOverlay) {
                    cartDropdown.classList.add('translate-x-full');
                    cartOverlay.classList.add('hidden');
                    document.body.style.overflow = ''; // Mở scroll
                }
            }

            // Event listeners cho cả mobile và desktop
            if (cartBtn) {
                cartBtn.addEventListener('click', openCart);
            }
            if (cartBtnDesktop) {
                cartBtnDesktop.addEventListener('click', openCart);
            }
            if (closeCart) {
                closeCart.addEventListener('click', closeCartFunc);
            }
            if (cartOverlay) {
                cartOverlay.addEventListener('click', closeCartFunc);
            }

            // Xử lý button "Thanh Toán" trong cart dropdown
            document.addEventListener('click', function (e) {
                const checkoutBtn = e.target.closest('.cart-checkout-btn');
                if (!checkoutBtn) {
                    return;
                }

                e.preventDefault();
                
                // Đóng cart dropdown
                closeCartFunc();
                
                // Chuyển đến trang giỏ hàng
                window.location.href = '/gio-hang';
            });

            // Event delegation cho các buttons trong giỏ hàng
            const cartItemsEl = document.getElementById('cart-items');
            if (cartItemsEl) {
                cartItemsEl.addEventListener('click', function (e) {
                    const cartItem = e.target.closest('.cart-item');
                    if (!cartItem) return;

                    const encodedKey = cartItem.dataset.itemKey;
                    if (!encodedKey) return;
                    const itemKey = decodeURIComponent(encodedKey);
                    const item = cart.find(i => buildCartItemKey(i) === itemKey);

                    if (!item) return;

                    // Xử lý click nút giảm
                    if (e.target.closest('.decrease-btn')) {
                        updateQuantity(itemKey, item.quantity - 1);
                    }
                    // Xử lý click nút tăng
                    else if (e.target.closest('.increase-btn')) {
                        updateQuantity(itemKey, item.quantity + 1);
                    }
                    // Xử lý click nút xóa
                    else if (e.target.closest('.remove-btn')) {
                        removeFromCart(itemKey);
                    }
                });
            }

            // Function chung để lấy thông tin sản phẩm từ button được click
            function getProductDataFromButton(button) {
                if (!button) return null;

                // Ưu tiên: Kiểm tra data attributes trên button trước
                if (button.dataset.productName && button.dataset.productPrice && button.dataset.productImage) {
                    return {
                        id: parseInt(button.dataset.productId) || 0,
                        productId: parseInt(button.dataset.productId) || 0,
                        name: button.dataset.productName,
                        brand: button.dataset.productBrand || '',
                        price: parseInt(button.dataset.productPrice) || 0,
                        image: button.dataset.productImage,
                        color: '',
                        lens: '',
                        lensLabel: '',
                        selectedOptions: []
                    };
                }

                // Kiểm tra nếu button nằm trong product summary (trang chi tiết sản phẩm)
                const productSummary = document.getElementById('product-summary');
                if (productSummary && productSummary.contains(button)) {
                    const name = productSummary.querySelector('#product-name')?.textContent.trim() || '';
                    const brand = productSummary.querySelector('#product-brand')?.textContent.trim() || '';
                    const priceText = productSummary.querySelector('[data-product-price]')?.textContent.trim() || '';
                    const price = parseInt(priceText.replace(/[^\d]/g, '')) || 0;
                    const image = document.getElementById('main-product-image')?.src || '';
                    const color = productSummary.dataset.selectedColor || productSummary.querySelector('.color-chip.active')?.dataset.color || '';
                    
                    // Lấy tất cả các option đã chọn (multi-select)
                    const activeOptionPills = Array.from(productSummary.querySelectorAll('.option-pill.active'));
                    const selectedOptions = activeOptionPills.map(pill => {
                        return pill.querySelector('.font-semibold')?.textContent.trim() || pill.dataset.option || '';
                    }).filter(Boolean);
                    
                    // Giữ lại để tương thích với code cũ
                    const lens = selectedOptions.length > 0 ? selectedOptions[0] : '';
                    const lensLabel = selectedOptions.join(', '); // Tất cả các option đã chọn
                    
                    // Lấy product ID
                    const productId = productSummary.dataset.productId || productSummary.getAttribute('data-product-id') || '';

                    return {
                        id: parseInt(productId) || 0,
                        productId: parseInt(productId) || 0,
                        name,
                        brand,
                        price,
                        image,
                        color,
                        lens,
                        lensLabel,
                        selectedOptions: selectedOptions // Mảng tất cả các option đã chọn
                    };
                }

                // Function helper để tìm giá trong container
                function findPriceElement(container) {
                    // Thử tìm element có cả 2 class text-red-600 và font-bold
                    let priceEl = container.querySelector('.text-red-600.font-bold');
                    if (priceEl) return priceEl;
                    
                    // Tìm element có font-bold và chứa số và "VNĐ" (ưu tiên)
                    const boldElements = container.querySelectorAll('.font-bold, [style*="font-weight: bold"], [style*="font-weight:700"]');
                    for (const el of boldElements) {
                        const text = el.textContent.trim();
                        if (text.includes('VNĐ') && /\d/.test(text)) {
                            // Kiểm tra xem có phải giá chính không (không phải giá gạch ngang)
                            if (!el.classList.contains('line-through') && !el.style.textDecoration.includes('line-through')) {
                                return el;
                            }
                        }
                    }
                    
                    // Tìm element có style color đỏ (#ed1c24 hoặc rgb(237, 28, 36)) và font-bold
                    const allElements = container.querySelectorAll('*');
                    for (const el of allElements) {
                        const style = window.getComputedStyle(el);
                        const color = style.color;
                        const text = el.textContent.trim();
                        // Kiểm tra màu đỏ (#ed1c24 = rgb(237, 28, 36))
                        if (text && text.includes('VNĐ') && /\d/.test(text)) {
                            if ((color.includes('237') && color.includes('28') && color.includes('36')) || 
                                color.includes('rgb(237, 28, 36)') || 
                                color.includes('#ed1c24')) {
                                if (el.classList.contains('font-bold') || style.fontWeight >= 600) {
                                    if (!el.classList.contains('line-through') && !style.textDecoration.includes('line-through')) {
                                        return el;
                                    }
                                }
                            }
                        }
                    }
                    
                    // Fallback: tìm tất cả .text-red-600 và chọn cái có chứa "VNĐ" và có font-bold
                    const priceElements = container.querySelectorAll('.text-red-600');
                    for (const el of priceElements) {
                        if (el.textContent.includes('VNĐ') && el.classList.contains('font-bold')) {
                            return el;
                        }
                    }
                    
                    // Fallback cuối: tìm bất kỳ element nào có chứa "VNĐ" và số (không phải giá gạch ngang)
                    for (const el of allElements) {
                        const text = el.textContent.trim();
                        if (text.includes('VNĐ') && /\d/.test(text) && el.children.length === 0) {
                            if (!el.classList.contains('line-through') && !window.getComputedStyle(el).textDecoration.includes('line-through')) {
                                return el;
                            }
                        }
                    }
                    
                    return null;
                }

                // Kiểm tra nếu button nằm trong product-card (sản phẩm liên quan, danh sách sản phẩm)
                const productCard = button.closest('.product-card');
                    if (productCard) {
                        const nameEl = productCard.querySelector('h3');
                    const brandEl = productCard.querySelector('.product-brand, .text-gray-500');
                    const priceEl = findPriceElement(productCard);
                        const imageEl = productCard.querySelector('.product-img-main') || productCard.querySelector('img');
                        
                    if (nameEl && priceEl && imageEl) {
                        const name = nameEl.textContent.trim();
                        const brand = brandEl ? brandEl.textContent.trim() : '';
                        const priceText = priceEl.textContent.trim();
                        const price = parseInt(priceText.replace(/[^\d]/g, ''));
                        const image = imageEl.src;

                        return {
                            name,
                            brand,
                            price,
                            image,
                            color: '',
                            lens: '',
                            lensLabel: ''
                        };
                    }
                }

                // Kiểm tra nếu button nằm trong swiper-slide (trang chủ, danh mục)
                const swiperSlide = button.closest('.swiper-slide');
                if (swiperSlide) {
                    const nameEl = swiperSlide.querySelector('h3');
                    // Tìm brand - có thể là p.text-gray-500 hoặc không có
                    const brandEl = swiperSlide.querySelector('.product-brand, .text-gray-500');
                    const priceEl = findPriceElement(swiperSlide);
                    const imageEl = swiperSlide.querySelector('.product-img-main') || swiperSlide.querySelector('img');
                        
                    if (nameEl && priceEl && imageEl) {
                        const name = nameEl.textContent.trim();
                        const brand = brandEl ? brandEl.textContent.trim() : '';
                        const priceText = priceEl.textContent.trim();
                        const price = parseInt(priceText.replace(/[^\d]/g, ''));
                        const image = imageEl.src;

                        return {
                            name,
                            brand,
                            price,
                            image,
                            color: '',
                            lens: '',
                            lensLabel: ''
                        };
                    }
                }

                return null;
            }

            // Thêm sản phẩm vào giỏ khi click button "Thêm vào giỏ hàng"
            document.addEventListener('click', function (e) {
                const addButton = e.target.closest('.add-to-cart-btn');
                if (!addButton) {
                    return;
                }

                e.preventDefault();

                // Lấy thông tin sản phẩm từ button
                const productData = getProductDataFromButton(addButton);

                if (productData && productData.name && productData.price && productData.image) {
                    addToCart(productData);
                } else {
                    console.warn('Không thể lấy thông tin sản phẩm:', productData);
                }
            });

            // Xử lý button "Mua ngay" - thêm vào giỏ và chuyển đến trang giỏ hàng
            document.addEventListener('click', function (e) {
                const buyNowButton = e.target.closest('.buy-now-btn');
                if (!buyNowButton) {
                    return;
                }

                e.preventDefault();

                // Lấy thông tin sản phẩm từ button
                const productData = getProductDataFromButton(buyNowButton);

                if (productData && productData.name && productData.price && productData.image) {
                    // Thêm sản phẩm vào giỏ hàng
                    addToCart(productData);
                    
                    // Chuyển đến trang giỏ hàng sau khi thêm
                    setTimeout(() => {
                        window.location.href = '/gio-hang';
                    }, 300); // Delay nhỏ để đảm bảo cart đã được lưu
                } else {
                    console.warn('Không thể lấy thông tin sản phẩm:', productData);
                }
            });

            // Load giỏ hàng khi trang tải
            loadCart();

            // --- LOGIC MOBILE FILTER SIDEBAR ---
            const mobileFilterBtn = document.getElementById('mobile-filter-btn');
            const mobileFilterSidebar = document.getElementById('mobile-filter-sidebar');
            const closeMobileFilter = document.getElementById('close-mobile-filter');
            const mobileFilterOverlay = document.getElementById('mobile-filter-overlay');

            // Mở filter sidebar
            function openMobileFilter() {
                if (mobileFilterSidebar && mobileFilterOverlay) {
                    mobileFilterSidebar.classList.remove('-translate-x-full');
                    mobileFilterOverlay.classList.remove('hidden');
                    document.body.style.overflow = 'hidden'; // Khóa scroll
                }
            }

            // Đóng filter sidebar
            function closeMobileFilterFunc() {
                if (mobileFilterSidebar && mobileFilterOverlay) {
                    mobileFilterSidebar.classList.add('-translate-x-full');
                    mobileFilterOverlay.classList.add('hidden');
                    document.body.style.overflow = ''; // Mở scroll
                }
            }

            // Event listeners
            if (mobileFilterBtn) {
                mobileFilterBtn.addEventListener('click', openMobileFilter);
            }
            if (closeMobileFilter) {
                closeMobileFilter.addEventListener('click', closeMobileFilterFunc);
            }
            if (mobileFilterOverlay) {
                mobileFilterOverlay.addEventListener('click', closeMobileFilterFunc);
            }

            // --- LOGIC SLIDER SWIPER (Responsive) ---

            // Banner Slider với autoplay
            new Swiper('.banner-slider', {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: true,
                autoplay: {
                    delay: 5000, // 5 giây chuyển slide
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.banner-slider .swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.banner-slider .swiper-button-next',
                    prevEl: '.banner-slider .swiper-button-prev',
                },
                effect: 'fade', // Hiệu ứng fade
                fadeEffect: {
                    crossFade: true
                },
            });

            // Slider Sản Phẩm sẽ được khởi tạo trong function filterProducts()

            // Slider Categories (chỉ trên mobile)
            new Swiper('.categories-slider', {
                slidesPerView: 2,
                spaceBetween: 15,
                watchOverflow: true,
                loop: false,
            });

            // Slider Brands - Chạy liên tục không dừng
            new Swiper('.brands-slider', {
                slidesPerView: 2,
                spaceBetween: 5,
                loop: true,
                centeredSlides: true,
                allowTouchMove: true,
                autoplay: {
                    delay: 1,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: false,
                    reverseDirection: false,
                },
                speed: 2500,
                breakpoints: {
                    480: {
                        slidesPerView: 3,
                        spaceBetween: 8,
                    },
                    768: {
                        slidesPerView: 4,
                        spaceBetween: 10,
                    },
                    1024: {
                        slidesPerView: 5,
                        spaceBetween: 12,
                    }
                }
            });

            // Slider Tin Tức (Mobile: 2 items, Tablet: 3 items, Desktop: 4 items)
            new Swiper('.news-slider', {
                slidesPerView: 2, // Mobile hiển thị đủ 2 tin tức
                spaceBetween: 8,
                watchOverflow: true,
                loop: false,
                speed: 600,
                navigation: {
                    nextEl: '.news-slider .swiper-button-next',
                    prevEl: '.news-slider .swiper-button-prev',
                },
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
                breakpoints: {
                    // Tablet (>= 768px)
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    // Desktop (>= 1024px)
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 10,
                    }
                }
            });

            // --- PRODUCT DETAIL OPTIONS ---
            const productSummary = document.getElementById('product-summary');
            if (productSummary) {
                const colorChips = Array.from(productSummary.querySelectorAll('.color-chip'));
                const optionPills = Array.from(productSummary.querySelectorAll('.option-pill'));
                const selectedColorLabel = productSummary.querySelector('#selected-color span');
                const selectedOptionLabel = productSummary.querySelector('#selected-option span');

                const setSelectedColor = (chip) => {
                    colorChips.forEach(btn => {
                        btn.classList.toggle('active', btn === chip);
                        btn.setAttribute('aria-pressed', btn === chip ? 'true' : 'false');
                    });
                    const color = chip?.dataset.color || '';
                    if (selectedColorLabel) {
                        selectedColorLabel.textContent = color;
                    }
                    productSummary.dataset.selectedColor = color;
                };

                // Option pills - multi-select với tính giá tổng
                const priceElement = productSummary.querySelector('[data-product-price]');
                const selectedOptionsLabel = document.getElementById('selected-options-list');
                const basePrice = priceElement ? parseFloat(priceElement.dataset.basePrice) || 0 : 0;

                // Tính tổng giá của tất cả các option đã chọn
                const calculateTotalPrice = () => {
                    let totalOptionPrice = 0;
                    const selectedOptions = [];
                    
                    optionPills.forEach(pill => {
                        if (pill.classList.contains('active')) {
                            const optionPrice = parseFloat(pill.dataset.optionPrice) || 0;
                            totalOptionPrice += optionPrice;
                            const optionTitle = pill.querySelector('.font-semibold')?.textContent.trim() || pill.dataset.option;
                            selectedOptions.push(optionTitle);
                        }
                    });
                    
                    return { totalOptionPrice, selectedOptions };
                };

                const updatePriceAndLabel = () => {
                    const { totalOptionPrice, selectedOptions } = calculateTotalPrice();
                    const totalPrice = basePrice + totalOptionPrice;
                    
                    if (priceElement) {
                        priceElement.textContent = new Intl.NumberFormat('vi-VN').format(totalPrice) + ' VNĐ';
                    }
                    
                    if (selectedOptionsLabel) {
                        if (selectedOptions.length > 0) {
                            selectedOptionsLabel.textContent = selectedOptions.join(', ');
                        } else {
                            selectedOptionsLabel.textContent = 'Chưa chọn';
                        }
                    }
                };

                const toggleOption = (pill) => {
                    const isCurrentlyActive = pill.classList.contains('active');
                    
                    if (isCurrentlyActive) {
                        pill.classList.remove('active');
                        pill.classList.remove('border-red-400', 'bg-red-50');
                        pill.setAttribute('aria-pressed', 'false');
                    } else {
                        pill.classList.add('active');
                        pill.classList.add('border-red-400', 'bg-red-50');
                        pill.setAttribute('aria-pressed', 'true');
                    }
                    
                    updatePriceAndLabel();
                };

                // Initialize defaults
                if (colorChips.length) {
                    const defaultChip = colorChips.find(chip => chip.classList.contains('active')) || colorChips[0];
                    setSelectedColor(defaultChip);
                }
                
                if (optionPills.length) {
                    // Mặc định chọn option đầu tiên nếu chưa có option nào được chọn
                    const hasActiveOption = optionPills.some(pill => pill.classList.contains('active'));
                    if (!hasActiveOption && optionPills[0]) {
                        optionPills[0].classList.add('active', 'border-red-400', 'bg-red-50');
                        optionPills[0].setAttribute('aria-pressed', 'true');
                    }
                    updatePriceAndLabel();
                }

                colorChips.forEach(chip => {
                    chip.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        setSelectedColor(chip);
                    });
                });

                optionPills.forEach(pill => {
                    pill.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        toggleOption(pill);
                    });
                });
            }

            // --- PRODUCT GALLERY & LIGHTBOX ---
            const mainImageEl = document.getElementById('main-product-image');
            if (mainImageEl) {
                const mainImageTrigger = document.getElementById('lightbox-trigger');
                const thumbnailButtons = Array.from(document.querySelectorAll('.thumbnail-button'));
                const lightboxEl = document.getElementById('image-lightbox');
                const lightboxMainImage = document.getElementById('lightbox-main-image');
                const lightboxThumbsContainer = document.getElementById('lightbox-thumbnails');
                const lightboxPrev = document.getElementById('lightbox-prev');
                const lightboxNext = document.getElementById('lightbox-next');
                const lightboxClose = document.getElementById('lightbox-close');

                const gallerySources = [];
                const galleryAlts = [];
                const seenSources = new Set();

                const addImageToGallery = (src, alt) => {
                    if (!src || seenSources.has(src)) return;
                    seenSources.add(src);
                    gallerySources.push(src);
                    galleryAlts.push(alt || mainImageEl.alt || 'Hình sản phẩm');
                };

                addImageToGallery(mainImageEl.src, mainImageEl.alt);

                thumbnailButtons.forEach(btn => {
                    const imgEl = btn.querySelector('img');
                    const src = btn.dataset.imageSrc || (imgEl ? imgEl.src : '');
                    const alt = imgEl ? imgEl.alt : '';
                    addImageToGallery(src, alt);
                });

                let currentImageIndex = gallerySources.indexOf(mainImageEl.src);
                if (currentImageIndex === -1) currentImageIndex = 0;

                let lightboxThumbnailButtons = [];

                const syncActiveThumbnails = (activeSrc) => {
                    thumbnailButtons.forEach(btn => {
                        const imgEl = btn.querySelector('img');
                        const btnSrc = btn.dataset.imageSrc || (imgEl ? imgEl.src : '');
                        btn.classList.toggle('active', btnSrc === activeSrc);
                    });
                };

                const syncLightboxThumbnails = (activeIndex) => {
                    lightboxThumbnailButtons.forEach((btn, index) => {
                        btn.classList.toggle('active', index === activeIndex);
                    });
                };

                const renderLightboxThumbnails = () => {
                    if (!lightboxThumbsContainer) return;
                    lightboxThumbsContainer.innerHTML = '';
                    gallerySources.forEach((src, index) => {
                        const thumbButton = document.createElement('button');
                        thumbButton.type = 'button';
                        thumbButton.className = 'lightbox-thumbnail';
                        thumbButton.dataset.index = index;
                        thumbButton.innerHTML = `<img src="${src}" alt="${galleryAlts[index] || 'Hình sản phẩm'}">`;
                        lightboxThumbsContainer.appendChild(thumbButton);
                    });
                    lightboxThumbnailButtons = Array.from(lightboxThumbsContainer.querySelectorAll('.lightbox-thumbnail'));
                    syncLightboxThumbnails(currentImageIndex);
                };

                const goToImage = (index, options = {}) => {
                    if (!gallerySources.length) return;
                    if (index < 0) index = gallerySources.length - 1;
                    if (index >= gallerySources.length) index = 0;
                    currentImageIndex = index;

                    if (options.updateMain !== false) {
                        mainImageEl.src = gallerySources[index];
                        const altText = galleryAlts[index] || 'Hình sản phẩm';
                        mainImageEl.alt = altText;
                        syncActiveThumbnails(gallerySources[index]);
                    }

                    if (lightboxEl && options.updateLightbox !== false && lightboxMainImage) {
                        lightboxMainImage.src = gallerySources[index];
                        lightboxMainImage.alt = galleryAlts[index] || 'Hình sản phẩm';
                        syncLightboxThumbnails(index);
                    }
                };

                const openLightbox = () => {
                    if (!lightboxEl) return;
                    renderLightboxThumbnails();
                    lightboxEl.classList.add('active');
                    lightboxEl.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                    goToImage(currentImageIndex, { updateMain: false });
                    if (lightboxClose) {
                        lightboxClose.focus({ preventScroll: true });
                    }
                };

                const closeLightbox = () => {
                    if (!lightboxEl) return;
                    lightboxEl.classList.remove('active');
                    lightboxEl.classList.add('hidden');
                    document.body.style.overflow = '';
                };

                thumbnailButtons.forEach(btn => {
                    btn.setAttribute('type', 'button');
                    btn.addEventListener('click', () => {
                        const imgEl = btn.querySelector('img');
                        const src = btn.dataset.imageSrc || (imgEl ? imgEl.src : '');
                        const index = gallerySources.indexOf(src);
                        if (index !== -1) {
                            goToImage(index);
                        }
                    });
                });

                if (lightboxThumbsContainer) {
                    lightboxThumbsContainer.addEventListener('click', (event) => {
                        const thumbBtn = event.target.closest('.lightbox-thumbnail');
                        if (!thumbBtn) return;
                        const index = Number(thumbBtn.dataset.index);
                        if (!Number.isNaN(index)) {
                            goToImage(index);
                        }
                    });
                }

                if (mainImageTrigger) {
                    mainImageTrigger.addEventListener('click', openLightbox);
                }

                if (lightboxPrev) {
                    lightboxPrev.addEventListener('click', () => goToImage(currentImageIndex - 1));
                }

                if (lightboxNext) {
                    lightboxNext.addEventListener('click', () => goToImage(currentImageIndex + 1));
                }

                if (lightboxClose) {
                    lightboxClose.addEventListener('click', closeLightbox);
                }

                if (lightboxEl) {
                    lightboxEl.addEventListener('click', (event) => {
                        if (event.target === lightboxEl) {
                            closeLightbox();
                        }
                    });
                }

                document.addEventListener('keydown', (event) => {
                    if (!lightboxEl || !lightboxEl.classList.contains('active')) return;
                    if (event.key === 'Escape') {
                        closeLightbox();
                    } else if (event.key === 'ArrowRight') {
                        goToImage(currentImageIndex + 1);
                    } else if (event.key === 'ArrowLeft') {
                        goToImage(currentImageIndex - 1);
                    }
                });

                // Đồng bộ trạng thái ban đầu
                goToImage(currentImageIndex, { updateLightbox: false });
            }

            // --- RELATED PRODUCTS SWIPER ---
            const relatedSwiperEl = document.querySelector('.related-swiper');
            if (relatedSwiperEl && typeof Swiper !== 'undefined') {
                new Swiper(relatedSwiperEl, {
                    slidesPerView: 1.1,
                    spaceBetween: 16,
                    loop: true,
                    navigation: {
                        nextEl: relatedSwiperEl.querySelector('.swiper-button-next'),
                        prevEl: relatedSwiperEl.querySelector('.swiper-button-prev'),
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 2,
                            spaceBetween: 18,
                        },
                        1024: {
                            slidesPerView: 3,
                            spaceBetween: 20,
                        },
                        1280: {
                            slidesPerView: 4,
                            spaceBetween: 24,
                        },
                    },
                });
            }

            // --- TAB SWITCHING FOR PRODUCT DETAIL ---
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabPanels = document.querySelectorAll('.tab-panel');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const targetTab = button.getAttribute('data-tab-target');

                    // Remove active class from all buttons and panels
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabPanels.forEach(panel => panel.classList.remove('active'));

                    // Add active class to clicked button and corresponding panel
                    button.classList.add('active');
                    const targetPanel = document.getElementById(targetTab);
                    if (targetPanel) {
                        targetPanel.classList.add('active');
                    }
                });
            });
        });
