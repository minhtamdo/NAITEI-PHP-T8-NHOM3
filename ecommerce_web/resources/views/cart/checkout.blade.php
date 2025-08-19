@extends('layouts.user')

@section('title', 'Thanh toán')

@section('content')

@vite(['resources/css/cart/checkout.css', 'resources/js/cart/checkout.js'])
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Đặt hàng</div>
        </div>

        <div class="checkout-grid">
            <div class="form-section">
                <div class="section-title">Thông tin giao hàng</div>
                
                <form id="checkoutForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">Họ</label>
                            <input type="text" id="firstName" name="firstName" required placeholder="Nhập họ">
                        </div>
                        <div class="form-group">
                            <label for="lastName">Tên</label>
                            <input type="text" id="lastName" name="lastName" required placeholder="Nhập tên">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Địa chỉ email</label>
                        <input type="email" id="email" name="email" required placeholder="your.email@domain.com">
                    </div>

                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="tel" id="phone" name="phone" required placeholder="+84 123 456 789">
                    </div>

                    <div class="form-group">
                        <label for="address">Địa chỉ giao hàng</label>
                        <input type="text" id="address" name="address" required placeholder="Số nhà, tên đường, phường/xã">
                    </div>

                    <div class="form-group">
                        <label for="notes">Ghi chú đặc biệt</label>
                        <textarea id="notes" name="notes" placeholder="Yêu cầu đặc biệt về thời gian giao hàng, đóng gói..."></textarea>
                    </div>

                    <div class="payment-section">
                        <div class="section-title">Phương thức thanh toán</div>
                        
                        <div class="payment-method">
                            <div class="payment-header">
                                <div class="payment-icon">COD</div>
                                <div class="payment-details">
                                    <h3>Thanh toán khi nhận hàng</h3>
                                    <p>Thanh toán bằng tiền mặt khi giao hàng</p>
                                </div>
                            </div>
                            <div class="payment-benefits">
                                <div>Kiểm tra kỹ sản phẩm trước khi thanh toán</div>
                                <div>Hoàn toàn miễn phí, không phát sinh thêm chi phí</div>
                                <div>Đảm bảo an toàn tuyệt đối cho khách hàng</div>
                                <div>Hỗ trợ đổi trả trong vòng 7 ngày</div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="order-summary">
                <div class="section-title">Tóm tắt đơn hàng</div>

                <div class="product-item">
                    <div class="product-image">◆</div>
                    <div class="product-info">
                        <div class="product-name">Áo sơ mi lụa premium</div>
                        <div class="product-variant">Trắng · Size M</div>
                        <div class="product-quantity">Số lượng: 1</div>
                    </div>
                    <div class="product-price">2.890.000₫</div>
                </div>

                <div class="product-item">
                    <div class="product-image">◆</div>
                    <div class="product-info">
                        <div class="product-name">Quần âu wools</div>
                        <div class="product-variant">Đen · Size 32</div>
                        <div class="product-quantity">Số lượng: 1</div>
                    </div>
                    <div class="product-price">3.490.000₫</div>
                </div>

                <div class="product-item">
                    <div class="product-image">◆</div>
                    <div class="product-info">
                        <div class="product-name">Cà vạt silk</div>
                        <div class="product-variant">Navy · Classic</div>
                        <div class="product-quantity">Số lượng: 1</div>
                    </div>
                    <div class="product-price">790.000₫</div>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-row">
                    <span>Tạm tính</span>
                    <span>7.170.000₫</span>
                </div>
                <div class="summary-row">
                    <span>Phí vận chuyển</span>
                    <span>30.000₫</span>
                </div>
                <div class="summary-row total">
                    <span>Tổng thanh toán</span>
                    <span>7.200.000₫</span>
                </div>

                <button type="submit" class="submit-btn" onclick="handleSubmit()">
                    Hoàn tất đơn hàng
                </button>

                <div class="security-note">
                    Được bảo mật bởi SSL 256-bit
                </div>
            </div>
        </div>
    </div>
</body>
</html>
@endsection
