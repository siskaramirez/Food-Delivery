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
        const cart = JSON.parse(localStorage.getItem('eatsway_cart')) || [];
        const savedPhone = localStorage.getItem('user_phone') || "{{ $user['phone'] }}";
        const savedPayment = localStorage.getItem('user_payment') || "No payment provided";
        const savedAddress = localStorage.getItem('temp_address') || "{{ $user['address'] }}";

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
        
        let currentOrderNum = localStorage.getItem('active_order_num');
        if (!currentOrderNum) {
            currentOrderNum = "#" + Math.floor(1000 + Math.random() * 9000);
            localStorage.setItem('active_order_num', currentOrderNum);
        }
        document.getElementById('order-id-display').innerText = currentOrderNum;

        const itemsContainer = document.getElementById('checkout-items-list');
        const totalDisplay = document.getElementById('grand-total');
        let total = 0;

        if (cart.length > 0) {
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
        }

        function saveToHistory() {
            let history = JSON.parse(localStorage.getItem('eatsway_history')) || [];
            const orderNum = localStorage.getItem('active_order_num');

            if (!orderNum) return;

            const isAlreadySaved = history.find(order => order.orderNum === orderNum);

            if (!isAlreadySaved) {
                const activeOrders = history.filter(order => order.status !== 'Completed');

                if (activeOrders.length >= 2) {
                    console.log("Order limit reached. Not saving to history.");

                    return;
                }

                const newEntry = {
                    orderNum: orderNum,
                    address: savedAddress,
                    driverName: "{{ $driver['name'] ?? 'No Driver' }}",
                    driverPhone: "{{ $driver['phone'] }}",
                    driverPlate: "{{ $driver['plate'] }}",
                    status: "{{ $driver['status'] ?? 'Preparing' }}",
                    distance: "{{ $driver['distance'] }}",
                    eta: "{{ $driver['eta'] }}",
                    date: new Date().toLocaleString()
                };
                history.push(newEntry);
                localStorage.setItem('eatsway_history', JSON.stringify(history));
            }
        }
        saveToHistory();

        function clearCart() {
            localStorage.removeItem('temp_address');
            localStorage.removeItem('eatsway_cart');
        }
    });
</script>

@endSection