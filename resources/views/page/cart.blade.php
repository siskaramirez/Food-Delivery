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

    /* Checkout Button Hover - Replaces Inline Styling */
    .btn-checkout {
        background-color: #ff6b6b;
        border: none;
        transition: 0.3s ease;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .btn-checkout:hover {
        background-color: #2d3436;
        /* Charcoal hover */
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    @media (max-width: 991px) {
        .main-cart-wrapper {
            padding: 30px 20px !important;
        }
    }
</style>

<div class="container py-5 mt-2">
    <div class="main-cart-wrapper shadow-sm border-0 bg-white p-5">

        <div class="d-flex align-items-center mb-5">
            <h2 class="fw-bold mb-0">My Cart</h2>
            <span class="ms-3 text-muted" id="cart-items-count">(0 items)</span>
        </div>

        <div class="row g-5">
            <div class="col-lg-7">
                <div id="cart-items-container" class="cart-items-list d-flex flex-column gap-3">
                    <div class="text-center py-5">
                        <p class="text-muted">Your cart is currently empty.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="order-summary-card p-4 rounded-4" style="background-color: #fff5f0;">
                    <h5 class="fw-bold mb-4">Order Method</h5>

                    <div class="mb-4">
                        <label class="info-label mb-2">Delivery Address</label>
                        <div class="bg-white p-3 rounded-3 shadow-sm d-flex align-items-center">
                            <p class="mb-0 fw-bold small flex-grow-1">{{ $user['address'] }}</p>
                            <a href="{{ route('menu.page') }}" class="text-danger small text-decoration-none fw-bold">Edit</a>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="info-label mb-2">Mode of Payment (MOP)</label>
                        <select class="form-select border-0 shadow-sm rounded-3 fw-bold p-3" style="font-size: 0.9rem;">
                            <option selected>Cash on Delivery (COD)</option>
                            <option value="1">GCash</option>
                            <option value="2">Maya</option>
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

                    <button class="btn btn-checkout btn-danger w-100 rounded-pill py-3 fw-bold shadow-sm" style="font-size: 1.2rem;">Checkout Order</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('cart-items-container');
        const subtotalDisplay = document.getElementById('subtotal-display');
        const totalDisplay = document.getElementById('total-display');
        const countDisplay = document.getElementById('cart-items-count');

        // 1. Kunin ang buong cart array
        const cart = JSON.parse(localStorage.getItem('eatsway_cart')) || [];

        if (cart.length > 0) {
            container.innerHTML = ''; // Linisin ang "Empty" message
            let subtotal = 0;
            let totalQty = 0;

            cart.forEach((item, index) => {
                const itemTotal = item.price * item.qty;
                subtotal += itemTotal;
                totalQty += item.qty;

                // 2. I-inject ang bawat item sa listahan
                container.innerHTML += `
                <div class="cart-item-card d-flex align-items-center p-3 shadow-sm border mb-3">
                    <div class="item-img-container me-3">
                        <img src="${item.image}" class="rounded-3" style="width: 80px; height: 80px; object-fit: cover;">
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="fw-bold mb-1">${item.name}</h5>
                        <p class="text-muted small mb-0">Quantity: ${item.qty} @ ₱${item.price}</p>
                    </div>
                    <div class="text-end">
                        <p class="fw-bold mb-0 text-danger">₱${itemTotal.toLocaleString()}</p>
                        <button class="btn btn-sm text-muted p-0 border-0" onclick="removeItem(${index})">Remove</button>
                    </div>
                </div>
            `;
            });

            // 3. I-update ang summary displays
            countDisplay.innerText = `(${cart.length} items)`;
            subtotalDisplay.innerText = `₱${subtotal.toLocaleString()}`;
            totalDisplay.innerText = `₱${subtotal.toLocaleString()}`;
        }
    });

    function removeItem(index) {
        let cart = JSON.parse(localStorage.getItem('eatsway_cart')) || [];
        cart.splice(index, 1); // Tanggalin ang specific item
        localStorage.setItem('eatsway_cart', JSON.stringify(cart));
        location.reload();
    }
</script>

@endSection