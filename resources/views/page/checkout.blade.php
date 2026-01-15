@extends('layout.main')
@section('content')

<style>
    .receipt-card {
        max-width: 800px;
        margin: 50px auto;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        border: 1px solid #f0f0f0;
    }

    .success-banner {
        background: #ff6b6b;
        color: white;
        text-align: center;
        padding: 30px;
    }

    .receipt-content {
        display: flex;
        padding: 30px;
        gap: 30px;
    }

    .column-left {
        flex: 1.2;
        border-right: 1px solid #eeeeee;
        padding-right: 30px;
    }

    .column-right {
        flex: 1;
    }

    .summary-title {
        font-size: 0.8rem;
        font-weight: 800;
        color: #ff6b6b;
        text-transform: uppercase;
        margin-bottom: 15px;
        letter-spacing: 0.5px;
    }

    .btn-eats-outline {
        border: 2px solid #ff6b6b;
        color: #ff6b6b;
        text-decoration: none;
        text-align: center;
        padding: 10px;
        border-radius: 50px;
        font-weight: 700;
        transition: 0.3s;
        display: block;
        margin-bottom: 10px;
    }

    .btn-eats-solid {
        background: #ff6b6b;
        color: white;
        text-decoration: none;
        text-align: center;
        padding: 12px;
        border-radius: 50px;
        font-weight: 700;
        transition: 0.3s;
        display: block;
    }

    .btn-eats-outline:hover,
    .btn-eats-solid:hover {
        transform: scale(1.03);
        box-shadow: 0 5px 15px rgba(255, 107, 107, 0.2);
    }
</style>

