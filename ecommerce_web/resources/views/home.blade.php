@extends('layouts.user')

@section('title', 'Trang chủ - Hiệu sách BlueMoon')

@vite(['resources/css/home.css', 'resources/js/home.js'])

@section('content')
<body>
    <div class="container">
        <!-- Header -->
        <header class="header fade-in">
            <h1 class="header-title">BlueMoon</h1>
            <a href="{{ route('products.viewall') }}" class="btn-primary">Khám phá</a>
        </header>

        <!-- Featured Books -->
        <section class="section" id="books">
            <h2 class="section-title fade-in">Sách Nổi Bật</h2>
            
            <div class="product-grid">
            @foreach ($products->skip(1)->take(4) as $product)
                <article class="product-card fade-in">
                    <a href="{{ route('products.detail', $product->id) }}">
                        <img src="{{ $product->image_url ?? 'https://via.placeholder.com/400x300?text=No+Image' }}"
                            class="product-image"
                            alt="{{ $product->name }}">
                    </a>
                    <div class="product-content">
                        <h3 class="product-title">{{ $product->name }}</h3>
                        <p class="product-price">{{ number_format($product->price, 0, ',', '.') }} ₫</p>
                        <a href="{{ route('products.detail', $product->id) }}" class="btn-secondary">
                            Xem chi tiết
                        </a>
                    </div>
                </article>
        @endforeach
        </div>
        </section>

        <!-- Promotion -->
        <section class="promotion fade-in">
            <h2 class="promotion-title">Khuyến mãi đặc biệt</h2>
            <p class="promotion-text">Giảm giá 30% sách văn học Việt Nam</p>
            <a href="{{ route('products.viewall', ['category' => 1, 'sort' => 'name', 'keyword' => '']) }}" class="btn-gold">
                Xem thêm
            </a>
        </section>

        <!-- Contact -->
        <section class="section">
            <h2 class="section-title fade-in">Thông tin</h2>
            <div class="contact-grid">
                <div class="contact-item fade-in">
                    <h3 class="contact-title">Địa chỉ</h3>
                    <div class="contact-info">
                        Phố Sách 19/12<br>
                        Quận Hoàn Kiếm, Hà Nội
                    </div>
                </div>
                
                <div class="contact-item fade-in">
                    <h3 class="contact-title">Liên hệ</h3>
                    <div class="contact-info">
                        <strong>0123 456 789</strong><br>
                        support@bluemoonbookstore.com
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</div>
@endsection
