@extends('layouts.user')

@section('title', 'Danh sách sản phẩm')

@section('content')

@vite(['resources/css/products/index.css', 'resources/js/products/index.js'])

<body>
    <div class="container">
        <!-- Header -->
        <header class="product-page-header">
            <h1 class="product-page-title">Đọc gì hôm nay</h1>
            <p class="product-page-subtitle">Khám phá cuốn sách yêu thích tiếp theo của bạn</p>
        </header>

        <!-- Filter Section -->
        <form method="GET" action="{{ route('products.viewall') }}" class="filter-section">
            {{-- Lọc theo thể loại --}}
            <div class="filter-group">
                <label class="filter-label">Thể loại:</label>
                <select class="filter-select" id="categoryFilter" name="category">
                    <option value="">Tất cả thể loại</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                            {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            {{-- Sắp xếp --}}
            <div class="filter-group">
                <label class="filter-label">Sắp xếp theo:</label>
                <select class="filter-select" id="sortFilter" name="sort">
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên sách</option>
                    <option value="price-low" {{ request('sort') == 'price-low' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                    <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                </select>
            </div>
            
            {{-- Tìm kiếm --}}
            <div class="filter-group">
                <input type="text" class="search-input" placeholder="Tìm kiếm tên sách hoặc tác giả..." 
                    id="searchInput" name="keyword" value="{{ request('keyword') }}">
            </div>

            <div class="filter-group">
                <button type="submit" class="btn-search">Tìm kiếm</button>
            </div>
        </form>


        <!-- Product Grid -->
            <div class="product-list" id="productList">
        @foreach ($products as $product)
                <div class="product-card fade-in stagger-delay-{{ $loop->iteration }}" 
                    data-category="{{ $product->category->name ?? 'unknown' }}" 
                    data-name="{{ $product->name }}" 
                    data-price="{{ $product->price }}">
                    
                    <!-- Hình ảnh -->
                    <div class="product-image-wrapper">
                        <a href="{{ route('products.detail', $product->id) }}">
                            <img src="{{ $product->image_url ?? 'https://via.placeholder.com/400x400?text=No+Image' }}" 
                         alt="{{ $product->name }}">
                </a>
                    </div>
                    
                    <!-- Nội dung sản phẩm -->
                    <div class="product-content">
                        <h3 class="product-title">
                            <a href="{{ route('products.detail', $product->id) }}">{{ $product->name }}</a>
                </h3>

                        <p class="product-price">{{ number_format($product->price, 0, ',', '.') }} VNĐ</p>

                        <form class="cart-form" onsubmit="addToCart(event, '{{ $product->name }}')">
                    <input type="hidden" name="name" value="{{ $product->name }}">
                    <input type="hidden" name="price" value="{{ $product->price }}">
                            <input type="hidden" name="image" value="{{ $product->image_url ?? 'https://via.placeholder.com/400x400?text=No+Image' }}">
                    <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn-add-cart">Add to Cart</button>
                </form>

                        <a href="{{ route('products.detail', $product->id) }}" class="product-details-link">View Details</a>
                    </div>
                </div>
            @endforeach
            </div>
        {{-- Hiển thị pagination --}}
        <div class="pagination-wrapper">
            {{ $products->links('pagination::simple-default') }}
        </div>
    </div>
</body>
@endsection
