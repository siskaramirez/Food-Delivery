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

    .edit-btn {
        background: none;
        border: none;
        color: #ff6b6b;
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
        color: #ee5253;
        text-decoration: underline;
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
                        <th width="10%">Order<br>ID</th>
                        <th width="10%">Food<br>Code</th>
                        <th width="10%">Total<br>Amount</th>
                        <th width="15%">Payment<br>Method</th>
                        <th width="13%">Payment<br>Status</th>
                        <th width="12%">Service<br>Type</th>
                        <th width="13%">Order<br>Status</th>
                        <th width="13%">Delivery<br>Status</th>
                        <th width="15%">Driver<br>Assign</th>
                        <th width="12%">Date<br>Ordered</th>
                        <th width="5%"></th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @php $lastOrderId = null; @endphp
                    @foreach($transactions as $row)
                    <tr class="{{ $lastOrderId !== $row->orderid ? 'top-border' : 'no-border' }}">
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
                            <span class="status-badge order-badge {{ strtolower($row->order_status) }}">
                                {{ $row->order_status }}
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
                            <span class="text-muted small">(Pick-up)</span>
                            @endif
                            @endif
                        </td>

                        <td class="small text-muted">
                            @if($lastOrderId !== $row->orderid) {{ $order->license ?? 'NA' }} @endif
                        </td>

                        <td class="text-muted small">
                            @if($lastOrderId !== $row->orderid)
                            {{ date('M d, Y', strtotime($row->orderdate)) }}
                            @endif
                        </td>

                        <td>
                            @if($lastOrderId !== $row->orderid)
                            <button type="button" class="edit-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#editOrder-{{ $row->orderid }}">EDIT
                            </button>

                            @include('admin.edit-order', ['order' => $row])
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

@endSection