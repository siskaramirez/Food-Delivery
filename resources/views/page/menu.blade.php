@extends('layout.main')
@section('content')

<style>
    html {
        scroll-behavior: smooth;
    }

    .category-selector-card {
        background: white;
        border: 1px solid #eee;
        border-radius: 12px;
        transition: all 0.3s ease;
        text-transform: uppercase;
        cursor: pointer;
        min-width: 300px;
        min-height: 250px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .category-selector-card:hover {
        border-color: #ff6b6b;
        transform: scale(1.04);
        box-shadow: 0 10px 20px rgba(255, 107, 107, 0.09);
    }

    .category-icon {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        margin-bottom: 25px;
    }

    .category-header-title {
        font-weight: 800;
        text-transform: uppercase;
        padding-left: 30px;
        margin-top: 20px;
        margin-bottom: 10px;
        position: relative;
    }

    .category-header-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 8px;
        height: 45px;
        background-color: #ff6b6b;
        border-radius: 10px;
    }

    .category-anchor {
        scroll-margin-top: 100px;
        margin-bottom: 50px !important;
        margin-top: 50px !important;
    }

    .menu-card {
        background: white;
        border-radius: 20px;
        padding: 0;
        transition: all 0.4s ease;
        border: 1px solid rgba(0, 0, 0, 0.03);
        height: 100%;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

    .menu-card:hover {
        transform: translateY(-13px);
        box-shadow: 0 20px 40px rgba(255, 107, 107, 0.15);
        border-color: #ff6b6b;
    }

    .food-img-container {
        height: 200px;
        width: 100%;
        overflow: hidden;
    }

    .food-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .price-tag {
        color: #ff6b6b;
        font-weight: 800;
        font-size: 1.4rem;
    }

    .btn-order {
        background-color: #ff6b6b;
        color: white;
        border: none;
        border-radius: 50px;
        padding: 8px 20px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: 0.3s;
    }

    .btn-order:hover {
        background-color: #333;
        color: white;
    }

    [id^="category-"] {
        scroll-margin-top: 40px;
    }
</style>

<div class="container-fluid mt-3">
    <div class="container text-center py-5 mb-5">
        <h1 class="fw-bold mb-5">Our Popular Categories</h1>
        <div class="row g-4 justify-content-center">
            @foreach($categories as $categoryName => $items)
            <div class="col-auto">
                <a href="#category-{{ Str::slug($categoryName) }}" class="text-decoration-none">
                    <div class="category-selector-card shadow-sm">
                        <img src="{{ $items->first()['image'] }}" class="category-icon">
                        <h4 class="fw-bold text-dark m-0">{{ $categoryName }}</h4>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <div class="container">
        <div class="text-center">
            <h1 class="fw-bold">Our Popular Menu</h1>
        </div>
        @foreach($categories as $categoryName => $items)
        @if($items->count() > 0)
        <div id="category-{{ Str::slug($categoryName) }}" class="category-anchor mb-4 mt-5">
            <h2 class="category-header-title">{{ $categoryName }}</h2>
        </div>

        <div class="row g-4 mb-5">
            @foreach($items as $food)
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="menu-card d-flex flex-column">
                    <div class="food-img-container">
                        <img src="{{ $food['image'] }}" alt="{{ $food['name'] }}" class="food-img">
                    </div>
                    <div class="text-start p-4">
                        <h5 class="fw-bold mb-2">{{ $food['name'] }}</h5>
                        <p class="text-muted small mb-4">{{ $food['description'] }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <span class="price-tag">₱{{ number_format($food['price'], 0) }}</span>
                            <a href="{{ route('menu.detail', $food['id']) }}" class="btn btn-order">
                                View Order
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        @endforeach
    </div>
</div>

@endSection