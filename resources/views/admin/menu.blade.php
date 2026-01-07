@extends('layout.main')
@section('content')

<style>
    .menu-inventory-wrapper {
        max-width: 1150px;
        margin: 0 auto;
    }

    .category-header-title {
        font-weight: 800;
        text-transform: uppercase;
        padding-left: 30px;
        margin-bottom: 25px;
        position: relative;
        color: #333;
    }

    .category-header-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 8px;
        height: 100%;
        background-color: #ff6b6b;
        border-radius: 10px;
    }

    .menu-card {
        background: white;
        border-radius: 25px;
        transition: all 0.4s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .menu-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(255, 107, 107, 0.15);
        border-color: #ff6b6b;
    }

    .food-img-container {
        height: 180px;
        width: 100%;
        overflow: hidden;
    }

    .food-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .stock-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        backdrop-filter: blur(3px);
    }

    .price-tag {
        color: #ff6b6b;
        font-weight: 800;
        font-size: 1.3rem;
    }

    .admin-qty-group {
        background: #f8f9fa;
        border-radius: 50px;
        padding: 5px;
        border: 1px solid #eee;
    }

    .admin-qty-group .form-control {
        background: transparent;
        border: none;
        text-align: center;
        font-weight: 600;
        color: #333;
    }

    .admin-qty-group .form-control:focus {
        box-shadow: none;
    }

    .btn-order {
        background-color: #ff6b6b;
        color: white;
        border: none;
        border-radius: 50px !important;
        padding: 8px 25px;
        font-weight: 700;
        font-size: 0.85rem;
        transition: 0.3s;
    }

    .btn-order:hover {
        background-color: #333;
        color: white;
        transform: scale(1.05);
    }
</style>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="fw-bold ms-5" style="color: #ff6b6b;">Inventory Control</h2>
        <div class="stats-mini me-5">Total Categories: <span class="fw-bold">{{ $categories->count() }}</span></div>
    </div>

    <div class="menu-inventory-wrapper">
        @foreach($categories as $categoryName => $items)
        <div id="category-{{ Str::slug($categoryName) }}" class="category-anchor mb-4 mt-5">
            <h3 class="category-header-title">{{ $categoryName }}</h3>
        </div>

        <div class="row g-4">
            @foreach($items as $food)
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="menu-card d-flex flex-column h-100">
                    <div class="food-img-container position-relative">
                        <img src="{{ asset('images/' . $food->foodcode . '.jpg') }}" alt="{{ $food->foodname }}" class="food-img">
                        <div class="stock-badge">
                            Stock: {{ $food->quantity }}
                        </div>
                    </div>

                    <div class="p-4 text-center">
                        <h6 class="text-muted small mb-1">{{ $food->foodcode }}</h6>
                        <h5 class="fw-bold mb-3" style="font-size: 1.1rem;">{{ $food->foodname }}</h5>

                        <div class="price-tag mb-4">₱{{ $food->price }}</div>

                        <form action="{{ route('menu.add', $food->foodcode) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="input-group admin-qty-group">
                                <input type="number" name="new_quantity" class="form-control"
                                    placeholder="Qty" min="1" required>
                                <button class="btn btn-order" type="submit">ADD</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>

@endSection