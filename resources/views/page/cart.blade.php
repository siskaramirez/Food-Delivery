@extends('layout.main')
@section('content')

<style>
    .main-cart-wrapper {
        min-height: 600px;
        border-radius: 35px;
    }

    .info-label {
        color: #ff6b6b;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .cart-item-card {
        background: white;
        transition: 0.3s;
        border-radius: 20px;
    }

    .cart-item-card:hover {
        transform: scale(1.02);
        border-color: #ff6b6b !important;
    }

    .qty-selector-cart {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border: 1px solid #eee;
        border-radius: 50px;
        padding: 5px 12px;
        width: fit-content;
    }

    .qty-btn-cart {
        border: none;
        background: none;
        font-weight: bold;
        font-size: 1.2rem;
        color: #333;
        cursor: pointer;
        padding: 0 5px;
    }

    .qty-input-cart {
        width: 38px;
        border: none;
        background: transparent;
        text-align: center;
        font-weight: bold;
        font-size: 1rem;
    }

    .btn-checkout {
        background-color: #ff6b6b;
        color: white;
        border: none;
        transition: 0.3s ease;
    }

    .btn-checkout:hover {
        background-color: #2d3436;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    @media (max-width: 991px) {
        .main-cart-wrapper {
            padding: 30px 20px !important;
        }
    }
</style>

<div class="container py-5 mt-1">
    <div class="main-cart-wrapper shadow-sm border-0 bg-white p-5">

        <div class="d-flex align-items-center mb-4">
            <h2 class="fw-bold mb-0">My Cart</h2>
            <span class="ms-3 text-muted" id="cart-items-count">(0 items)</span>
        </div>

        <div class="row g-5">
            <div class="col-lg-7">
                <div id="cart-items-container" class="cart-items-list d-flex flex-column gap-3">
                </div>
            </div>

            <div class="col-lg-5">
                <div class="order-summary-card p-4 rounded-4" style="background-color: #fff5f0;">
                    <h5 class="fw-bold mb-3">Order Method</h5>

                    <div class="mb-4">
                        <label class="info-label mb-2">Delivery Address</label>
                        <div class="bg-white p-3 rounded-3 shadow-sm d-flex align-items-center">
                            <p class="mb-0 fw-bold small flex-grow-1" id="cart-delivery-address">
                                {{ $user['address'] }}
                            </p>
                            <a href="{{ route('profile.page') }}" class="text-danger small text-decoration-none fw-bold">Edit</a>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="info-label mb-2">Mode of Payment (MOP)</label>
                        <select class="form-select border-0 shadow-sm rounded-3 fw-bold p-3" style="font-size: 0.9rem;">
                            <option selected>Cash on Delivery (COD)</option>
                            <option value="1">Credit/Debit Card</option>
                            <option value="2">Digital Wallet</option>
                        </select>
                    </div>

                    <hr class="my-4" style="border-top: 2px dashed #ddd;">

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold" id="subtotal-display">₱0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="text-muted">Delivery Fee</span>
                        <span class="fw-bold text-success">FREE</span>
                    </div>

                    <div class="d-flex justify-content-between mb-5 border-top pt-3">
                        <h4 class="fw-bold">Total</h4>
                        <h4 class="fw-bold text-danger" id="total-display">₱0</h4>
                    </div>

                    <button class="btn btn-checkout w-100 rounded-pill py-3 fw-bold shadow-sm">
                        Checkout Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        renderCart();
        syncAddress();
    });

    function renderCart() {
        const container = document.getElementById('cart-items-container');
        const countDisplay = document.getElementById('cart-items-count');
        const subtotalDisplay = document.getElementById('subtotal-display');
        const totalDisplay = document.getElementById('total-display');

        const cart = JSON.parse(localStorage.getItem('eatsway_cart')) || [];

        if (cart.length > 0) {
            container.innerHTML = '';
            let subtotal = 0;
            let totalQuantity = 0;

            cart.forEach((item, index) => {
                const itemTotal = item.price * item.qty;
                subtotal += itemTotal;
                totalQuantity += item.qty;

                container.innerHTML += `
                    <div class="cart-item-card d-flex align-items-center p-3 shadow-sm border mb-3">
                        <div class="item-img-container me-3">
                            <img src="${item.image}" alt="${item.name}" class="rounded-3" style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">${item.name}</h5>
                            <div class="qty-selector-cart shadow-sm mt-2">
                                <button class="qty-btn-cart" onclick="changeQty(${index}, -1)">−</button>
                                <input class="qty-input-cart ms-2" type="number" value="${item.qty}" readonly>
                                <button class="qty-btn-cart" onclick="changeQty(${index}, 1)">+</button>
                            </div>
                        </div>
                        <div class="text-end">
                            <p class="fw-bold mb-0 text-danger" style="font-size: 1.1rem;">₱${itemTotal.toLocaleString()}</p>
                            <button class="btn btn-sm text-muted p-0 border-0" onclick="removeItem(${index})">Remove</button>
                        </div>
                    </div>
                `;
            });
            countDisplay.innerText = `(${totalQuantity} items)`;
            subtotalDisplay.innerText = `₱${subtotal.toLocaleString()}`;
            totalDisplay.innerText = `₱${subtotal.toLocaleString()}`;

            if (window.updateHeaderCartCount) updateHeaderCartCount();
        } else {
            container.innerHTML = `
                <div class="text-center py-5">
                    <p class="text-muted">Your cart is empty.</p>
                    <a href="{{ route('menu.page') }}" class="btn btn-outline-danger rounded-pill px-4">Go to Menu</a>
                </div>
            `;
            countDisplay.innerText = `(0 items)`;
            subtotalDisplay.innerText = `₱0`;
            totalDisplay.innerText = `₱0`;
            if (window.updateHeaderCartCount) updateHeaderCartCount();
        }
    }

    function changeQty(index, change) {
        let cart = JSON.parse(localStorage.getItem('eatsway_cart')) || [];

        if (cart[index]) {
            cart[index].qty += change;

            if (cart[index].qty < 1) {
                cart[index].qty = 1;
            }

            localStorage.setItem('eatsway_cart', JSON.stringify(cart));
            renderCart();
        }
    }

    function syncAddress() {
        const savedAddress = localStorage.getItem('user_address');
        const addressDisplay = document.getElementById('cart-delivery-address');
        if (savedAddress && addressDisplay) {
            addressDisplay.innerText = savedAddress;
        }
    }

    function removeItem(index) {
        let cart = JSON.parse(localStorage.getItem('eatsway_cart')) || [];
        cart.splice(index, 1);
        localStorage.setItem('eatsway_cart', JSON.stringify(cart));

        if (typeof updateHeaderCartCount === "function") {
            updateHeaderCartCount();
        }

        renderCart();
    }
</script>

@endSection