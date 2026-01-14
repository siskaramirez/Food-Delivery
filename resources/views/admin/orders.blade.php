@extends('layout.main')
@section('content')

<style>
    /* Glassmorphism Effect */
    .glass-card {
        background: white;
        /*backdrop-filter: blur(10px);*/
        border-radius: 25px;
        padding: 20px;
        border: 1px solid #fff1f1;
    }

    .custom-table thead th {
        background: transparent;
        color: #ff6b6b;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
        padding: 20px;
    }

    .custom-table tbody tr {
        background-color: transparent !important;
        cursor: pointer;
    }

    .custom-table tbody tr:hover td {
        background-color: rgba(255, 107, 107, 0.08) !important;
        transition: background-color 0.2s ease;
    }

    .custom-table tbody tr:hover td:first-child {
        box-shadow: inset 4px 0 0 0 #ff6b6b;
    }

    .food-tag {
        background: #605f5fff;
        color: white;
        padding: 5px 10px;
        border-radius: 7px;
        font-size: 0.8rem;
    }

    .service-tag {
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
    }

    .delivery {
        background: #e3f2fd;
        color: #1976d2;
    }

    .pickup {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .status-badge {
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        /*text-align: center;*/
        text-transform: uppercase;
        display: inline-block;
        /*min-width: 95px;*/
    }

    .pay-badge.pending {
        background-color: #fff3e0;
        color: #ef6c00;
    }

    .pay-badge.paid {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .pay-badge.refunded {
        background-color: #eceff1;
        color: #455a64;
    }

    .pay-badge.cancelled {
        background-color: #ffebee;
        color: #c62828;
    }

    .order-badge.pending {
        background-color: #fff3e0;
        color: #ef6c00;
    }

    .order-badge.completed {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .order-badge.cancelled {
        background-color: #ffebee;
        color: #c62828;
    }

    .delivery-badge.pending {
        background-color: #fff3e0;
        color: #ef6c00;
    }

    .delivery-badge.assigned {
        background-color: #f3e5f5;
        color: #7b1fa2;
    }

    .delivery-badge.picked-up {
        background-color: #e1f5fe;
        color: #0288d1;
    }

    .delivery-badge.en-route {
        background-color: #e0f2f1;
        color: #00796b;
    }

    .delivery-badge.delivered {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .delivery-badge.cancelled {
        background-color: #ffebee;
        color: #c62828;
    }

    .edit-btn {
        background: none;
        border: none;
        color: #333;
        font-size: 0.75rem;
        font-weight: 650;
        text-decoration: underline;
        text-underline-offset: 4px;
        letter-spacing: 1px;
        padding: 0;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .edit-btn:hover {
        color: #333;
        text-decoration: underline;
    }

    .remove-btn {
        background: none;
        border: none;
        color: #c62828;
        font-size: 0.75rem;
        font-weight: 650;
        text-decoration: underline;
        text-underline-offset: 4px;
        letter-spacing: 1px;
        padding: 0;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .remove-btn:hover {
        color: #c62828;
        text-decoration: underline;
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

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold ms-3" style="color: #ff6b6b;">Transaction Logs</h2>
        <div class="stats-mini">Total Orders: <span class="fw-bold">{{ $transactions->unique('orderid')->count() }}</span></div>
    </div>

    <div class="glass-card">
        <div class="table-responsive">
            <table class="table align-middle custom-table">
                <thead class="text-center">
                    <tr>
                        <th width="8%">Order<br>ID</th>
                        <th width="8%">Food<br>Code</th>
                        <th width="8%">Total<br>Amount</th>
                        <th width="15%">Payment<br>Method</th>
                        <th width="12%">Payment<br>Status</th>
                        <th width="12%">Service<br>Type</th>
                        <th width="12%">Order<br>Status</th>
                        <th width="12%">Delivery<br>Status</th>
                        <th width="15%">Driver<br>Assign</th>
                        <th width="10%">Date<br>Ordered</th>
                        <th width="10%"></th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @php $lastOrderId = null; @endphp
                    @foreach($transactions as $row)
                    <tr id="admin-order-row-{{ $row->orderid }}" class="{{ $lastOrderId !== $row->orderid ? 'top-border' : 'no-border' }}">
                        <td class="fw-bold">
                            @if($lastOrderId !== $row->orderid) #{{ $row->orderid }} @endif
                        </td>

                        <td><span class="food-tag">{{ $row->foodcode }}</span></td>

                        <td class="fw-bold text-dark">₱{{ $row->totalprice }}</td>

                        <td class="small text-muted">
                            @if($lastOrderId !== $row->orderid) {{ $row->paymentmethod }} @endif
                        </td>

                        <td>
                            @if($lastOrderId !== $row->orderid)
                            <span class="status-badge pay-badge {{ strtolower($row->paymentstatus) }}">
                                {{ $row->paymentstatus }}
                            </span>
                            @endif
                        </td>

                        <td>
                            @if($lastOrderId !== $row->orderid)
                            @if($row->deliveryneeded == 1)
                            <span class="service-tag delivery"><i class="fas fa-truck"></i> Delivery</span>
                            @else
                            <span class="service-tag pickup"><i class="fas fa-walking"></i> Pick-up</span>
                            @endif
                            @endif
                        </td>

                        <td>
                            @if($lastOrderId !== $row->orderid)
                            <span class="status-badge order-badge {{ strtolower($row->status_name) }}">
                                {{ $row->status_name }}
                            </span>
                            @endif
                        </td>

                        <td>
                            @if($lastOrderId !== $row->orderid)
                            @if($row->deliveryneeded == 1)
                            <span class="status-badge delivery-badge {{ strtolower(str_replace(' ', '-', $row->deliverystatus ?? 'pending')) }}">
                                {{ $row->deliverystatus ?? 'Pending' }}
                            </span>
                            @else
                            <span class="service-tag pickup">Pick-up</span>
                            @endif
                            @endif
                        </td>

                        <td class="small text-muted">
                            @if($lastOrderId !== $row->orderid) {{ $row->license ?? 'NA' }} @endif
                        </td>

                        <td class="text-muted small">
                            @if($lastOrderId !== $row->orderid)
                            {{ date('M d, Y', strtotime($row->orderdate)) }}
                            @endif
                        </td>

                        <td>
                            @if($lastOrderId !== $row->orderid)
                            @php
                            $paymentStatus = $row->paymentstatus;
                            $paymentMethod = $row->paymentmethod;

                            $isCancelled = ($row->order_status_id == 3);
                            $isPickUpFinished = ($row->deliveryneeded == 0 && $row->order_status_id == 2);
                            $isDeliveryFinished = ($row->deliveryneeded == 1 && $row->deliverystatus == 'Delivered');

                            $needsRefund = ($isCancelled && !in_array($paymentMethod, ['Cash', 'Cash on Delivery']) && $paymentStatus == 'Paid');

                            $showRemove = ($isCancelled || $isPickUpFinished || $isDeliveryFinished) && !$needsRefund;
                            @endphp

                            @if(!$showRemove)
                            <button type="button" class="edit-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#editOrder-{{ $row->orderid }}">EDIT
                            </button>
                            @include('admin.edit-order', ['order' => $row])
                            
                            @else
                            <button type="button" class="remove-btn" onclick="openAdminDeleteModal('{{ $row->orderid }}')">REMOVE</button>
                            @endif
                            @endif
                        </td>
                    </tr>
                    @php $lastOrderId = $row->orderid; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<dialog id="adminDeleteModal" class="custom-dialog">
    <div class="dialog-content">
        <h3 class="fw-bold">Confirm Deletion</h3>
        <p class="text-muted">Are you sure you want to remove order #<span id="admin-modal-order-id"></span>?</p>
        <div class="dialog-actions">
            <button type="button" id="adminBtnCancel" class="btn-cancel">Cancel</button>
            <button type="button" id="adminBtnConfirm" class="btn-confirm">Remove</button>
        </div>
    </div>
</dialog>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('adminDeleteModal');
        const btnCancel = document.getElementById('adminBtnCancel');
        const btnConfirm = document.getElementById('adminBtnConfirm');
        const modalText = document.getElementById('admin-modal-order-id');
        let orderToHide = null;

        const hiddenAdminOrders = JSON.parse(localStorage.getItem('admin_hidden_orders') || '[]');
        hiddenAdminOrders.forEach(id => {
            const row = document.getElementById(`admin-order-row-${id}`);
            if (row) row.style.display = 'none';
        });

        window.openAdminDeleteModal = function(orderId) {
            orderToHide = orderId;
            if (modalText) modalText.innerText = orderId;
            modal.showModal();
        };

        btnCancel.addEventListener('click', () => modal.close());

        btnConfirm.addEventListener('click', function() {
            if (!orderToHide) return;

            let adminList = JSON.parse(localStorage.getItem('admin_hidden_orders') || '[]');

            if (!adminList.includes(orderToHide)) {
                adminList.push(orderToHide);
            }

            localStorage.setItem('admin_hidden_orders', JSON.stringify(adminList));

            const rowToHide = document.getElementById(`admin-order-row-${orderToHide}`);
            if (rowToHide) {
                rowToHide.style.transition = '0.3s';
                rowToHide.style.opacity = '0';
                setTimeout(() => {
                    rowToHide.style.display = 'none';
                }, 300);
            }
            modal.close();
        });

        modal.addEventListener('click', (e) => {
            const dimensions = modal.getBoundingClientRect();
            if (e.clientX < dimensions.left ||
                e.clientX > dimensions.right ||
                e.clientY < dimensions.top ||
                e.clientY > dimensions.bottom) {
                modal.close();
            }
        });
    });
</script>

@endSection