@extends('layout.main')
@section('content')

<style>
    .driver-container {
        max-width: 1150px;
        margin: 0 auto;
    }

    .driver-card {
        background: white;
        border-radius: 25px;
        padding: 30px;
        border: 1px solid #fff1f1;
    }

    .driver-table thead th {
        background: transparent;
        color: #ff6b6b;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
        padding: 20px;
        border-bottom: 1px solid #ff6b6b;
    }

    .driver-table tbody td {
        padding: 25px 15px;
        font-size: 0.95rem;
        color: #555;
        background-color: transparent !important;
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
    }

    .status-available {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .status-unavailable {
        background-color: #ffebee;
        color: #c62828;
    }
</style>

<div class="container mt-5">
    <div class="driver-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold ms-3" style="color: #ff6b6b;">Driver Information</h2>
            <div class="stats-mini">Total Drivers: <span class="fw-bold">{{ $drivers->count() }}</span></div>
        </div>

        <div class="driver-card">
            <div class="table-responsive">
                <table class="table align-middle driver-table">
                    <thead class="text-center">
                        <tr>
                            <th width="20%">License</th>
                            <th width="20%">Driver Name</th>
                            <th width="20%">Contact No.</th>
                            <th width="20%">Plate No.</th>
                            <th width="20%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drivers as $driver)
                        <tr class="text-center">
                            <td class="fw-bold" style="color: #333;">#{{ $driver->license }}</td>
                            <td>{{ $driver->drivername }}</td>
                            <td>{{ $driver->contactno }}</td>
                            <td>{{ $driver->plateno }}</td>
                            <td>
                                @if($driver->isAvailable == 'AV')
                                <span class="status-badge status-available">Available</span>
                                @else
                                <span class="status-badge status-unavailable">Unavailable</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No driver data found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endSection