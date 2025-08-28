function getStatusClass(status) {
    const statusMap = {
        'pending': 'status-pending',
        'processing': 'status-processing',
        'shipped': 'status-shipped',
        'delivered': 'status-delivered',
        'cancelled': 'status-cancelled'
    };
    return statusMap[status] || 'status-pending';
}

function getStatusText(status) {
    const statusMap = {
        'pending': 'Chờ xác nhận',
        'processing': 'Đang chuẩn bị',
        'shipped': 'Đang giao hàng',
        'delivered': 'Đã giao hàng',
        'cancelled': 'Đã hủy'
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

function trackOrder(orderId) {
    const notification = document.getElementById('successNotification');
    notification.querySelector('.notification-text').textContent = `Đang tải thông tin chi tiết đơn hàng ${orderId}...`;
    notification.style.display = 'block';
    
    setTimeout(() => {
        notification.style.display = 'none';
    }, 3000);
}

function contactSupport(orderId) {
    const notification = document.getElementById('successNotification');
    notification.querySelector('.notification-text').textContent = `Yêu cầu hỗ trợ cho đơn hàng ${orderId} đã được gửi.`;
    notification.style.display = 'block';
    
    setTimeout(() => {
        notification.style.display = 'none';
    }, 3000);
}

// Event listeners
document.getElementById('statusFilter').addEventListener('change', filterOrders);
document.getElementById('dateFrom').addEventListener('change', filterOrders);
document.getElementById('dateTo').addEventListener('change', filterOrders);

function renderOrders(orders) {
    const container = document.getElementById('ordersContainer');
    const emptyState = document.getElementById('emptyState');
    
    if (orders.length === 0) {
        // Ẩn tất cả order cards
        const orderCards = container.querySelectorAll('.order-card');
        orderCards.forEach(card => card.style.display = 'none');
        emptyState.style.display = 'block';
    } else {
        emptyState.style.display = 'none';
        // Hiển thị/ẩn order cards dựa trên filter
        const allOrderCards = container.querySelectorAll('.order-card');
        allOrderCards.forEach((card, index) => {
            if (index < orders.length) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
}

// Khởi tạo trang
document.addEventListener('DOMContentLoaded', () => {
    filteredOrders = ordersData;
    renderOrders(filteredOrders);
});