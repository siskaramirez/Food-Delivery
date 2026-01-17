@extends('layout.main')
@section('content')

<style>
    body {
        background-color: #fcf6f1;
    }

    .orders-wrapper {
        max-width: 1000px;
        margin: 50px auto;
        padding: 0 20px;
    }

    .orders-main-container {
        background: #ffffff;
        border-radius: 30px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        min-height: 500px;
    }

    .page-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
    }

    .title-text {
        color: #333;
        font-weight: 800;
        font-size: 2.2rem;
    }

    .subtitle-text {
        color: #888;
        font-size: 1rem;
    }

    .order-history-card {
        background: #fafafaff;
        border: 1px solid #e7e6e6ff;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: transform 0.2s;
    }

    .order-history-card:hover {
        transform: scale(1.02);
    }

    .order-main-info {
        flex: 1;
    }

    .order-number-label {
        color: #ff6b6b;
        font-weight: 800;
        font-size: 1.3rem;
        margin-bottom: 10px;
        display: block;
    }

    .info-grid {
        display: flex;
        gap: 40px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 0.75rem;
        color: #ff6b6b;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 0.95rem;
        color: #444;
        font-weight: 600;
    }

    .status-section {
        text-align: right;
        min-width: 150px;
    }

    .status-text {
        color: #f39c12;
        font-weight: 800;
        font-size: 1rem;
        margin-bottom: 10px;
        display: block;
    }

    .btn-cancel-order {
        background-color: transparent;
        color: #ff6b6b;
        border: 1px solid #ff6b6b;
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.85rem;
        transition: all 0.3s;
        width: 100%;
    }

    .btn-cancel-order:hover {
        background-color: #ff6b6b;
        color: white;
    }

    .empty-history {
        text-align: center;
        padding: 80px 0;
    }

    .empty-history p {
        color: #999;
        font-size: 1.2rem;
        margin-bottom: 25px;
    }

    .btn-order-now {
        background-color: transparent;
        color: #ff6b6b;
        border: 1px solid #ff6b6b;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 700;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-order-now:hover {
        background-color: #ff6b6b;
        color: white;
        transform: scale(1.05);
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

    .btn-confirm,
    .btn-cancel {
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
</style>

<!-- MAIN -->
<div class="orders-wrapper">
    <div class="orders-main-container">

        <div class="page-header-flex">
            <div>
                <h1 class="title-text">My Orders</h1>
            </div>
            <div class="subtitle-text">Recent Transactions</div>
        </div>

        <div id="orders-list-content">
            @if($orders->isEmpty())
            <div class="empty-history py-5">
                <p>No orders found in your history.</p>
                <a href="{{ route('menu.page') }}" class="btn-order-now">Order Now</a>
            </div>
            @else
            @foreach($orders as $order)
            <div class="order-history-card">
                <div class="order-main-info">
                    <span class="order-number-label">Order #{{ $order->orderid }}</span>

                    <div class="info-grid">
                        @if($order->deliveryneeded == 0)
                        <div class="info-item">
                            <span class="info-label">Service Type</span>
                            <span class="info-value">Pick-up</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Schedule</span>
                            <span id="receipt-pickup-date" class="info-value">Loading...</span>
                        </div>
                        @else
                        <div class="info-item">
                            <span class="info-label">Rider</span>
                            @if($order->drivername)
                            <span class="info-value">{{ $order->drivername }}</span>
                            <span style="font-size: 0.75rem; color: #888;">{{ $order->contactno }}</span>
                            @else
                            <span class="info-value">Not assigned yet</span>
                            @endif
                        </div>

                        <div class="info-item">
                            <span class="info-label">Plate Number</span>
                            @if($order->plateno)
                            <span class="info-value">{{ $order->plateno }}</span>
                            @else
                            <span class="info-value">TBA</span>
                            @endif
                        </div>
                        @endif
                        <div class="info-item">
                            <span class="info-label">Payment Method</span>
                            <span class="info-value">{{ $order->paymentmethod }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Total Amount</span>
                            <span class="info-value">₱{{ $order->totalprice }}</span>
                        </div>
                    </div>
                </div>

                <div class="status-section border-start ps-4">
                    <span class="info-label">Status</span>
                    @if($order->deliveryneeded == 1)
                    <span class="status-text">{{ $order->deliverystatus }}</span>
                    @else
                    <span class="status-text">{{ $order->order_status }}</span>
                    @endif

                    @if($order->order_status_id == 1)
                    <button onclick="confirmCancel('{{ $order->orderid }}')" class="btn-cancel-order mt-2">
                        Cancel Order
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>

<!-- WARNING BUTTON -->
<dialog id="cancelOrderModal" class="custom-dialog">
    <div class="dialog-content">
        <h3 class="fw-bold">Confirm Cancellation</h3>
        <p class="text-muted">Are you sure you want to cancel order #<span id="modal-order-id"></span>?</p>
        <div class="dialog-actions">
            <button type="button" id="btnCancel" class="btn-cancel">Keep Order</button>
            <button type="button" id="btnConfirmCancel" class="btn-confirm">Cancel Order</button>
        </div>
    </div>
</dialog>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pickupDisplay = document.getElementById('receipt-pickup-date');
        if (!pickupDisplay) return;

        let savedAddress = sessionStorage.getItem('temp_address');

        if (!savedAddress) {
            const lastId = sessionStorage.getItem('order_submitted');
            savedAddress = localStorage.getItem('pickup_string_' + lastId);
        }

        if (savedAddress) {
            const cleanDate = savedAddress.replace('Pick-up on ', '');
            pickupDisplay.innerText = cleanDate;
        } else {
            pickupDisplay.innerText = "As scheduled";
        }

        const modal = document.getElementById('cancelOrderModal');
        const btnCancel = document.getElementById('btnCancel');
        const btnConfirmCancel = document.getElementById('btnConfirmCancel');
        const modalTextDisplay = document.getElementById('modal-order-id');
        let orderToCancel = null;

        window.confirmCancel = function(orderId) {
            orderToCancel = orderId;
            if (modalTextDisplay) {
                modalTextDisplay.innerText = orderId;
            }
            modal.showModal();
        };

        if (btnCancel) {
            btnCancel.addEventListener('click', () => modal.close());
        }

        modal.addEventListener('click', (e) => {
            const dialogDimensions = modal.getBoundingClientRect();
            if (
                e.clientX < dialogDimensions.left ||
                e.clientX > dialogDimensions.right ||
                e.clientY < dialogDimensions.top ||
                e.clientY > dialogDimensions.bottom
            ) {
                modal.close();
            }
        });

        btnConfirmCancel.addEventListener('click', async function() {
            if (!orderToCancel) return;

            btnConfirmCancel.disabled = true;
            btnConfirmCancel.innerText = "Cancelling...";

            try {
                const response = await fetch(`/order/cancel/${orderToCancel}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    modal.close();
                    window.location.reload();
                } else {
                    alert("Error: " + result.message);
                    btnConfirmCancel.disabled = false;
                    btnConfirmCancel.innerText = "Cancel Order";
                }
            } catch (error) {
                console.error("Cancellation failed:", error);
                alert("An error occurred. Please try again.");
                btnConfirmCancel.disabled = false;
                btnConfirmCancel.innerText = "Cancel Order";
            }
        });
    });
</script>

@endSection