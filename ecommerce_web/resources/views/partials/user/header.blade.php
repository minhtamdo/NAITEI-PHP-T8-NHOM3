<header>
    <nav>
        <ul>
            <li><a href="/">Trang chủ</a></li>
            <li><a href="/products">Sản phẩm</a></li>
            <li><a href="{{ route('cart.index') }}">Giỏ hàng</a></li>
            <li><a href="{{ route('orders.track') }}">Theo dõi đơn hàng</a></li>
            <li style="margin-left: auto;">
                @auth
                    <a href="{{ route('profile') }}" class="btn btn-primary">Xem Profile</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary">Đăng nhập</a>
                @endauth
            </li>
        </ul>
    </nav>
</header>
