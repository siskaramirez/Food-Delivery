<style>
    body {
        background-color: #fff5f0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .navbar-floating-wrapper {
        margin-top: 20px;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 50px;
        padding: 5px 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.3);
        position: relative;
        z-index: 9999;
    }

    .navbar {
        background-color: transparent;
        padding: 10px 0;
    }

    .navbar-brand {
        font-weight: 800;
        font-size: 1.5rem;
        margin-left: 15px;
    }

    .center-nav {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .nav-link {
        color: #666;
        font-weight: 600;
        padding: 5px 0 !important;
        margin: 0 10px;
        transition: color 0.3s;
        position: relative;
        text-decoration: none;
    }

    .nav-link:hover,
    .nav-link.active {
        color: #ff6b6b;
    }

    .nav-link::after {
        content: "";
        position: absolute;
        bottom: -3px;
        left: 0;
        width: 0;
        height: 2px;
        background-color: #ff6b6b;
        transition: width 0.3s ease;
    }

    .nav-link:hover::after,
    .nav-link.active::after {
        width: 100%;
    }

    .search-container {
        position: relative;
        margin-left: 20px;
    }

    .search-input {
        border-radius: 50px;
        padding-left: 40px;
        background: white;
        border: 2px solid #eee;
        width: 250px;
        font-size: 0.9rem;
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 1rem;
    }

    .btn-cart {
        font-size: 1.3rem;
        color: #f68d8dff;
        font-weight: 700;
        text-decoration: none;
        margin-right: 15px;
        transition: color 0.3s;
        position: relative;
        padding-bottom: 5px;
        text-decoration: none;
    }

    .btn-cart:hover,
    .btn-cart.active {
        color: #f95d5dff;
    }

    .btn-cart::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 3px;
        background-color: #ff6b6b;
        transition: width 0.3s ease;
    }

    .btn-cart:hover::after,
    .btn-cart.active::after {
        width: 100%;
    }

    .btn-signup {
        background-color: transparent;
        color: #ff6b6b;
        border: 1px solid #ff6b6b;
        border-radius: 30px;
        padding: 6px 20px;
        font-weight: 600;
        transition: all 0.3s;
        text-decoration: none;
    }

    .btn-signup:hover {
        background-color: #ff6b6b;
        color: white;
    }

    .profile-circle {
        width: 38px;
        /*45*/
        height: 38px;
        /*45*/
        background-color: #ddd;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #e8e4e4ff;
        /*margin-left: 15px;*/
    }

    .profile-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .dropdown-toggle::after {
        display: none;
    }
</style>

