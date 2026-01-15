<style>
    .custom-dialog {
        border: none;
        border-radius: 20px;
        padding: 30px;
        width: 90%;
        max-width: 400px;
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

    /* Ilagay ito sa iyong main CSS file o sa <style> tag ng admin page */
    .status-disabled {
        color: #ccc !important;
        background-color: #f8f9fa !important;
        cursor: not-allowed;
    }

    .status-active {
        color: #333;
    }
</style>

<div class="modal fade" id="editOrder-{{ $order->orderid }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered mx-auto" style="max-width: 380px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pt-3 px-3 pb-0">
                <h6 class="fw-bold m-0" style="color: #ff6b6b;">Manage Order #{{ $order->orderid }}</h6>
                <button type="button" class="btn-close small" data-bs-dismiss="modal" style="font-size: 0.7rem;"></button>
            </div>

            <form id="updateForm-{{ $order->orderid }}" action="{{ route('order.update', $order->orderid) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-3">
                    @php
                    $isStatusLocked = in_array($order->order_status_id, [2, 3]);

                    $statusLabel = 'Pending';
                    if($order->order_status_id == 2) $statusLabel = 'Completed';
                    if($order->order_status_id == 3) $statusLabel = 'Cancelled';
                    @endphp

                    <div class="mb-2">
                        <label class="fw-bold text-muted mb-1" style="font-size: 0.7rem;">ORDER STATUS</label>
                        @if($isStatusLocked)
                        <div class="form-control form-control-sm border-0 bg-light text-start" style="border-radius: 10px; font-size: 0.85rem;">
                            {{ $statusLabel }}
                        </div>
                        <input type="hidden" name="order_status" value="{{ $statusLabel }}">
                        @else
                        <select name="order_status" class="form-select form-select-sm border-0 bg-light" style="border-radius: 10px; font-size: 0.85rem;">
                            <option value="Pending" {{ $order->order_status_id == 1 ? 'selected' : '' }}>Pending</option>
                            <option value="Completed">Completed</option>
                        </select>
                        @endif
                    </div>

                    @if($order->order_status_id == 3)
                    @php
                    $payment = DB::table('payments')->where('orderid', $order->orderid)->first();
                    @endphp

                    @if($payment && !in_array($payment->paymentmethod, ['Cash on Delivery', 'Cash']))
                    <div class="mb-2">
                        <label class="fw-bold text-muted mb-1" style="font-size: 0.7rem;">PAYMENT STATUS</label>
                        <select name="payment_status" class="form-select form-select-sm border-0 bg-light">
                            <option value="Paid" {{ $order->paymentstatus == 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="Refunded" {{ $order->paymentstatus == 'Refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    @endif
                    @endif

                    @if($order->deliveryneeded == 1)
                    <div id="delivery-section-{{ $order->orderid }}" class="{{ $order->order_status_id != 2 ? 'd-none' : '' }}">
                        @php
                        $currentStatus = $order->deliverystatus ?? 'Pending';
                        $isAssigned = in_array($currentStatus, ['Assigned', 'Picked Up', 'En Route', 'Delivered']);
                        @endphp

                        <div class="mb-2">
                            @if(!$isAssigned)
                            <label class="fw-bold text-muted mb-1" style="font-size: 0.7rem;">ASSIGN DRIVER</label>
                            <select name="driver_license" id="driverSelect-{{ $order->orderid }}" class="form-select form-select-sm border-0 bg-light" style="border-radius: 10px; font-size: 0.85rem;">
                                <option value="" id="defaultDriver-{{ $order->orderid }}" disabled selected hidden>Select Driver</option>
                                @foreach($drivers as $driver)
                                @php
                                $isUnavailable = ($driver->isAvailable == 'UA' && $order->license !== $driver->license);
                                @endphp
                                <option value="{{ $driver->license }}"
                                    {{ $order->license == $driver->license ? 'selected' : '' }}
                                    {{ $isUnavailable ? 'disabled' : '' }}
                                    style="{{ $isUnavailable ? 'color: #ccc; background-color: #f8f9fa;' : 'color: #333;' }}">
                                    {{ $driver->license }} {{ $isUnavailable ? '(Unavailable)' : '' }}
                                </option>
                                @endforeach
                            </select>
                            @else
                            <label class="fw-bold text-muted mb-1" style="font-size: 0.7rem;">ASSIGNED DRIVER</label>
                            <div class="form-control form-control-sm border-0 bg-light text-start" style="border-radius: 10px; font-size: 0.85rem;">
                                {{ $order->license }}
                            </div>
                            <input type="hidden" name="driver_license" value="{{ $order->license }}">
                            @endif
                        </div>

                        <div class="mb-2">
                            <label class="fw-bold text-muted mb-1" style="font-size: 0.7rem;">DELIVERY STATUS</label>
                            <select name="delivery_status" class="form-select form-select-sm border-0 bg-light" style="border-radius: 10px; font-size: 0.85rem;">
                                @php
                                $currentStatus = $order->deliverystatus ?? 'Pending';

                                $statusSequence = ['Pending', 'Assigned', 'Picked Up', 'En Route', 'Delivered'];

                                $currentIndex = array_search($currentStatus, $statusSequence);
                                @endphp

                                @foreach($statusSequence as $index => $statusName)
                                @php
                                $isDisabled = ($index < $currentIndex || $index> $currentIndex + 1);

                                    if($currentStatus == 'Delivered' && $statusName != 'Delivered') {
                                    $isDisabled = true;
                                    }
                                    @endphp

                                    <option value="{{ $statusName }}"
                                        {{ $currentStatus == $statusName ? 'selected' : '' }}
                                        {{ $isDisabled ? 'disabled' : '' }}
                                        class="{{ $isDisabled ? 'status-disabled' : 'status-active' }}">
                                        {{ $statusName }}
                                    </option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer border-0 pt-0 px-3 pb-3">
                    <button type="button"
                        onclick="confirmUpdate('{{ $order->orderid }}')"
                        class="btn w-100 fw-bold btn-sm py-2"
                        style="background: #ff6b6b; color: white; border-radius: 12px; font-size: 0.8rem;">SAVE CHANGES
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<dialog id="confirmUpdateModal" class="custom-dialog">
    <div class="dialog-content text-start">
        <h3 class="fw-bold">Confirm Update</h3>
        <p class="text-muted">Are you sure you want to save the changes for order #<span id="update-order-id"></span>?</p>
        <div class="dialog-actions">
            <button type="button" onclick="closeUpdateModal()" class="btn-cancel">Cancel</button>
            <button type="button" id="btnFinalSubmit" class="btn-confirm">Confirm</button>
        </div>
    </div>
</dialog>

<script>
    const updateModal = document.getElementById('confirmUpdateModal');
    let pendingOrderId = null;

    function confirmUpdate(orderId) {
        pendingOrderId = orderId;
        document.getElementById('update-order-id').innerText = orderId;
        updateModal.showModal();
    }

    function closeUpdateModal() {
        updateModal.close();
        pendingOrderId = null;
    }

    document.getElementById('btnFinalSubmit').addEventListener('click', function() {
        if (pendingOrderId) {
            const form = document.getElementById(`updateForm-${pendingOrderId}`);
            const confirmBtn = this;

            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

            form.submit();
        }
    });

    updateModal.addEventListener('click', (e) => {
        if (e.target === updateModal) closeUpdateModal();
    });
</script>