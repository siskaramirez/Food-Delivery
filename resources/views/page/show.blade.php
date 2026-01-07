@extends('layout.main')
@section('content')

<style>
    .main-show-card {
        background: white;
        border-radius: 40px;
        overflow: hidden;
        display: flex;
        align-items: stretch;
    }

    .image-side-wrapper {
        padding: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .food-img-bg-container {
        background-color: #efc9b7ff;
        border-radius: 30px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 20px;
    }

    .main-detail-img {
        width: 100%;
        height: 500px;
        object-fit: cover;
        border-radius: 25px;
    }

    .back-btn-circle {
        position: absolute;
        top: 40px;
        left: 40px;
        width: 60px;
        height: 60px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        z-index: 10;
        transition: 0.3s ease;
    }

    .back-arrow {
        color: #333;
        font-size: 1.8rem;
        font-weight: bold;
        line-height: 1;
        margin-top: -4px;
    }

    .back-btn-circle:hover {
        background: #ff6b6b;
        transform: translateX(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .back-btn-circle:hover .back-arrow {
        color: white;
    }

    .price-detail {
        font-size: 2.5rem;
        font-weight: 800;
        color: #ff6b6b;
    }

    .qty-selector-lg {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 50px;
        display: flex;
        align-items: center;
        border: 1px solid #eee;
    }

    .qty-btn {
        width: 40px;
        height: 35px;
        border-radius: 50%;
        border: none;
        background: white;
        line-height: 1.5rem;
        font-size: 1.6rem;
        color: #ff6b6b;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        transition: 0.3s;
    }

    .qty-btn:hover {
        transform: scale(1.1);
        background: #ff6b6b;
        color: white;
    }

    .qty-input {
        width: 60px;
        border: none;
        background: transparent;
        text-align: center;
        font-weight: 700;
        font-size: 1.4rem;
    }

    .btn-add-cart-lg {
        background-color: #ff6b6b;
        color: white;
        border: none;
        border-radius: 100px;
        padding: 15px 40px;
        font-weight: 700;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        transition: 0.3s;
        box-shadow: 0 10px 25px rgba(255, 107, 107, 0.3);
    }

    .btn-add-cart-lg:hover {
        background-color: #333;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }

    .cart-icon-white {
        width: 22px;
        filter: brightness(0) invert(1);
    }

    .custom-dialog {
        border: none;
        border-radius: 20px;
        padding: 30px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        /*background: #fff;*/
    }

    .custom-dialog::backdrop {
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(1px);
    }

    @media (max-width: 991px) {
        .main-detail-img {
            height: 400px;
        }
    }
</style>

<div class="container py-5 py-lg-5 mt-1">
    <div class="main-show-card shadow-sm border-0 d-flex flex-column flex-lg-row overflow-hidden">

        <div class="col-lg-6 image-side-wrapper">
            <div class="food-img-bg-container">
                <a href="{{ route('menu.page') }}" class="back-btn-circle" title="Back to Menu">
                    <span class="back-arrow">⬅</span>
                </a>
                <img src="{{ asset('images/' . $food->foodcode . '.jpg') }}" alt="{{ $food->foodname }}" class="main-detail-img">
            </div>
        </div>

        <div class="col-lg-6 p-5 d-flex flex-column justify-content-center">
            <nav aria-label="breadcrumb" class="mb-3" style="font-weight: bold; font-size: 1.2rem;">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('menu.page') }}" class="text-decoration-none text-muted">Menu</a></li>
                    <li class="breadcrumb-item active text-danger fw-bold text-capitalize" aria-current="page">{{ $food->category }}</li>
                </ol>
            </nav>

            <h1 class="display-4 fw-bold mb-3">{{ $food->foodname }}</h1>

            <div class="d-flex align-items-center gap-4 mb-2">
                <span class="price-detail" id="display-price">₱{{ $food->price }}</span>
                @if($food->quantity > 0)
                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Available</span>
                @else
                <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">Unavailable</span>
                @endif
                <!-- AVAILABILITY STATUS
                @if($food->isAvailable == 'AV')
                <span class="badge bg-success">Available</span>
                @else
                <span class="badge bg-danger">Unavailable</span>
                @endif
                -->
            </div>

            <p class="text-muted fs-5 mb-4" style="line-height: 1.8;">
                {{ $food->description }}
            </p>

            <div class="order-controls-wrapper d-flex flex-wrap align-items-center gap-4">
                <div class="qty-selector-lg">
                    <button type="button" class="qty-btn" onclick="updateOrder(-1, {{ $food->price }})">−</button>
                    <input id="main-qty-input" class="qty-input ms-2" type="number" value="1" min="1" readonly>
                    <button type="button" class="qty-btn" onclick="updateOrder(1, {{ $food->price }})">+</button>
                </div>

                <button id="main-cart-btn" class="btn btn-add-cart-lg flex-grow-1">
                    <img src="https://cdn-icons-png.flaticon.com/512/263/263142.png" alt="Cart" class="cart-icon-white">
                    <span id="btn-text">Add to Cart (1)</span>
                </button>
            </div>

            <div class="mt-5 pt-4 border-top">
                <div class="d-flex gap-5" style="font-size: 1.1rem;">
                    <div class="text-center ms-4">
                        <p class="mb-0 text-muted">Rating</p>
                        <p class="fw-bold mt-1 mb-0 text-warning">★ 4.9</p>
                    </div>
                    <div class="text-center">
                        <p class="mb-0 text-muted">Delivery</p>
                        <p class="fw-bold mt-1 mb-0">15-20 min</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<dialog id="statusAlertModal" class="custom-dialog">
    <div class="dialog-content text-center">
        <h3 id="alertTitle" class="fw-bold mb-3">Notice</h3>
        <p id="alertMessage" class="text-muted mb-3"></p>
        <div class="mt-3">
            <button onclick="this.closest('dialog').close()" class="btn rounded-pill fw-bold px-5" style="color:white; background: #ff6b6b;">OK</button>
        </div>
    </div>
</dialog>

<script>
    const statusModal = document.getElementById('statusAlertModal');
    const alertTitle = document.getElementById('alertTitle');
    const alertMessage = document.getElementById('alertMessage');

    function showAlert(title, message, isError = false) {
        alertTitle.innerText = title;
        alertMessage.innerText = message;
        
        alertTitle.style.color = isError ? '#dc3545' : '#333';
        
        statusModal.showModal();
    }

    function updateOrder(change, basePrice) {
        const qtyInput = document.getElementById('main-qty-input');
        const priceDisplay = document.getElementById('display-price');
        const btnText = document.getElementById('btn-text');
        const cartBtn = document.getElementById('main-cart-btn');

        const maxStock = parseInt("{{ $food->quantity }}");

        let currentQty = parseInt(qtyInput.value);
        let newQty = currentQty + change;

        if (newQty >= 1 && newQty <= maxStock) {
            qtyInput.value = newQty;

            let totalAmount = basePrice * newQty;
            priceDisplay.innerText = `₱${totalAmount.toLocaleString()}`;
            btnText.innerText = `Add to Cart (${newQty})`;

            cartBtn.style.transform = 'scale(1.03)';
            setTimeout(() => {
                cartBtn.style.transform = 'scale(1)';
            }, 100);
        } else if (newQty > maxStock) {
            showAlert("Stock Limit", `Sorry, only ${maxStock} items available in stock.`, true);
        }
    }

    document.getElementById('main-cart-btn').addEventListener('click', function() {
        const userEmail = localStorage.getItem('user_email');

        if (!userEmail) {
            window.location.href = "{{ route('signin.page') }}";
            return;
        }

        const isAvailable = "{{ $food->isAvailable }}";
        const currentStock = parseInt("{{ $food->quantity }}");

        if (isAvailable === 'UA' || currentStock <= 0) {
            showAlert("Unavailable", "This item is currently unavailable.", true);
            return;
        }

        const qtyInput = document.getElementById('main-qty-input');
        const orderQty = parseInt(qtyInput.value);

        const foodItem = {
            id: "{{ $food->foodcode }}",
            name: "{{ $food->foodname }}",
            price: parseFloat("{{ $food->price }}"),
            image: "{{ asset('images/' . $food->foodcode . '.jpg') }}",
            qty: orderQty
        };

        let cart = JSON.parse(localStorage.getItem('eatsway_cart')) || [];

        const existingItemIndex = cart.findIndex(item => item.id === foodItem.id);
        let totalQtyInCart = orderQty;

        if (existingItemIndex > -1) {
            totalQtyInCart += cart[existingItemIndex].qty;
        }

        if (totalQtyInCart > currentStock) {
            showAlert("Order Limit", `Cannot add more. You already have some in cart and total exceeds available stock (${currentStock}).`, true);
            return;
        }
        
        if (existingItemIndex > -1) {
            cart[existingItemIndex].qty += foodItem.qty;
        } else {
            cart.push(foodItem);
        }

        localStorage.setItem('eatsway_cart', JSON.stringify(cart));

        updateHeaderCartCount();
    });

    statusModal.addEventListener('click', (e) => {
        if (e.target === statusModal) statusModal.close();
    });

    function updateHeaderCartCount() {
        const cart = JSON.parse(localStorage.getItem('eatsway_cart')) || [];
        const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
        const headerCount = document.getElementById('header-cart-count');
        if (headerCount) {
            headerCount.innerText = `(${totalItems})`;
            headerCount.classList.remove('d-none');
        }
    }
</script>

@endSection