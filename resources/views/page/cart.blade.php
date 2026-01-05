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
        background: #f2f6f9ad;
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

    /* WARNING BUTTON */
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

    .dialog-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 20px;
    }

    .btn-confirm, .btn-cancel {
        flex: 1;
        height: 45px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        border: none;
        display: flex;
        transition: all 0.2s ease;
        align-items: center;
        justify-content: center;
        text-transform: uppercase;
        cursor: pointer;
    }

    .btn-confirm {
        background: #ff6b6b;
        color: white;
        /*transition: 0.2s;*/
    }

    .btn-confirm:hover {
        background: #ee5253;
    }

    .btn-cancel {
        background: #f0f1f1ff;
        color: #6c757d;
        /*transition: 0.2s;*/
    }

    .btn-cancel:hover {
        background: #e2e6ea;
    }

    @media (max-width: 991px) {
        .main-cart-wrapper {
            padding: 30px 20px !important;
        }
    }
</style>

<!-- WARNING BUTTON -->
<dialog id="removeItemModal" class="custom-dialog">
    <div class="dialog-content">
        <h3 class="fw-bold">Confirm Deletion</h3>
        <p class="text-muted">Are you sure you want to remove this item<br>from your cart?</p>
        <div class="dialog-actions">
            <button id="btnCancelRemove" class="btn-cancel">Cancel</button>
            <button id="btnConfirmRemove" class="btn-confirm">Remove</button>
        </div>
    </div>
</dialog>

<dialog id="limitModal" class="custom-dialog">
    <div class="dialog-content text-center">
        <h3 class="fw-bold mb-3">Order Limit Reached</h3>
        <p class="text-muted mb-3">You can only have 2 active orders at a time. Please wait for your previous orders to be processed or cancelled.</p>
        <div class="mt-3">
            <button onclick="this.closest('dialog').close()" class="btn rounded-pill fw-bold px-5" style="color:white; background: #ff6b6b;">OK</button>
        </div>
    </div>
</dialog>

