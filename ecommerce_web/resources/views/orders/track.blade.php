@extends('layouts.user')

@section('title', 'Theo dõi đơn hàng')

@section('content')

@vite(['resources/css/orders/track.css', 'resources/js/orders/track.js'])
<body>
    <div class="track-page-header">
        <div class="header-content">
            <h1 class="header-title">Lịch Sử Đơn Hàng</h1>
            <p class="header-subtitle">Theo dõi và quản lý các đơn hàng của bạn</p>
        </div>
    </div>

    <div class="main-container">
        <div id="successNotification" class="success-notification" style="display: none;">
            <div class="notification-text">Thông tin đơn hàng đã được cập nhật thành công.</div>
        </div>

        <div class="filters-section">
            <h3 class="filters-title">Tìm kiếm đơn hàng</h3>
            <div class="filters-grid">
                <div class="filter-group">
                    <label class="filter-label">Trạng thái đơn hàng</label>
                    <select id="statusFilter" class="filter-input">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending">Chờ xác nhận</option>
                        <option value="processing">Đang chuẩn bị</option>
                        <option value="shipped">Đang giao hàng</option>
                        <option value="delivered">Đã giao hàng</option>
                        <option value="cancelled">Đã hủy</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Từ ngày</label>
                    <input type="date" id="dateFrom" class="filter-input">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Đến ngày</label>
                    <input type="date" id="dateTo" class="filter-input">
                </div>
            </div>
        </div>

        <div id="ordersContainer" class="orders-grid">
            <!-- Order 1 -->
            <div class="order-card">
                <div class="order-header">
                    <div class="order-meta">
                        <div class="order-id-section">
                            <div class="order-id">#BK2024001</div>
                            <div class="order-date">15/08/2024</div>
                        </div>
                        <div class="order-status status-delivered">
                            Đã giao hàng
                        </div>
                    </div>
                </div>

                <div class="order-content">
                    <div class="books-section">
                        <div class="books-title">Sách đã đặt mua</div>
                        <div class="book-item">
                            <span class="book-name">The Art of War - Sun Tzu (Bìa cứng)</span>
                            <span class="book-quantity">1</span>
                        </div>
                        <div class="book-item">
                            <span class="book-name">Sapiens: A Brief History of Humankind</span>
                            <span class="book-quantity">1</span>
                        </div>
                        <div class="book-item">
                            <span class="book-name">Atomic Habits - James Clear</span>
                            <span class="book-quantity">2</span>
                        </div>
                    </div>

                    <div class="order-details">
                        <div class="detail-card">
                            <div class="detail-label">Địa chỉ giao hàng</div>
                            <div class="detail-value">123 Nguyễn Du, Quận 1, TP.HCM</div>
                        </div>
                        <div class="detail-card">
                            <div class="detail-label">Số điện thoại</div>
                            <div class="detail-value">0912 345 678</div>
                        </div>
                    </div>

                    <div class="total-section">
                        <div class="total-label">Tổng giá trị</div>
                        <div class="total-amount">1.250.000₫</div>
                    </div>

                    <div class="action-buttons">
                        <button class="btn btn-primary">Huỷ đơn hàng</button>
                        <button class="btn btn-secondary">Hỗ trợ</button>
                    </div>
                </div>
            </div>

            <!-- Order 2 -->
            <div class="order-card">
                <div class="order-header">
                    <div class="order-meta">
                        <div class="order-id-section">
                            <div class="order-id">#BK2024002</div>
                            <div class="order-date">12/08/2024</div>
                        </div>
                        <div class="order-status status-shipped">
                            Đang giao hàng
                        </div>
                    </div>
                </div>

                <div class="order-content">
                    <div class="books-section">
                        <div class="books-title">Sách đã đặt mua</div>
                        <div class="book-item">
                            <span class="book-name">Homo Deus - Yuval Noah Harari</span>
                            <span class="book-quantity">1</span>
                        </div>
                        <div class="book-item">
                            <span class="book-name">21 Lessons for the 21st Century</span>
                            <span class="book-quantity">1</span>
                        </div>
                    </div>

                    <div class="order-details">
                        <div class="detail-card">
                            <div class="detail-label">Địa chỉ giao hàng</div>
                            <div class="detail-value">456 Lê Lợi, Quận 3, TP.HCM</div>
                        </div>
                        <div class="detail-card">
                            <div class="detail-label">Số điện thoại</div>
                            <div class="detail-value">0987 654 321</div>
                        </div>
                    </div>

                    <div class="total-section">
                        <div class="total-label">Tổng giá trị</div>
                        <div class="total-amount">890.000₫</div>
                    </div>

                    <div class="action-buttons">
                        <button class="btn btn-primary">Chi tiết đơn hàng</button>
                        <button class="btn btn-secondary">Hỗ trợ</button>
                    </div>
                </div>
            </div>

            <!-- Order 3 -->
            <div class="order-card">
                <div class="order-header">
                    <div class="order-meta">
                        <div class="order-id-section">
                            <div class="order-id">#BK2024003</div>
                            <div class="order-date">10/08/2024</div>
                        </div>
                        <div class="order-status status-processing">
                            Đang chuẩn bị
                        </div>
                    </div>
                </div>

                <div class="order-content">
                    <div class="books-section">
                        <div class="books-title">Sách đã đặt mua</div>
                        <div class="book-item">
                            <span class="book-name">The Psychology of Money</span>
                            <span class="book-quantity">1</span>
                        </div>
                        <div class="book-item">
                            <span class="book-name">Deep Work - Cal Newport</span>
                            <span class="book-quantity">1</span>
                        </div>
                    </div>

                    <div class="order-details">
                        <div class="detail-card">
                            <div class="detail-label">Địa chỉ giao hàng</div>
                            <div class="detail-value">789 Võ Văn Tần, Quận 3, TP.HCM</div>
                        </div>
                        <div class="detail-card">
                            <div class="detail-label">Số điện thoại</div>
                            <div class="detail-value">0901 234 567</div>
                        </div>
                    </div>

                    <div class="total-section">
                        <div class="total-label">Tổng giá trị</div>
                        <div class="total-amount">650.000₫</div>
                    </div>

                    <div class="action-buttons">
                        <button class="btn btn-primary">Huỷ đơn hàng</button>
                        <button class="btn btn-secondary">Hỗ trợ</button>
                    </div>
                </div>
            </div>

            <!-- Order 4 -->
            <div class="order-card">
                <div class="order-header">
                    <div class="order-meta">
                        <div class="order-id-section">
                            <div class="order-id">#BK2024004</div>
                            <div class="order-date">05/08/2024</div>
                        </div>
                        <div class="order-status status-pending">
                            Chờ xác nhận
                        </div>
                    </div>
                </div>

                <div class="order-content">
                    <div class="books-section">
                        <div class="books-title">Sách đã đặt mua</div>
                        <div class="book-item">
                            <span class="book-name">The Subtle Art of Not Giving a F*ck</span>
                            <span class="book-quantity">1</span>
                        </div>
                        <div class="book-item">
                            <span class="book-name">Thinking, Fast and Slow</span>
                            <span class="book-quantity">1</span>
                        </div>
                    </div>

                    <div class="order-details">
                        <div class="detail-card">
                            <div class="detail-label">Địa chỉ giao hàng</div>
                            <div class="detail-value">321 Hai Bà Trưng, Quận 1, TP.HCM</div>
                        </div>
                        <div class="detail-card">
                            <div class="detail-label">Số điện thoại</div>
                            <div class="detail-value">0934 567 890</div>
                        </div>
                    </div>

                    <div class="total-section">
                        <div class="total-label">Tổng giá trị</div>
                        <div class="total-amount">420.000₫</div>
                    </div>

                    <div class="action-buttons">
                        <button class="btn btn-primary">Huỷ đơn hàng</button>
                        <button class="btn btn-secondary">Hỗ trợ</button>
                    </div>
                </div>
            </div>

        </div>


        <div id="emptyState" class="empty-state" style="display: none;">
            <div class="empty-icon">📚</div>
            <h2 class="empty-title">Chưa có đơn hàng</h2>
            <p class="empty-message">Bạn chưa có đơn hàng nào. Hãy khám phá bộ sưu tập sách đặc biệt của chúng tôi.</p>
            <button class="empty-action" onclick="window.location.href='#'">Khám phá sách</button>
        </div>
    </div>
</body>
@endsection
