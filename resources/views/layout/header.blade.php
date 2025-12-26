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
        gap: 10px;
    }

    .nav-link {
        color: #666;
        font-weight: 600;
        margin: 0 10px;
        transition: all 0.3s;
        border-bottom: 2px solid transparent;
    }

    .nav-link:hover {
        color: #ff6b6b;
        border-bottom: 2px solid #ff6b6b;
    }

    .search-container {
        position: relative;
        margin-left: 20px;
    }

    .search-input {
        border-radius: 50px;
        padding-left: 35px;
        background: white;
        border: 1px solid #eee;
        width: 250px;
        font-size: 0.9rem;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 1rem;
    }

    .btn-cart {
        font-size: 1.3rem;
        color: #ff6b6b;
        font-weight: 700;
        text-decoration: none;
        margin-right: 15px;
        transition: all 0.3s;
        padding-bottom: 1px;
        border-bottom: 2px solid transparent;
    }

    .btn-cart:hover {
        border-bottom: 2px solid #ff6b6b;
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
        width: 38px; /*45*/
        height: 38px; /*45*/
        background-color: #ddd;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #ddd;
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
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home.page') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('menu.page') }}">Menu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('about.page') }}">About Us</a>
                        </li>
                    </ul>
                    <form action="{{ route('menu.page') }}" method="GET" class="search-container d-none d-lg-block">
                        <span class="search-icon">🔎︎</span>
                        <input
                            class="form-control search-input"
                            type="search"
                            name="search"
                            placeholder="Search for food..."
                            aria-label="Search"
                            value="{{ request('search') }}">
                    </form>
                </div>

                <div class="d-flex align-items-center">
                    <a href="{{ route('cart.page') }}" class="btn-cart" id="header-cart-btn">My Cart<span id="header-cart-count" class="d-none ms-1">0</span></a>
                    <a href="{{ route('signup.page') }}" class="btn btn-signup ms-3 me-3">Sign Up</a>

                    <div class="nav-item dropdown">
                        <a class="dropdown-toggle p-0" href="#" id="profileDrop" role="button" data-bs-toggle="dropdown">
                            <div class="profile-circle">
                                <img src="{{ asset('images/profile.jpg') }}" alt="Profile">
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item" href="{{ route('profile.page') }}">My Profile</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('signin.page') }}" onclick="handleLogout()">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>

<script>
    function updateHeaderCartCount() {
        const cart = JSON.parse(localStorage.getItem('eatsway_cart')) || [];

        const totalQuantity = cart.reduce((sum, item) => sum + item.qty, 0);

        const headerCount = document.getElementById('header-cart-count');
        if (headerCount) {
            if (totalQuantity > 0) {
                headerCount.innerText = `(${totalQuantity})`;
                headerCount.classList.remove('d-none');
            } else {
                headerCount.classList.add('d-none');
            }
        }
    }
    document.addEventListener('DOMContentLoaded', updateHeaderCartCount);

    function handleLogout() {
        localStorage.clear();
    }
</script>