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

    .btn-delete-order {
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

    .btn-delete-order:hover {
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
        border-radius: 15px;
        padding: 25px;
        width: 90%;
        max-width: 450px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .custom-dialog::backdrop {
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(1px);
    }

    .dialog-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-confirm {
        background: #dc3545;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
    }

    .btn-confirm:hover {
        background: #e35664ff;
        border: 1px solid #333;
        color: white;
    }

    .btn-cancel {
        background: #ecececff;
        border: 1px solid #ddd;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
    }

    .btn-cancel:hover {
        background: #ecececff;
        border: 1px solid #333;
    }
</style>

<!-- WARNING BUTTON -->
<dialog id="deleteOrderModal" class="custom-dialog">
    <div class="dialog-content">
        <h3>Confirm Permanent Deletion</h3>
        <p>Are you sure you want to cancel order #<span id="modal-order-id"></span>?</p>
        <div class="dialog-actions">
            <button type="button" id="btnCancel" class="btn-cancel">Cancel</button>
            <button type="button" id="btnConfirmDelete" class="btn-confirm">Delete</button>
        </div>
    </div>
</dialog>

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
            @php
            $rider = $drivers->count() > 0 ? $drivers[$order->orderid % $drivers->count()] : null;
            @endphp
            <div class="order-history-card">
                <div class="order-main-info">
                    <span class="order-number-label">Order #{{ $order->orderid }}</span>

                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Rider</span>
                            <span class="info-value">{{ $rider->drivername }}</span>
                            <span style="font-size: 0.75rem; color: #888;">{{ $rider->contactno }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Plate Number</span>
                            <span class="info-value">{{ $rider->plateno }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Expected Time</span>
                            <span class="info-value" style="color: #28a745;">20-30 mins</span>
                        </div>
                    </div>
                </div>

                <div class="status-section border-start ps-4">
                    <span class="info-label">Status</span>
                    <span class="status-text">{{ $order->status }}</span>

                    @if($order->order_status_id == 1)
                    <button onclick="confirmCancel('{{ $order->orderid }}')" class="btn-delete-order mt-2">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('deleteOrderModal');
        const btnCancel = document.getElementById('btnCancel');
        const btnConfirmDelete = document.getElementById('btnConfirmDelete');
        const modalTextDisplay = document.getElementById('modal-order-id');
        let orderToCancel = null;
        
        window.confirmCancel = function(orderId) {
            orderToCancel = orderId;
            if (modalTextDisplay) {
                modalTextDisplay.innerText = orderId;
            }
            modal.showModal();
        };

        btnCancel.addEventListener('click', () => modal.close());

        btnConfirmDelete.addEventListener('click', async function() {
            if (!orderToCancel) return;

            btnConfirmDelete.disabled = true;
            btnConfirmDelete.innerText = "Deleting...";

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
                    btnConfirmDelete.disabled = false;
                    btnConfirmDelete.innerText = "Delete";
                }
            } catch (error) {
                console.error("Cancellation failed:", error);
                alert("An error occurred. Please try again.");
                btnConfirmDelete.disabled = false;
                btnConfirmDelete.innerText = "Delete";
            }
        });
    });
</script>

@endSection