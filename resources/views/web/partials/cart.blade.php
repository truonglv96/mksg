<!-- Overlay khi giỏ hàng mở -->
<div id="cart-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-[65] hidden"></div>

<!-- Giỏ hàng Dropdown -->
<div id="cart-dropdown"
    class="fixed top-0 right-0 w-80 md:w-96 h-full bg-white shadow-2xl z-[70] transform translate-x-full transition-transform duration-300">
    <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-bold text-gray-800">{{ config('texts.cart_title') }} (<span id="cart-total-items">0</span>)</h3>
            <button id="close-cart" class="text-gray-600 hover:text-red-600 text-2xl">&times;</button>
        </div>

        <!-- Cart Items -->
        <div id="cart-items" class="flex-1 overflow-y-auto p-4">
            <p class="text-gray-500 text-center py-8">{{ config('texts.cart_empty') }}</p>
        </div>

        <!-- Footer -->
        <div class="border-t p-4">
            <div class="flex justify-between items-center mb-4">
                <span class="font-bold text-gray-800">{{ config('texts.cart_total') }}</span>
                <span id="cart-total-price" class="font-bold text-red-600 text-xl">0 {{ config('texts.currency') }}</span>
            </div>
            <button
                class="w-full bg-red-600 text-white py-3 rounded font-medium hover:bg-red-700 transition-colors cart-checkout-btn"
                type="button">
                {{ config('texts.cart_checkout', 'Thanh Toán') }}
            </button>
        </div>
    </div>
</div>

