@extends('layout.main')
@section('content')

<style>
    .hero-container {
        margin: 50px auto;
        width: 90%;
        max-width: 1300px;
        min-height: 75vh;
        border-radius: 30px;
        overflow: hidden;
        position: relative;
        display: flex;
    }

    .hero-image {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.48);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
        color: white;
        text-align: center;
    }

    .hero-title {
        font-size: 4.3rem;
        font-weight: 800;
        letter-spacing: 1.3px;
        margin-bottom: 12px;
    }

    .hero-tagline {
        font-size: 1.4rem;
        font-weight: 400;
        margin-bottom: 32px;
        max-width: 520px;
    }

    .btn-menu-hero {
        font-size: 1.1rem;
        padding: 10px 42px;
        border-radius: 40px;
        border: none;
        background: #ff6b6b;
        color: white;
        font-weight: 700;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.3s;
    }

    .btn-menu-hero:hover {
        transform: scale(1.07);
        box-shadow: 0 10px 50px rgba(255, 107, 107, 0.45);
    }
</style>

<div class="hero-container">
    <img
        src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=1500&auto=format&fit=crop"
        alt="Food"
        class="hero-image">
    <div class="hero-overlay">
        <h1 class="hero-title">Are you hungry???</h1>

        <div class="hero-tagline">
            <p class="mb-1">Don't wait!!!</p>
            <p class="mb-3">Grab and Eats the way with <strong>EatsWay!</strong></p>
        </div>
        <a href="{{ route('menu.page') }}" class="btn-menu-hero">Menu</a>
    </div>
</div>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const email = "{{ session('user_email') }}" || "{{ old('email') }}";
        const name = "{{ session('user_name') }}" || ("{{ old('fname') }}" ? "{{ old('fname') }} {{ old('lname') }}" : "");
        const phone = "{{ session('user_phone') }}" || "{{ old('phone') }}";
        const address = "{{ session('user_address') }}" || "{{ old('address') }}";
        /* const age = "{{ session('user_age') }}"; */
        const joined = "{{ session('joined_date') }}";

        if (email) {
            localStorage.setItem('user_email', email);
            localStorage.setItem('user_name', name);
            localStorage.setItem('user_phone', phone);
            localStorage.setItem('user_address', address);
            /* localStorage.setItem('user_age', age); */
            localStorage.setItem('user_joined', joined);
            localStorage.setItem('eatsway_authenticated', 'true');
        }

        if (typeof updateNavbar === 'function') {
            updateNavbar();
        }
    });
</script>
@endif

@endSection