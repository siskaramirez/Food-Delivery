@extends('layout.main')
@section('content')

<style>
    .about-header {
        padding: 80px 0 40px;
        text-align: center;
    }

    .about-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        margin-bottom: 40px;
        border: 1px solid rgba(0,0,0,0.02);
    }

    .accent-text {
        color: #ff6b6b;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 1.2rem;
    }

    .about-img {
        width: 100%;
        border-radius: 20px;
        height: 400px;
        object-fit: cover;
    }

    .stat-box {
        text-align: center;
        padding: 20px;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: #ff6b6b;
        display: block;
    }

    .stat-label {
        font-weight: 600;
        color: #666;
    }
</style>

<div class="container">
    <div class="about-header">
        <h6 class="accent-text">Our Story</h6>
        <h1 class="display-4 fw-bold">About EatsWay!</h1>
        <div class="mx-auto" style="width: 60px; height: 4px; margin-top: 10px;"></div>
    </div>

    <div class="row  g-5">
        <div class="col-lg-6 pt-5">
            <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?q=80&w=800&auto=format&fit=crop" alt="EatsWay Delivery" class="about-img">
        </div>

        <div class="col-lg-6">
            <div class="about-card">
                <h3 class="fw-bold mb-4">We're on a Mission to Deliver Happiness</h3>
                <p class="text-muted mb-4">
                    Founded in 2023, EatsWay! started with a simple idea: high-quality, chef-prepared meals should be accessible to everyone, anywhere. We believe that food is more than just fuel—it's an experience that brings people together.
                </p>
                <p class="text-muted mb-4">
                    Our team works with local farmers to source the freshest organic ingredients. Every burger, pizza, and salad we deliver is crafted with passion and delivered with a smile.
                </p>
                
                <div class="row mt-5">
                    <div class="col-4 stat-box">
                        <span class="stat-number">10k+</span>
                        <span class="stat-label">Orders</span>
                    </div>
                    <div class="col-4 stat-box">
                        <span class="stat-number">20+</span>
                        <span class="stat-label">Branches</span>
                    </div>
                    <div class="col-4 stat-box">
                        <span class="stat-number">5.0</span>
                        <span class="stat-label">Rating</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row text-center mb-5 g-4">
        <div class="col-md-4">
            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                <div class="mb-3 fs-1">🌱</div>
                <h5 class="fw-bold">Fresh Ingredients</h5>
                <p class="text-muted small">We only use organic, non-GMO ingredients sourced directly from local farms.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                <div class="mb-3 fs-1">⚡</div>
                <h5 class="fw-bold">Fast Delivery</h5>
                <p class="text-muted small">Our localized delivery network ensures your food arrives hot and fresh every time.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                <div class="mb-3 fs-1">👨‍🍳</div>
                <h5 class="fw-bold">Expert Chefs</h5>
                <p class="text-muted small">Our kitchen is led by world-class chefs who prioritize flavor and nutrition.</p>
            </div>
        </div>
    </div>
</div>

@endSection