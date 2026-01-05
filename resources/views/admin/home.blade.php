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
        box-shadow: 0 8px 25px rgba(255, 107, 107, 0.05);
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
        border-bottom: 1px solid #fdf0ee;
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
        border-bottom: 1px solid #fff1f1;
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
    }

    .paid {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .pending {
        background: #fff3e0;
        color: #ef6c00;
    }

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
                            <table class="history-table">
                                <thead>
                                    <tr>
                                        <th>OrderID</th>
                                        <th>Date</th>
                                        <th>Payment</th>
                                        <th>Status</th>
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
                                        <td class="text-muted">{{ $order->status_name }}</td>
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

                        <div class="pt-3 border-top">
                            <form id="delete-form-{{ $user->userid }}" action="{{ route('user.delete', $user->userid) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="openDeleteModal('{{ $user->userid }}', '{{ $user->uname }}')" class="btn btn-sm w-100 fw-bold"
                                    style="background-color: #fff1f1; color: #dc3545; border: 1px solid #f8d7da; border-radius: 10px; padding: 10px;">
                                    <i class="fas fa-trash-alt me-2"></i>Remove Account
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<dialog id="deleteUserModal" class="custom-dialog">
    <div class="dialog-content">
        <h3 class="fw-bold mb-3">Confirm Deletion</h3>
        <p class="text-muted text-center">Are you sure you want to remove<br><b><span id="modal-user-name"></span></b>?<br>This will permanently remove all their records.</p>
        <div class="dialog-actions">
            <button type="button" onclick="closeDeleteModal()" class="btn-cancel">Cancel</button>
            <button type="button" id="btnConfirmDeleteUser" class="btn-confirm">Delete</button>
        </div>
    </div>
</dialog>

<script>
    const deleteModal = document.getElementById('deleteUserModal');
    let userToDelete = null;

    function openDeleteModal(userId, userName) {
        userToDelete = userId;
        document.getElementById('modal-user-name').innerText = userName;
        deleteModal.showModal();
    }

    function closeDeleteModal() {
        deleteModal.close();
        userToDelete = null;
    }

    document.getElementById('btnConfirmDeleteUser').addEventListener('click', function() {
        if (userToDelete) {
            const form = document.getElementById(`delete-form-${userToDelete}`);
            const confirmBtn = this;
            
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            
            form.submit();
        }
    });

    deleteModal.addEventListener('click', (e) => {
        if (e.target === deleteModal) closeDeleteModal();
    });
</script>

@endSection