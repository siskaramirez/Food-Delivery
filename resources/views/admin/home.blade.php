@extends('layout.main')
@section('content')

<style>
    /* Main Container Alignment */
    .admin-main-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Accordion Custom Styling */
    .user-accordion-item {
        background: white;
        border-radius: 15px !important;
        border: 1px solid #fff1f1 !important;
        margin-bottom: 15px;
        overflow: hidden;
    }

    .accordion-button {
        background: white !important;
        color: #4a4a4a !important;
        font-weight: 600;
        padding: 20px 25px;
        border: none !important;
    }

    .accordion-button:not(.collapsed) {
        box-shadow: none;
        color: #ff6b6b !important;
        border-bottom: 2px solid #fff1f1 !important;
    }

    .user-tag {
        color: #ff6b6b;
        font-weight: 800;
        margin-right: 10px;
    }

    /* Details Panel Design */
    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 30px;
        padding: 25px;
        background: #fffcfb;
    }

    .info-section,
    .history-section {
        background: white;
        padding: 20px;
        border-radius: 15px;
        border: 1px solid #fff1f1;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #e6e5e5ff;
        font-size: 0.9rem;
    }

    .detail-label {
        color: #999;
        font-weight: 500;
    }

    .detail-value {
        color: #4a4a4a;
        font-weight: 600;
    }

    /* Inner Table History */
    .history-table {
        width: 100%;
        font-size: 0.85rem;
    }

    .history-table th {
        color: #ff6b6b;
        padding-bottom: 10px;
    }

    .history-table td {
        padding: 10px 0;
        border-bottom: 1px solid #e6e5e5ff;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #ccc;
        font-style: italic;
    }

    /* Badges */
    .status-badge {
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
    }

    .status-badge.paid {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-badge.pending {
        background: #fff3e0;
        color: #ef6c00;
    }

    .status-badge.refunded {
        background: #eceff1;
        color: #455a64;
    }

    .status-badge.completed {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-badge.cancelled {
        background: #ffebee;
        color: #c62828;
    }

    .status-badge.order-pending {
        background: #fff3e0;
        color: #ef6c00;
    }
</style>

<div class="admin-main-container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4 px-2">
        <h2 class="fw-bold ms-2" style="color: #ff6b6b;">User Management & Records</h2>
        <div class="stats-mini">Total Customers: <span class="fw-bold">{{ count($users) }}</span></div>
    </div>

    <div class="accordion" id="userRecords">
        @foreach($users as $user)
        <div class="accordion-item user-accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#user-{{ $user->userid }}">
                    <span class="user-tag">User:</span> {{ $user->uname }}
                    <span class="ms-3 text-muted fw-normal">|</span>
                    <span class="user-tag ms-3">UserID:</span> {{ $user->userid }}
                </button>
            </h2>
            <div id="user-{{ $user->userid }}" class="accordion-collapse collapse" data-bs-parent="#userRecords">
                <div class="accordion-body p-0">
                    <div class="details-grid">

                        <div class="info-section">
                            <h6 class="fw-bold mb-3" style="color: #ff6b6b;"><i class="fas fa-user-circle me-2"></i>User Profile</h6>
                            <div class="detail-row"><span class="detail-label">Username</span><span class="detail-value">{{ $user->uname }}</span></div>
                            <div class="detail-row"><span class="detail-label">UserID</span><span class="detail-value">{{ $user->userid }}</span></div>
                            <div class="detail-row"><span class="detail-label">Contact No</span><span class="detail-value">{{ $user->contactno }}</span></div>
                            <div class="detail-row"><span class="detail-label">Age / Gender</span><span class="detail-value">{{ $user->age }} / {{ $user->gender }}</span></div>
                            <div class="detail-row"><span class="detail-label">Address</span><span class="detail-value">{{ $user->address }}</span></div>
                            <div class="detail-row"><span class="detail-label">Registered</span><span class="detail-value">{{ date('M d, Y', strtotime($user->dateregistered)) }}</span></div>
                        </div>

                        <div class="history-section">
                            <h6 class="fw-bold mb-3" style="color: #ff6b6b;"><i class="fas fa-shopping-bag me-2"></i>Order History</h6>
                            @if(count($user->orders) > 0)
                            <table class="history-table text-center">
                                <thead>
                                    <tr>
                                        <th>OrderID</th>
                                        <th>Date</th>
                                        <th>Payment</th>
                                        <th>Order</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->orders as $order)
                                    <tr>
                                        <td class="fw-bold">#{{ $order->orderid }}</td>
                                        <td>{{ date('M d, Y', strtotime($order->orderdate)) }}</td>
                                        <td>
                                            <span class="status-badge {{ strtolower($order->paymentstatus) }}">
                                                {{ $order->paymentstatus }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                            $order_stats = strtolower($order->status_name);
                                            if($order_stats == 'pending') $order_stats = 'order-pending';
                                            @endphp
                                            <span class="status-badge {{ $order_stats }}">
                                                {{ $order->status_name }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div class="empty-state">
                                <img src="https://cdn-icons-png.flaticon.com/512/11484/11484803.png" width="50" class="mb-2 opacity-50">
                                <p>No orders found for this user.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