<!-- MAIN -->
<div class="container py-5">
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
                        <label class="info-label mb-2">Service Type</label>
                        <select class="form-select border-0 shadow-sm rounded-3 fw-bold p-3" style="font-size: 0.9rem;" id="service-type-select" onchange="toggleServiceFields()">
                            <option value="Delivery" selected>Delivery</option>
                            <option value="Pick-up">Pick-up</option>
                        </select>
                    </div>

                    <div id="delivery-details-wrapper">
                        <div class="mb-4">
                            <label class="info-label mb-2">Delivery Address</label>
                            <select class="form-select border-0 shadow-sm rounded-3 fw-bold p-3" style="font-size: 0.9rem;" id="delivery-address-select" onchange="handleAddressOptionChange()">
                                <option id="synced-address-option" value="{{ $user['address'] }}">{{ $user['address'] }}</option>
                                <option value="custom">Use another address</option>
                            </select>
                        </div>

                        <div class="mb-4 d-none" id="custom-address-wrapper">
                            <label class="info-label mb-2">Enter New Address</label>
                            <input type="text" id="custom-address-input" class="form-control border-0 shadow-sm rounded-3 p-3" placeholder="Unit/Bldg, Street, City">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="info-label mb-2">Mode of Payment (MOP)</label>
                        <select id="payment-method-select" class="form-select border-0 shadow-sm rounded-3 fw-bold p-3" style="font-size: 0.9rem;">
                            <option value="Cash on Delivery" selected>Cash on Delivery (COD)</option>
                            <option value="Credit/Debit Card">Credit/Debit Card</option>
                            <option value="Digital Wallet">Digital Wallet</option>
                        </select>
                    </div>

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

                    <button class="btn btn-checkout w-100 rounded-pill py-3 fw-bold shadow-sm">Checkout Order</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        renderCart();
        syncAddress();

        const checkoutBtn = document.querySelector('.btn-checkout');
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', processCheckout);
        }

        let itemIndexToRemove = null;

        window.removeItem = function(index) {
            itemIndexToRemove = index;
            const modal = document.getElementById('removeItemModal');
            if (modal) {
                modal.showModal();
            }
        };

        document.getElementById('btnCancelRemove').onclick = function() {
            document.getElementById('removeItemModal').close();
            itemIndexToRemove = null;
        };

        document.getElementById('btnConfirmRemove').onclick = function() {
            if (itemIndexToRemove !== null) {
                let cart = JSON.parse(localStorage.getItem('eatsway_cart')) || [];

                cart.splice(itemIndexToRemove, 1);
                localStorage.setItem('eatsway_cart', JSON.stringify(cart));

                if (typeof updateHeaderCartCount === "function") {
                    updateHeaderCartCount();
                }

                document.getElementById('removeItemModal').close();
                renderCart();
                itemIndexToRemove = null;
            }
        };
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

            sessionStorage.setItem('temp_total', subtotal.toFixed(2));

        } else {
            container.innerHTML = `
                <div class="text-center py-5">
                    <p class="text-muted">Your cart is empty.</p>
                    <a href="{{ route('menu.page') }}" class="btn btn-outline-dark rounded-pill px-4">Go to Menu</a>
                </div>
            `;
            countDisplay.innerText = `(0 items)`;
            subtotalDisplay.innerText = `₱0`;
            totalDisplay.innerText = `₱0`;
        }
        if (window.updateHeaderCartCount) updateHeaderCartCount();
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

    function toggleServiceFields() {
        const serviceType = document.getElementById('service-type-select').value;
        const deliveryWrapper = document.getElementById('delivery-details-wrapper');

        if (serviceType === "Pick-up") {
            deliveryWrapper.classList.add('d-none');
        } else {
            deliveryWrapper.classList.remove('d-none');
        }
    }

    function syncAddress() {
        const addressOption = document.getElementById('synced-address-option');
        const savedAddress = localStorage.getItem('user_address');

        if (savedAddress && savedAddress.trim() !== "") {
            addressOption.innerText = savedAddress;
            addressOption.value = savedAddress;
        }
    }

    function handleAddressOptionChange() {
        const select = document.getElementById('delivery-address-select');
        const customWrapper = document.getElementById('custom-address-wrapper');

        if (select.value === "custom") {
            customWrapper.classList.remove('d-none');
            document.getElementById('custom-address-input').focus();
        } else {
            customWrapper.classList.add('d-none');
        }
    }

    function processCheckout() {
        const cart = JSON.parse(localStorage.getItem('eatsway_cart')) || [];

        if (cart.length === 0) {
            alert("Your cart is empty!");
            return;
        }

        const activeCount = {{ $activeOrdersCount ?? 0 }};

        if (activeCount >= 2) {
            document.getElementById('limitModal').showModal(); // Ipakita ang warning
            return; // STOP: Huwag ituloy ang checkout
        }

        const serviceType = document.getElementById('service-type-select').value;
        const paymentMethod = document.getElementById('payment-method-select').value;
        let finalAddress = "";

        if (serviceType === "Pick-up") {
            finalAddress = "Pick-up at Store";
        } else {
            const addressSelect = document.getElementById('delivery-address-select').value;
            if (addressSelect === "custom") {
                finalAddress = document.getElementById('custom-address-input').value;
                if (!finalAddress || finalAddress.trim() === "") {
                    alert("Please enter your delivery address.");
                    return;
                }
            } else {
                finalAddress = addressSelect || "{{ $user['address'] }}";
            }
        }


        sessionStorage.setItem('temp_address', finalAddress);
        sessionStorage.setItem('temp_method', paymentMethod);
        sessionStorage.setItem('temp_service', serviceType);

        if (paymentMethod === "Cash on Delivery") {
            sessionStorage.setItem('temp_ref', 'COD-PAYMENT');
            sessionStorage.setItem('payment_done', 'true');
            window.location.href = "{{ route('cart.checkout') }}";
        } else {
            sessionStorage.setItem('payment_done', 'false');
            window.location.href = "{{ route('payment.page') }}";
        }
        
        /* remove this history
        const history = JSON.parse(localStorage.getItem('eatsway_history')) || [];

        if (history.filter(order => order.status !== 'Completed').length >= 2) {
            alert("You currently have 2 active orders. Please note that refunds are not possible at this time. \n\nPlease come back and order again once your previous orders are completed to keep track of your history.");
            return;
        } */
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