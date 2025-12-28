@extends('layout.main')
@section('content')

<style>
    .menu-section {
        padding: 50px 0;
    }

    .menu-card {
        background: white;
        border-radius: 30px;
        padding: 25px;
        transition: all 0.4s ease;
        border: 1px solid rgba(0, 0, 0, 0.03);
        height: 100%;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
    }

    .menu-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 20px 40px rgba(255, 107, 107, 0.15);
        border-color: #ff6b6b;
    }

    .food-img-container {
        background-color: #efc9b7ff;
        border-radius: 25px;
        padding: 10px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .food-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 20px;
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
        padding: 10px 20px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: 0.3s;
    }

    .btn-order:hover {
        background-color: #333;
        color: white;
    }

    .food-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #333;
    }

    /* Location Bar Styles */
    .location-wrapper {
        max-width: 600px;
        margin: 20px auto 100px auto;
        text-align: left;
    }

    .location-title {
        font-weight: 800;
        font-size: 2.5rem;
        color: #1a1a1a;
        margin-bottom: 30px;
    }

    .location-pin {
        color: #ff6b6b;
        font-size: 1.3rem;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 650;
    }

    .location-bar {
        background: white;
        border-radius: 100px;
        padding: 10px 15px 10px 20px;
        display: flex;
        align-items: center;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        border: 1px solid #eee;
        width: 100%;
    }

    .location-bar input {
        border: none;
        outline: none;
        flex-grow: 1;
        font-size: 1.1rem;
        color: #666;
        text-align: left;
        background: transparent;
    }

    .btn-done {
        background-color: transparent;
        color: #ff6b6b;
        border: 2px solid #ff6b6b;
        border-radius: 50px;
        padding: 10px 20px;
        font-weight: 700;
        font-size: 1rem;
        transition: 0.3s;
        white-space: nowrap;
    }

    .btn-done:hover {
        background-color: #ff6b6b;
        color: white;
        transform: scale(1.07);
    }
</style>

<div class="container menu-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">

                <div class="location-wrapper">
                    <div class="location-pin mb-2 ms-3">
                        <span style="font-size: 1.5rem;">📍</span>
                        <span id="display-location">Location</span>
                    </div>

                    <h1 class="fw-bold mb-4 ms-4" style="font-size: 2.5rem; line-height: 1.2;">
                        <strong>Discover restaurants that deliver near you.</strong>
                    </h1>

                    <form class="location-bar" action="{{ route('menu.page') }}" method="GET" onsubmit="saveMenuLocation()">
                        <input
                            type="text"
                            list="location-options"
                            id="location-choice"
                            name="location"
                            placeholder="Enter your delivery address"
                            value="{{ request('location') }}">
                        <datalist id="location-options">
                            <option value="Caloocan City, Metro Manila">
                            <option value="Manila City, Metro Manila">
                            <option value="Marikina City, Metro Manila">
                            <option value="Pasay City, Metro Manila">
                            <option value="Quezon City, Metro Manila">
                            <option value="Taguig City, Metro Manila">
                            <option value="Valenzuela City, Metro Manila">
                        </datalist>

                        <button type="submit" class="btn-done">
                            <span style="font-size: 0.9rem;">✔</span> DONE
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="row g-4 justify-content-center">
        @foreach($foods as $food)
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="menu-card text-center">
                <div class="food-img-container">
                    <img src="{{ $food['image'] }}" alt="{{ $food['name'] }}" class="food-img">
                </div>

                <h5 class="food-title mb-1">{{ $food['name'] }}</h5>
                <p class="text-muted small mb-4">{{ $food['description'] }}</p>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="price-tag">₱{{ number_format($food['price'], 0) }}</span>

                    <a href="{{ route('menu.detail', $food['id']) }}" class="btn btn-order">
                        View Order
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    function saveMenuLocation() {
        const selectedLocation = document.getElementById('location-choice').value;

        if (selectedLocation) {
            localStorage.setItem('user_address', selectedLocation);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const savedAddress = localStorage.getItem('user_address');
        const displayPin = document.getElementById('display-location');

        if (savedAddress && displayPin) {
            displayPin.innerText = savedAddress;
        }
    });
    `
    function addToCart(name, price, image) {
        let cart = JSON.parse(localStorage.getItem('eatsway_cart')) || [];

        const existingItem = cart.find(item => item.name === name);

        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                name,
                price,
                image,
                quantity: 1
            });
        }

        localStorage.setItem('eatsway_cart', JSON.stringify(cart));
        alert(name + " added to cart!");
    }
    `
</script>

@endSection