<div style="mt-2">
    <div class="receipt-card">
        <div class="success-banner">
            <div style="font-size: 2.5rem; margin-bottom: 5px;">✔</div>
            <h2 style="margin:0; font-weight: 800; font-size: 1.6rem;">Your order has been placed!</h2>
            <p style="margin:5px 0 0; font-size: 1rem; opacity: 0.9;">Thank you for your order.</p>
        </div>

        <div class="receipt-content">
            <div class="column-left">
                <div class="summary-title">Order Items</div>
                <div id="no-items-message" class="text-center py-5 d-none">
                    <h4 class="text-muted mb-3">No Order Item(s) Found</h4>
                    <a href="{{ route('menu.page') }}" class="btn btn-outline-dark rounded-pill px-3" style="font-size: 0.9rem;">Go to Menu</a>
                </div>
                <div id="checkout-items-list"></div>
                <div style="margin-top: 25px; text-align: center;">
                    <span style="color: #999; font-size: 0.8rem; text-transform: uppercase; font-weight: 600;">Order Number</span>
                    <h4 id="order-id-display" style="margin: 0; color: #333; font-weight: 800;">#0000</h4>
                </div>
            </div>

            <div class="column-right">
                <div class="summary-title">Order Summary</div>
                <div style="background: #f5f5f5ff; padding: 15px; border-radius: 12px; font-size: 0.85rem; color: #444; line-height: 1.8; border: 1px solid #dcdadaff; margin-bottom: 15px;">
                    <div><strong>Phone:</strong> <span id="summary-phone">{{ $user['phone'] }}</span></div>
                    <div><strong>Address:</strong> <span id="summary-address"></span></div>
                    <div><strong>Payment:</strong> <span id="summary-payment"></span></div>
                    <div><strong>Status:</strong> <span style="color: #f39c12; font-weight: 700;">Preparing</span></div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <span style="font-weight: 700; color: #999; font-size: 0.8rem;">TOTAL PAID</span>
                    <span id="grand-total" style="font-size: 1.6rem; font-weight: 900; color: #ff6b6b;">₱0.00</span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <a href="{{ route('orders.page') }}" onclick="clearCart()" class="btn-eats-solid">My Orders</a>
                    <a href="{{ route('menu.page') }}" onclick="clearCart()" class="btn-eats-outline">New Order</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        //const cart = JSON.parse(localStorage.getItem('eatsway_cart')) || [];
        let cart = JSON.parse(localStorage.getItem('eatsway_cart')) || JSON.parse(sessionStorage.getItem('eatsway_checkout_snapshot'));
        const savedPhone = localStorage.getItem('user_phone') || "{{ $user['phone'] }}";
        const savedPayment = sessionStorage.getItem('temp_method') || "No payment provided";
        const savedAddress = sessionStorage.getItem('temp_address') || "{{ $user['address'] }}";

        if (cart && cart.length > 0) {
            sessionStorage.setItem('eatsway_checkout_snapshot', JSON.stringify(cart));
        } else {
            cart = JSON.parse(sessionStorage.getItem('eatsway_checkout_snapshot')) || [];
        }

        document.getElementById('summary-phone').innerText = savedPhone;
        document.getElementById('summary-address').innerText = savedAddress;
        document.getElementById('summary-payment').innerText = savedPayment;

        const mainContent = document.getElementById('main-checkout-content');
        const noItemsMessage = document.getElementById('no-items-message');

        if (cart.length === 0) {
            if (mainContent) mainContent.classList.add('d-none');
            if (noItemsMessage) noItemsMessage.classList.remove('d-none');
            return;
        }

        const itemsContainer = document.getElementById('checkout-items-list');
        const totalDisplay = document.getElementById('grand-total');
        let total = 0;

        itemsContainer.innerHTML = cart.map(item => {
            const price = parseFloat(item.price) || 0;
            const qty = parseInt(item.quantity || item.qty) || 0;
            const itemTotal = price * qty;
            total += itemTotal;

            return `
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <img src="${item.image}" style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover; margin-right: 15px; border: 1px solid #eee;">
                        <div style="flex-grow: 1;">
                            <div style="font-weight: 700; font-size: 0.9rem; color: #333;">${item.name}</div>
                            <div style="font-size: 0.8rem; color: #888;">₱${price} x ${qty}</div>
                        </div>
                        <div style="font-weight: 700; color: #333;">₱${itemTotal}</div>
                    </div>
                `;
        }).join('');

        totalDisplay.innerText = "₱" + total.toLocaleString();

        const headerCount = document.getElementById('header-cart-count');
        if (headerCount) headerCount.innerText = "(0)";

        const isPaymentDone = sessionStorage.getItem('payment_done');
        const orderAlreadyPlaced = sessionStorage.getItem('order_submitted');

        if (cart.length > 0 && isPaymentDone === 'true' && !orderAlreadyPlaced) {
            autoPlaceOrder(cart);
        } else if (orderAlreadyPlaced) {
            const displayElement = document.getElementById('order-id-display');
            if (displayElement) displayElement.innerText = "#" + orderAlreadyPlaced;
        }

        /*
        function clearCart() {
            localStorage.removeItem('eatsway_cart');
            sessionStorage.clear();
            
            localStorage.removeItem('active_order_num');
        } */
    });

    async function autoPlaceOrder(cart) {
        const orderData = {
            cart: cart,
            address: sessionStorage.getItem('temp_address'),
            mop: sessionStorage.getItem('temp_method'),
            ref: sessionStorage.getItem('temp_ref') || 'COD-PAYMENT',
            service: sessionStorage.getItem('temp_service'),
            _token: "{{ csrf_token() }}"
        };

        try {
            const response = await fetch("{{ route('order.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(orderData)
            });

            const result = await response.json();

            if (result.success) {
                const displayElement = document.getElementById('order-id-display');
                if (displayElement) {
                    displayElement.innerText = "#" + result.order_id;
                }
                sessionStorage.setItem('order_submitted', result.order_id);
                localStorage.removeItem('eatsway_cart');
            } else {
                console.error("Auto-save failed: " + result.message);
                window.location.href = "{{ route('cart.page') }}";
            }
        } catch (error) {
            console.error("Order failed:", error);
        }
    }
    window.addEventListener('beforeunload', function (e) {
    });

    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function() {
            if (!this.href.includes(window.location.pathname)) {
                sessionStorage.removeItem('eatsway_checkout_snapshot');
                sessionStorage.removeItem('order_submitted');
                sessionStorage.removeItem('payment_done');
            }
        });
    });
</script>

@endSection