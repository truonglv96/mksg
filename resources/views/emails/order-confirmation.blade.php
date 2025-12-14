<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn Tạm Tính</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }
        .email-container {
            max-width: 700px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            background-color: #ed1c24;
            color: #ffffff;
            padding: 40px 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .header-left h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .header-left p {
            font-size: 14px;
            opacity: 0.9;
        }
        .header-right {
            text-align: right;
            font-size: 14px;
        }
        .header-right-item {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-bottom: 8px;
            gap: 8px;
        }
        .header-right-item:last-child {
            margin-bottom: 0;
        }
        .content {
            padding: 30px;
        }
        .product-header {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 20px;
            padding: 15px 0;
            border-bottom: 2px solid #e5e5e5;
            font-size: 12px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
        }
        .product-item {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 20px;
            padding: 20px 0;
            border-bottom: 1px solid #e5e5e5;
        }
        .product-info {
            display: flex;
            gap: 15px;
        }
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            background-color: #f0f0f0;
        }
        .product-details h3 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #000;
        }
        .product-details p {
            font-size: 13px;
            color: #666;
            margin-bottom: 4px;
        }
        .product-quantity {
            text-align: center;
            color: #666;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .product-price {
            text-align: right;
        }
        .product-price-main {
            font-size: 16px;
            font-weight: bold;
            color: #000;
            margin-bottom: 4px;
        }
        .product-price-unit {
            font-size: 12px;
            color: #666;
        }
        .order-summary {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e5e5e5;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            font-size: 14px;
        }
        .summary-row.total {
            font-size: 18px;
            font-weight: bold;
            color: #ed1c24;
            border-top: 2px solid #e5e5e5;
            margin-top: 10px;
            padding-top: 15px;
        }
        .summary-label {
            color: #666;
        }
        .summary-value {
            color: #000;
            font-weight: 500;
        }
        .summary-value.total {
            color: #ed1c24;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <h1>Hóa Đơn Tạm Tính</h1>
                <p>Đây là tóm tắt chi tiết cho giỏ hàng hiện tại</p>
            </div>
            <div class="header-right">
                <div class="header-right-item">
                    <span>📄</span>
                    <span>Mã đơn tạm: #{{ $bill->code_bill }}</span>
                </div>
                <div class="header-right-item">
                    <span>📅</span>
                    <span>Thời gian: {{ $bill->created_at->format('H:i d/m/Y') }}</span>
                </div>
                <div class="header-right-item">
                    <span>🛒</span>
                    <span>Tổng sản phẩm: {{ $billDetails->sum('qty') }}</span>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Product Header -->
            <div class="product-header">
                <div>SẢN PHẨM</div>
                <div style="text-align: center;">SỐ LƯỢNG</div>
                <div style="text-align: right;">THÀNH TIỀN</div>
            </div>

            <!-- Product Items -->
            @foreach($billDetails as $detail)
                @php
                    $product = $detail->product;
                    $productImage = $product && $product->image ? asset('img/product/' . $product->image) : asset('img/product/default.jpg');
                    $productName = $product ? $product->name : ($detail->category_name ?? 'Sản phẩm');
                    $brand = $product && $product->brand ? $product->brand->name : null;
                    $color = null;
                    if ($product && $detail->color_id) {
                        $color = $product->colors->where('id', $detail->color_id)->first();
                    }
                    $unitPrice = $detail->price - ($detail->sale_off ?? 0);
                    $totalPrice = $unitPrice * $detail->qty;
                @endphp
                <div class="product-item">
                    <div class="product-info">
                        <img src="{{ $productImage }}" alt="{{ $productName }}" class="product-image" onerror="this.src='{{ asset('img/product/default.jpg') }}'">
                        <div class="product-details">
                            <h3>{{ $productName }}</h3>
                            @if($brand)
                                <p>Thương hiệu: {{ $brand }}</p>
                            @endif
                            @if($color)
                                <p>Màu: {{ $color->name }}</p>
                            @endif
                            @if($detail->category_name && strpos($detail->category_name, ',') !== false)
                                <p>Gói tròng: {{ $detail->category_name }}</p>
                            @elseif($detail->category_name)
                                <p>Danh mục: {{ $detail->category_name }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="product-quantity">
                        x{{ $detail->qty }}
                    </div>
                    <div class="product-price">
                        <div class="product-price-main">{{ number_format($totalPrice, 0, ',', '.') }} VNĐ</div>
                        <div class="product-price-unit">Đơn giá: {{ number_format($unitPrice, 0, ',', '.') }} VNĐ</div>
                    </div>
                </div>
            @endforeach

            <!-- Order Summary -->
            <div class="order-summary">
                @php
                    $subtotal = $totalAmount;
                    $shippingFee = 0; // Miễn phí
                    $discount = 0; // Chưa áp dụng
                    $finalTotal = $subtotal + $shippingFee - $discount;
                @endphp
                <div class="summary-row">
                    <span class="summary-label">Tạm tính</span>
                    <span class="summary-value">{{ number_format($subtotal, 0, ',', '.') }} VNĐ</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Phí vận chuyển</span>
                    <span class="summary-value">Miễn phí</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Mã giảm giá</span>
                    <span class="summary-value">Chưa áp dụng</span>
                </div>
                <div class="summary-row total">
                    <span>Tổng thanh toán</span>
                    <span class="summary-value total">{{ number_format($finalTotal, 0, ',', '.') }} VNĐ</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