<div class="container">
    <div class="navbar-floating-wrapper">
        <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand">EatsWay!</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#foodleNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="foodleNav">
                <div class="mx-auto d-flex align-items-center">
                    <ul class="navbar-nav center-nav">
                        @if(session('user_role') === 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home.admin') ? 'active' : '' }}" href="{{ route('home.admin') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('orders.admin') ? 'active' : '' }}" href="{{ route('orders.admin') }}">Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('menu.admin') ? 'active' : '' }}" href="{{ route('menu.admin') }}">Menu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('drivers.admin') ? 'active' : '' }}" href="{{ route('drivers.admin') }}">Drivers</a>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home.page') ? 'active' : '' }}" href="{{ route('home.page') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('menu.page') ? 'active' : '' }}" href="{{ route('menu.page') }}">Menu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('about.page') ? 'active' : '' }}" href="{{ route('about.page') }}">About Us</a>
                        </li>
                        @endif
                    </ul>

                    @php
                    $role = session('user_role');

                    $searchRoute = route('menu.page');
                    $placeholder = 'Search for food...';

                    if ($role === 'admin') {
                    if (request()->routeIs('home.admin')) {
                    $searchRoute = route('home.admin');
                    $placeholder = 'Search users or ID...';
                    } elseif (request()->routeIs('orders.admin')) {
                    $searchRoute = route('orders.admin');
                    $placeholder = 'Search order ID...';
                    } elseif (request()->routeIs('menu.admin')) {
                    $searchRoute = route('menu.admin');
                    $placeholder = 'Search for food...';
                    } elseif (request()->routeIs('drivers.admin')) {
                    $searchRoute = route('drivers.admin');
                    $placeholder = 'Search driver name or license...';
                    }
                    }
                    @endphp
                    <form action="{{ $searchRoute }}" method="GET" class="search-container d-none d-lg-block">
                        <span class="search-icon">🔎︎</span>
                        <input class="form-control search-input" type="search" name="search" placeholder="{{ $placeholder }}" value="{{ request('search') }}">
                    </form>
                </div>

                <div class="d-flex align-items-center">
                    @if(session('user_role') === 'admin')
                    <a href="{{ route('logout.submit') }}" class="btn btn-outline-secondary me-4" onclick="handleLogout()">Logout</a>
                    @elseif(Auth::check())
                    <a href="{{ route('cart.page') }}" class="btn-cart {{ request()->routeIs('cart.page') ? 'active' : '' }}" id="header-cart-btn">
                        My Cart<span id="header-cart-count" class="d-none ms-1">0</span>
                    </a>

                    <a href="{{ route('orders.page') }}" id="nav-orders-icon" class="ms-2 me-2" title="My Orders">
                        <div class="profile-circle">
                            <img src="{{ asset('images/orders.jpg') }}" alt="Orders">
                        </div>
                    </a>

                    <div class="nav-item dropdown">
                        <a class="dropdown-toggle p-0" href="#" id="profileDrop" data-bs-toggle="dropdown">
                            <div class="profile-circle ms-2">
                                <img src="{{ asset('images/profile.jpg') }}" alt="Profile">
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item" href="{{ route('profile.page') }}">My Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.history') }}">History</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="{{ route('logout.submit') }}" onclick="handleLogout()">Logout</a></li>
                        </ul>
                    </div>

                    @else
                    <a href="{{ route('signup.page') }}" id="nav-signup-btn" class="btn btn-signup ms-3 me-3">Sign Up</a>
                    @endif
                </div>
            </div>
        </nav>
    </div>
</div>

<script>
    function updateNavbar() {
        const isAuth = localStorage.getItem('eatsway_authenticated');
        const userEmail = localStorage.getItem('user_email');

        const signupBtn = document.getElementById('nav-signup-btn');
        const profileDropdown = document.getElementById('nav-profile-dropdown');
        const ordersIcon = document.getElementById('nav-orders-icon');

        const isFullyLoggedIn = userEmail && isAuth === 'true';

        if (isFullyLoggedIn) {
            if (signupBtn) signupBtn.classList.add('d-none');
            if (profileDropdown) profileDropdown.classList.remove('d-none');
            if (ordersIcon) ordersIcon.classList.remove('d-none');
        } else {
            if (signupBtn) signupBtn.classList.remove('d-none');
            if (profileDropdown) profileDropdown.classList.add('d-none');
            if (ordersIcon) ordersIcon.classList.add('d-none');
        }

        const hasCheckedOut = localStorage.getItem('active_order_num');
        const isCheckoutPage = window.location.pathname.includes('/checkout');

        if (hasCheckedOut && !isCheckoutPage) {
            localStorage.removeItem('eatsway_cart');
            localStorage.removeItem('active_order_num');
            localStorage.removeItem('user_payment');
        }

        const cart = JSON.parse(localStorage.getItem('eatsway_cart')) || [];
        const totalQuantity = cart.reduce((sum, item) => sum + (parseInt(item.qty || item.quantity) || 0), 0);
        const headerCount = document.getElementById('header-cart-count');

        if (headerCount) {
            if (totalQuantity > 0) {
                headerCount.innerText = `(${totalQuantity})`;
                headerCount.classList.remove('d-none');
            } else {
                headerCount.innerText = "0";
                headerCount.classList.add('d-none');
            }
        }
    }
    document.addEventListener('DOMContentLoaded', updateNavbar);

    function handleLogout() {
        localStorage.clear();
    }
</script>