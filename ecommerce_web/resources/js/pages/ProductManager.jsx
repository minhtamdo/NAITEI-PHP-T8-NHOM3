import { useState } from 'react';
import { useForm } from '@inertiajs/react';

export default function ProductManager({ products, categories }) {
  const [currentPage, setCurrentPage] = useState(1);
  const [editingProduct, setEditingProduct] = useState(null);
  const itemsPerPage = 15;

  const productForm = useForm({
    name: '',
    description: '',
    price: '',
    stock: '',
    category_id: '',
    image: null,
    author: '',
  });
  const [notifications, setNotifications] = useState([]);

  const addNotification = (type, message) => {
    const id = Date.now();
    const newNotification = { id, type, message };
    setNotifications((prev) => [...prev, newNotification]);

    setTimeout(() => {
      removeNotification(id);
    }, 30000);
  };

  const removeNotification = (id) => {
    setNotifications((prev) => prev.filter((notification) => notification.id !== id));
  };

  const sortedProducts = products.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at));
  const paginatedProducts = sortedProducts.slice(
    (currentPage - 1) * itemsPerPage,
    currentPage * itemsPerPage
  );

  const handleProductSubmit = (e) => {
    e.preventDefault();
    
    // Debug: Log form data
    console.log('Form data before submit:', {
      name: productForm.data.name,
      image: productForm.data.image,
      hasImage: !!productForm.data.image
    });
    
    const formData = new FormData();
    formData.append('name', productForm.data.name);
    formData.append('description', productForm.data.description || '');
    formData.append('price', productForm.data.price);
    formData.append('stock', productForm.data.stock);
    formData.append('category_id', productForm.data.category_id);
    formData.append('author', productForm.data.author);
    
    if (productForm.data.image) {
      console.log('Adding image to form:', productForm.data.image.name);
      formData.append('image', productForm.data.image);
    }

    if (editingProduct) {
      productForm.put(route('admin.products.update', editingProduct.id), {
        data: formData,
        forceFormData: true, // Ensure FormData is used
        onSuccess: () => {
          setEditingProduct(null);
          productForm.reset();
          addNotification('success', 'Product updated successfully');
        },
        onError: (errors) => {
          addNotification('error', 'Failed to update product');
        },
      });
    } else {
      productForm.post(route('admin.products.store'), {
        data: formData,
        forceFormData: true, // Ensure FormData is used
        onSuccess: () => {
          productForm.reset();
          addNotification('success', 'Product created successfully');
        },
        onError: (errors) => {
          addNotification('error', 'Failed to create product');
        },
      });
    }
  };

  const handleEditProduct = (product) => {
    setEditingProduct(product);
    productForm.setData({
      name: product.name,
      description: product.description || '',
      price: product.price,
      stock: product.stock,
      category_id: product.category_id,
      image: null, // File input cannot be pre-filled
      author: product.author || '',
    });
  };

  const handleDeleteProduct = (productId) => {
    if (confirm('Are you sure you want to delete this product?')) {
      productForm.delete(route('admin.products.destroy', productId), {
        onSuccess: () => {
          addNotification('success', 'Product deleted successfully');
        },
        onError: () => {
          addNotification('error', 'Failed to delete product');
        },
      });
    }
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
        <h3 className="text-lg font-medium mb-4 dark:text-white">Manage Products</h3>

        {/* Product Form */}
        <form onSubmit={handleProductSubmit} className="grid gap-4 mb-6" encType="multipart/form-data">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input
              type="text"
              placeholder="Product name"
              className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
              value={productForm.data.name}
              onChange={(e) => productForm.setData('name', e.target.value)}
              required
            />
            <div className="relative">
              <input
                type="number"
                placeholder="Price"
                className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 w-full pr-16"
                value={productForm.data.price || ''}
                onChange={(e) => {
                  const value = e.target.value === '' ? '' : Number(e.target.value);
                  productForm.setData('price', value);
                }}
                required
              />
              <span className="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400">
                VND
              </span>
            </div>
            <input
              type="text"
              placeholder="Author"
              className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
              value={productForm.data.author}
              onChange={(e) => productForm.setData('author', e.target.value)}
              required
            />
          </div>

          <textarea
            placeholder="Description"
            rows={3}
            className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
            value={productForm.data.description}
            onChange={(e) => productForm.setData('description', e.target.value)}
          />

          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input
              type="number"
              placeholder="Stock quantity"
              className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
              value={productForm.data.stock || ''}
              onChange={(e) => {
                const value = e.target.value === '' ? '' : Number(e.target.value);
                productForm.setData('stock', value);
              }}
              required
            />
            <select
              className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
              value={productForm.data.category_id}
              onChange={(e) => productForm.setData('category_id', e.target.value)}
              required
            >
              <option value="">Select category</option>
              {categories.map((category) => (
                <option key={category.id} value={category.id}>{category.name}</option>
              ))}
            </select>
          </div>

          <input
            type="file"
            accept="image/*"
            className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
            onChange={(e) => productForm.setData('image', e.target.files[0])}
          />

          {/* Preview selected image */}
          {productForm.data.image && (
            <div className="mt-2">
              <p className="text-sm text-gray-600 dark:text-gray-400">Selected image preview:</p>
              <img
                src={URL.createObjectURL(productForm.data.image)}
                alt="Preview"
                className="h-20 w-20 object-cover rounded-md border"
              />
            </div>
          )}

          {editingProduct && editingProduct.image_url && (
            <div className="mt-2">
              <p className="text-sm text-gray-600 dark:text-gray-400">Current image:</p>
              <img
                src={editingProduct.image_url.startsWith('http') 
                  ? editingProduct.image_url 
                  : `http://127.0.0.1:8000/storage/${editingProduct.image_url}`}
                alt="Current product"
                className="h-20 w-20 object-cover rounded-md border"
                onError={(e) => { 
                  e.target.src = 'http://127.0.0.1:8000/images/no_image.png'; 
                }}
              />
            </div>
          )}

          <div className="flex gap-2">
            <button
              type="submit"
              className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500"
              disabled={productForm.processing}
            >
              {editingProduct ? 'Update Product' : 'Add Product'}
            </button>
            {editingProduct && (
              <button
                type="button"
                onClick={() => {
                  setEditingProduct(null);
                  productForm.reset();
                }}
                className="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600"
              >
                Cancel
              </button>
            )}
          </div>
        </form>

        {Object.keys(productForm.errors).length > 0 && (
          <div className="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
            <strong>Lỗi:</strong>
            <ul className="mt-2 list-disc list-inside">
              {Object.values(productForm.errors).map((error, index) => (
                <li key={index}>{error}</li>
              ))}
            </ul>
          </div>
        )}

        {/* Products Table */}
        <div className="overflow-x-auto">
          <table className="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead className="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Author</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stock</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Image</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody className="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              {paginatedProducts.map((product) => (
                <tr key={product.id}>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{product.id}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{product.name}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{product.author || 'N/A'}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{Number(product.price).toLocaleString('vi-VN')} VND</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{product.stock}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{product.category?.name || 'N/A'}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                    <img
                      src={product.image_url?.startsWith('http') 
                        ? product.image_url 
                        : `http://127.0.0.1:8000/storage/${product.image_url || 'images/no_image.png'}`}
                      alt={product.name}
                      className="h-10 w-10 object-cover rounded-md border"
                      onError={(e) => { 
                        e.target.src = 'http://127.0.0.1:8000/images/no_image.png'; 
                      }}
                    />
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                    <button
                      onClick={() => handleEditProduct(product)}
                      className="mr-2 px-3 py-1 bg-blue-200 dark:bg-blue-600 text-blue-800 dark:text-white rounded-md hover:bg-blue-300 dark:hover:bg-blue-500"
                    >
                      Edit
                    </button>
                    <button
                      onClick={() => handleDeleteProduct(product.id)}
                      className="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700"
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        <div className="flex justify-between items-center mt-4">
          <button
            disabled={currentPage === 1}
            onClick={() => setCurrentPage(currentPage - 1)}
            className="px-3 py-1 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-white rounded-md disabled:opacity-50"
          >
            Previous
          </button>
          <span className="text-gray-700 dark:text-gray-300">
            Page {currentPage} of {Math.ceil(products.length / itemsPerPage)}
          </span>
          <button
            disabled={currentPage === Math.ceil(products.length / itemsPerPage)}
            onClick={() => setCurrentPage(currentPage + 1)}
            className="px-3 py-1 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-white rounded-md disabled:opacity-50"
          >
            Next
          </button>
        </div>
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

