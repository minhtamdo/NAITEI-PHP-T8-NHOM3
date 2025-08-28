import { useState } from 'react';
import { useForm } from '@inertiajs/react';
import { useEffect } from 'react';
import { router } from '@inertiajs/react';
import { CheckCircleIcon, XCircleIcon } from 'lucide-react';

export default function OrderManager({ orders, onConfirmOrder, processing }) {
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 15;

  const orderForm = useForm({
    status: ''
  });

  const [filters, setFilters] = useState({
    status: '',
    startDate: '',
    endDate: ''
  });
  const [selectedStatus, setSelectedStatus] = useState({});
  const [filteredOrders, setFilteredOrders] = useState(orders);
  const [editingOrder, setEditingOrder] = useState(null);
  const [viewingOrder, setViewingOrder] = useState(null);
  const [notifications, setNotifications] = useState([]);


  // Function to add a notification
  const addNotification = (type, message) => {
    const id = Date.now();
    const newNotification = { id, type, message };
    setNotifications(prev => [...prev, newNotification]);
    
    // Auto remove after 30 seconds
    setTimeout(() => {
      removeNotification(id);
    }, 30000);
  };

  // Function to remove a notification
  const removeNotification = (id) => {
    setNotifications(prev => prev.filter(notification => notification.id !== id));
  };

  // Sync filteredOrders with orders props when changed
  useEffect(() => {
    setFilteredOrders(orders);
  }, [orders]);
  const sortedOrders = filteredOrders.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
  const paginatedOrders = sortedOrders.slice(
    (currentPage - 1) * itemsPerPage,
    currentPage * itemsPerPage
  );

  // Handle Find button
  const handleFindOrders = () => {
    let result = orders;

    if (filters.status) {
      result = result.filter(
        (order) => order.status.toLowerCase() === filters.status.toLowerCase()
      );
    }

    if (filters.startDate && filters.endDate) {
      const startDate = new Date(filters.startDate);
      const endDate = new Date(filters.endDate);
      endDate.setHours(23, 59, 59, 999); // Set to end of day

      result = result.filter((order) => {
        const orderDate = new Date(order.created_at);
        return orderDate >= startDate && orderDate <= endDate;
      });
    }

    setFilteredOrders(result);
  };

  // Format datetime
  const formatDateTime = (dateString) => {
    const date = new Date(dateString);
    if (isNaN(date)) return dateString;
    return date.toLocaleString();
  };

  // Handle start date change
  const handleStartDateChange = (date) => {
    setFilters(prev => ({
      ...prev,
      startDate: date,
      endDate: prev.endDate && new Date(prev.endDate) < new Date(date) ? date : prev.endDate
    }));
  };

  // Start editing order status
  const handleStartEditOrder = (order) => {
    setEditingOrder(order.id);
    setSelectedStatus(prev => ({
      ...prev,
      [order.id]: order.status
    }));

    orderForm.setData({
      status: order.status
    });
  };

  // Handle status change in select
  const handleStatusChange = (orderId, newStatus) => {
    setSelectedStatus(prev => ({
      ...prev,
      [orderId]: newStatus
    }));

    orderForm.setData('status', newStatus);
  };

  // Save new status
  const handleSaveStatus = (orderId) => {
    const newStatus = selectedStatus[orderId];

    if (!newStatus) {
      alert('Please select a status');
      return;
    }
    orderForm.setData('status', newStatus);
    orderForm.put(route('admin.orders.update', orderId), {
      onSuccess: () => {
        setEditingOrder(null);
        setSelectedStatus(prev => {
          const newState = { ...prev };
          delete newState[orderId];
          return newState;
        });
        addNotification('success', 'Order status updated successfully');
      },
      onError: (errors) => {
        console.error('Error updating order status:', errors);
        addNotification('error', 'Failed to update order status');
      }
    });
  };

  // Cancel edit
  const handleCancelEdit = (orderId) => {
    setEditingOrder(null);
    setSelectedStatus(prev => {
      const newState = { ...prev };
      delete newState[orderId];
      return newState;
    });
  };

  // View order details
  const handleViewOrder = (order) => {
    setViewingOrder(order);
  };

  // Close modal
  const handleCloseModal = () => {
    setViewingOrder(null);
  };

  return (
    <div className="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
      {/* Notification Container */}
      <div className="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 space-y-2 w-80">
        {notifications.map((notification) => (
          <div 
            key={notification.id} 
            className={`relative p-4 rounded-md shadow-lg border-l-4 ${
              notification.type === 'success' 
                ? 'bg-green-100 border-green-500 text-green-700' 
                : 'bg-red-100 border-red-500 text-red-700'
            } fade in`}
          >
            <button
              onClick={() => removeNotification(notification.id)}
              className="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
            >
              &times;
            </button>
            <strong className="block">
              {notification.type === 'success' ? 'Success!' : 'Error!'}
            </strong>
            <span>{notification.message}</span>
          </div>
        ))}
      </div>
      <div className="p-6">
        <h3 className="text-lg font-medium mb-4">Manage Orders</h3>

        {/* Filters */}
        <div className="flex flex-col md:flex-row gap-4 mb-4">
          <select
            className="w-full md:w-48 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white"
            value={filters.status}
            onChange={(e) =>
              setFilters((prev) => ({ ...prev, status: e.target.value }))
            }
          >
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
            <option value="return">Return</option>
          </select>

          <div className="flex items-center gap-2">
            <input
              type="date"
              className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white"
              value={filters.startDate}
              onChange={(e) => handleStartDateChange(e.target.value)}
              max={filters.endDate || undefined}
            />
            <span className="text-gray-500 dark:text-gray-400">to</span>
            <input
              type="date"
              className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white"
              value={filters.endDate}
              onChange={(e) => setFilters(prev => ({ ...prev, endDate: e.target.value }))}
              min={filters.startDate || undefined}
            />
          </div>

          <button
            onClick={() => setFilters({ status: '', startDate: '', endDate: '' })}
            className="px-3 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600"
          >
            Clear Filters
          </button>
          <button
            onClick={handleFindOrders}
            className="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
          >
            Find
          </button>
        </div>

        <div className="overflow-x-auto">
          <table className="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead className="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Order ID</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Customer</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Order date</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody className="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              {paginatedOrders.map((order) => (
                <tr key={order.id}>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{order.id}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{order.user?.name || 'Guest'}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{Number(order.total_price).toLocaleString("vi-VN")} VND</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{formatDateTime(order.created_at)}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                    {editingOrder === order.id ? (
                      <select
                        className="px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white"
                        value={selectedStatus[order.id] || order.status}
                        onChange={(e) => handleStatusChange(order.id, e.target.value)}
                      >
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="return">Return</option>
                      </select>
                    ) : (
                      <span className={`px-2 py-1 rounded-full text-xs font-medium ${
                        order.status === 'completed' ? 'bg-green-100 text-green-800' :
                        order.status === 'processing' ? 'bg-blue-100 text-blue-800' :
                        order.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                        order.status === 'cancelled' ? 'bg-red-100 text-red-800' :
                        'bg-purple-100 text-purple-800'
                      }`}>
                        {order.status}
                      </span>
                    )}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                    {editingOrder === order.id ? (
                      <div className="flex gap-2">
                        <button
                          onClick={() => handleSaveStatus(order.id)}
                          className="px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700"
                          disabled={orderForm.processing}
                        >
                          {orderForm.processing ? 'Saving...' : 'Save'}
                        </button>
                        <button
                          onClick={() => handleCancelEdit(order.id)}
                          className="px-3 py-1 bg-gray-500 text-white rounded-md hover:bg-gray-600"
                        >
                          Cancel
                        </button>
                      </div>
                    ) : (
                      <div className="flex gap-2">
                        <button
                          onClick={() => handleStartEditOrder(order)}
                          className="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                        >
                          Change Status
                        </button>
                        <button
                          onClick={() => handleViewOrder(order)}
                          className="px-3 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                        >
                          View Order
                        </button>
                        <button
                          onClick={() => onConfirmOrder(order.id)}
                          disabled={order.status !== 'pending' || processing[order.id]}
                          className={`px-3 py-1 rounded-md ${
            order.status === 'pending' && !processing[order.id]
              ? 'bg-green-200 dark:bg-green-600 text-green-800 dark:text-white hover:bg-green-300 dark:hover:bg-green-500'
              : 'bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed'
          }`}
                        >
                          {processing[order.id] ? 'confirming' : 'accept'}
                        </button>
                      </div>
                    )}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        {/* Pagination */}
        <div className="flex justify-between items-center mt-4">
          <button
            disabled={currentPage === 1}
            onClick={() => setCurrentPage(currentPage - 1)}
            className="px-3 py-1 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-white rounded-md disabled:opacity-50"
          >
            Previous
          </button>
          <span className="text-gray-700 dark:text-gray-300">Page {currentPage} of {Math.ceil(filteredOrders.length / itemsPerPage)}</span>
          <button
            disabled={currentPage === Math.ceil(filteredOrders.length / itemsPerPage)}
            onClick={() => setCurrentPage(currentPage + 1)}
            className="px-3 py-1 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-white rounded-md disabled:opacity-50"
          >
            Next
          </button>
        </div>

        {/* Modal for Viewing Order Details */}
        {viewingOrder && (
          <div className="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
            <div className="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-2xl">
              <h3 className="text-lg font-medium mb-4 dark:text-white">Order Details - ID: {viewingOrder.id}</h3>
              <div className="space-y-4">
                <div>
                  <h4 className="font-semibold dark:text-gray-300">Customer Information</h4>
                  <p><strong>Name:</strong> {viewingOrder.user?.name || 'Guest'}</p>
                  <p><strong>Email:</strong> {viewingOrder.user?.email || 'N/A'}</p>
                </div>
                <div>
                  <h4 className="font-semibold dark:text-gray-300">Shipping Address</h4>
                  <p>{viewingOrder.address?.street || 'N/A'}, {viewingOrder.address?.city || 'N/A'}, {viewingOrder.address?.country || 'N/A'}</p>
                </div>
                <div>
                  <h4 className="font-semibold dark:text-gray-300">Order Items</h4>
                  <ul className="list-disc pl-5">
                    {viewingOrder.order_items.map((item, index) => (
                      <li key={index}>
                        <strong>{item.product?.name || 'Unknown Product'}</strong> - Quantity: {item.quantity}, Price: {Number(item.product?.price * item.quantity).toLocaleString("vi-VN")} VND
                      </li>
                    ))}
                  </ul>
                </div>
                <div>
                  <h4 className="font-semibold dark:text-gray-300">Order Summary</h4>
                  <p><strong>Total Price:</strong> {Number(viewingOrder.total_price).toLocaleString("vi-VN")} VND</p>
                  <p><strong>Status:</strong> {viewingOrder.status}</p>
                  <p><strong>Order Date:</strong> {formatDateTime(viewingOrder.created_at)}</p>
                </div>
              </div>
              <button
                onClick={handleCloseModal}
                className="mt-4 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
              >
                Close
              </button>
            </div>
          </div>
        )}
      </div>
      <style jsx>{`
        .fade.in {
          opacity: 1;
          transition: opacity 0.5s;
        }
        .fade {
          opacity: 0;
          transition: opacity 0.5s;
        }
      `}</style>
    </div>
  );
}