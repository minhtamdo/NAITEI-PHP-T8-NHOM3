@vite(['resources/css/partials/user/header.css', 'resources/js/partials/user/header.js'])
<body>
    <header class="page-header">
    <nav>
            <ul id="navMenu">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Trang chủ</a></li>
                <li><a href="{{ route('products.viewall') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">Sản phẩm</a></li>
                <li><a href="{{ route('cart.index') }}" class="{{ request()->routeIs('cart.*') ? 'active' : '' }}">Giỏ hàng</a></li>
                <li><a href="{{ route('orders.track') }}" class="{{ request()->routeIs('orders.track') ? 'active' : '' }}">Theo dõi đơn hàng</a></li>
                <li class="user-menu">
                    <!-- Authenticated User -->
                    <div id="authUser" style="display: none;">
                        <a href="#" class="btn btn-primary" onclick="toggleDropdown(event)">Xem Profile</a>
                        <div class="user-dropdown" id="userDropdown">
                            <a href="#">Profile</a>
                            <a href="#">Đơn hàng</a>
                            <a href="#">Cài đặt</a>
                            <a href="#" onclick="logout()">Đăng xuất</a>
                        </div>
                    </div>
                    <!-- Guest User -->
                    <div id="guestUser">
                        <a href="{{ route('login') }}" class="btn btn-secondari">Đăng nhập</a>
                    </div>
            </li>
        </ul>
            <button class="mobile-toggle" onclick="toggleMenu()">☰</button>
    </nav>
</header>
</body>
