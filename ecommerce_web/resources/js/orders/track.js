function formatAddress(address) {
    if (!address) return 'Chưa có';
    let parts = [
        address.details,
        address.ward,
        address.district,
        address.city,
        address.country
    ];
    if (address.postal_code) {
        parts.push(address.postal_code);
    }
    return parts.filter(Boolean).join(', ');
}

function getStatusClass(status) {
    const statusMap = {
        'pending': 'status-pending',
        'processing': 'status-processing',
        'completed': 'status-completed',
        'cancelled': 'status-cancelled',
        'return': 'status-return'
    };
    return statusMap[status] || 'status-pending';
}

function getStatusText(status) {
    const statusMap = {
        'pending': 'Chờ xác nhận',
        'processing': 'Đang xử lý',
        'completed': 'Hoàn thành',
        'cancelled': 'Đã hủy',
        'return': 'Trả hàng'
    };
    return statusMap[status] || 'Không xác định';
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount) + '₫';
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

// Lấy dữ liệu orders từ server
const ordersData = window.ordersData || [];
let filteredOrders = ordersData;

function filterOrders() {
    const statusFilter = document.getElementById('statusFilter').value;
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;

    filteredOrders = ordersData.filter(order => {
        const matchStatus = !statusFilter || order.status === statusFilter;
        const matchDateFrom = !dateFrom || new Date(order.created_at) >= new Date(dateFrom);
        const matchDateTo = !dateTo || new Date(order.created_at) <= new Date(dateTo);
        return matchStatus && matchDateFrom && matchDateTo;
    });

    renderOrders(filteredOrders);
}

function renderOrders(orders) {
    const container = document.getElementById('ordersContainer');
    const emptyState = document.getElementById('emptyState');

    container.innerHTML = ''; // clear cũ

    if (orders.length === 0) {
        emptyState.style.display = 'block';
        return;
    }

    emptyState.style.display = 'none';

    orders.forEach(order => {
        const orderCard = document.createElement('div');
        orderCard.classList.add('order-card');
        orderCard.innerHTML = `
            <div class="order-header">
                <div class="order-meta">
                    <div class="order-id-section">
                        <div class="order-id">#${order.id}</div>
                        <div class="order-date">${formatDate(order.created_at)}</div>
                    </div>
                    <div class="order-status ${getStatusClass(order.status)}">
                        ${getStatusText(order.status)}
                    </div>
                </div>
            </div>
            <div class="order-content">
                <div class="books-section">
                    <div class="books-title">Sách đã đặt mua</div>
                    ${order.order_items.map(item => `
                        <div class="book-item">
                            <span class="book-name">${item.product.name}</span>
                            <span class="book-quantity">${item.quantity}</span>
                        </div>
                    `).join('')}
                </div>
                <div class="order-details">
                    <div class="detail-card">
                        <div class="detail-label">Địa chỉ giao hàng</div>
                        <div class="detail-value">${formatAddress(order.address)}</div>
                    </div>
                    <div class="detail-card">
                        <div class="detail-label">Số điện thoại</div>
                        <div class="detail-value">${order.address?.phone_number || 'Chưa có'}</div>
                    </div>
                </div>
                <div class="total-section">
                    <div class="total-label">Tổng giá trị</div>
                    <div class="total-amount">${formatCurrency(order.total_price)}</div>
                </div>
            </div>
        `;
        container.appendChild(orderCard);
    });
}

// Event listeners
document.getElementById('statusFilter').addEventListener('change', filterOrders);
document.getElementById('dateFrom').addEventListener('change', filterOrders);
document.getElementById('dateTo').addEventListener('change', filterOrders);

// Khởi tạo trang
document.addEventListener('DOMContentLoaded', () => {
    renderOrders(filteredOrders);
});