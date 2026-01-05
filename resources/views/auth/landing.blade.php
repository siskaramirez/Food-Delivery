@extends('layout.main')
@section('content')

<style>
    .selection-container {
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .choice-card {
        background: white;
        border-radius: 40px;
        padding: 50px;
        transition: all 0.4s ease;
        border: 2px solid transparent;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    }

    .choice-card:hover {
        transform: translateY(-15px);
        border-color: #ff6b6b;
        box-shadow: 0 20px 40px rgba(255, 107, 107, 0.1);
    }

    .icon-box {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background-color: #fff5f0;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 25px;
    }

    .icon-box img {
        width: 60%;
        height: auto;
    }

    .btn-select {
        margin-top: 20px;
        padding: 10px 40px;
        border-radius: 50px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
    }

    .user-btn {
        background-color: transparent;
        color: #ff6b6b;
        border: 2px solid #ff6b6b;
    }

    .user-btn:hover {
        background-color: #ff6b6b;
        color: white;
        transform: scale(1.07);
    }

    .admin-btn {
        background-color: transparent;
        color: #333;
        border: 2px solid #333;
    }

    .admin-btn:hover {
        background-color: #333;
        color: white;
        transform: scale(1.07);
    }
</style>

<div class="selection-container">
    <div class="container mt-5 mb-4">
        <div class="text-center mb-5">
            <h1 class="fw-bold display-4">Welcome to <span style="color: #ff6b6b;">EatsWay!</span></h1>
            <p class="text-muted fs-5">Please select your destination to continue.</p>
        </div>

        <div class="row justify-content-center g-4">
            <div class="col-12 col-md-5 col-lg-4">
                <a href="{{ route('signup.page') }}" class="text-decoration-none">
                    <div class="choice-card text-center">
                        <div class="icon-box">
                            <img src="https://cdn-icons-png.flaticon.com/512/3448/3448609.png" alt="User">
                        </div>
                        <h2 class="fw-bold text-dark">Customer</h2>
                        <p class="text-muted">Order delicious meals and track your delivery.</p>
                        <span class="btn btn-select user-btn user-btn:hover">Sign Up</span>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-5 col-lg-4">
                <a href="{{ route('signin.page', ['role' => 'admin']) }}" class="text-decoration-none">
                    <div class="choice-card text-center">
                        <div class="icon-box" style="background-color: #f0f0f0;">
                            <img src="https://cdn-icons-png.flaticon.com/512/2328/2328966.png" alt="Admin" style="filter: grayscale(1);">
                        </div>
                        <h2 class="fw-bold text-dark">Administrator</h2>
                        <p class="text-muted">Manage menus, orders, and platform settings.</p>
                        <span class="btn btn-select admin-btn admin-btn:hover">Sign In</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

@endSection