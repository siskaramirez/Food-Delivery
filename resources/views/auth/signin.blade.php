@extends('layout.main')
@section('content')

<style>
    /* Layout Styling */
    .signin-wide-wrapper {
        background: white;
        border-radius: 30px;
        width: 100%;
        max-width: 1100px; /* Slightly narrower than signup since there are fewer fields */
        min-height: 500px;
    }

    .signin-form-side {
        width: 50%;
        padding: 60px;
    }

    .signin-image-side {
        width: 50%;
        background: url('https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?q=80&w=1000') center/cover;
        position: relative;
    }

    .image-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.43);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        text-align: center;
    }

    /* Input & Button Styling */
    .custom-input {
        background-color: #fcfcfc;
        border: 2px solid #f0f0f0;
        border-radius: 12px;
        padding: 12px;
        transition: 0.3s;
    }

    .custom-input:focus {
        border-color: #ff6b6b;
        box-shadow: none;
    }

    .btn-signin-submit {
        background-color: transparent;
        color: #ff6b6b;
        border: 2px solid #ff6b6b;
        border-radius: 12px;
        transition: all 0.3s;
    }

    .btn-signin-submit:hover {
        color: white;
        background-color: #ff6b6b;
    }

    /* Links & Hovers */
    .hover-red:hover {
        color: #ff6b6b;
    }

    .btn-signup-link {
        font-size: 1.5rem;
        color: #ff6b6b;
        font-weight: 700;
        text-decoration: none;
        padding: 5px 8px;
        transition: 0.3s;
        border-bottom: 2px solid transparent;
    }

    .btn-signup-link:hover {
        color: #ff6b6b;
        border-bottom: 2px solid #ff6b6b;
    }

    @media (max-width: 992px) {
        .signin-form-side { width: 100%; padding: 40px; }
        .signin-wide-wrapper { max-width: 500px; }
    }
</style>

<div class="container d-flex justify-content-center align-items-center py-5">
    <div class="signin-wide-wrapper shadow-sm border-0 d-flex overflow-hidden">
        
        <div class="signin-form-side">
            <h2 class="fw-bold mb-2" style="color: #ff6b6b;">Sign In</h2>
            <p class="text-muted small mb-4">Welcome back! Please enter your details.</p>
            
            <form action="{{ route('signin.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Email address</label>
                    <input type="email" name="email" class="form-control custom-input" placeholder="Enter your email" required>
                </div>
                
                <div class="mb-1">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control custom-input" placeholder="Enter password" required>
                </div>

                <div class="text-end mb-4">
                    <a href="#" class="small text-muted">Forgot your Pass?</a>
                </div>

                <button type="submit" class="btn btn-signin-submit w-100 py-2 fw-bold">Sign In</button>
            </form>

            <div class="text-center mt-5">
                <p class="text-muted small mb-4">or</p>
                <h2 class="fw-bold">
                    <a href="{{ route('signup.page') }}" class="btn-signup-link">Go to Sign Up</a>
                </h2>
            </div>
        </div>

        <div class="signin-image-side d-none d-lg-block">
            <div class="image-overlay">
                <h1 class="fw-bold">Hungry?</h1>
                <p class="small" style="font-size: 1.1rem;">Your favorite meals are just a few clicks away.</p>
            </div>
        </div>
    </div>
</div>

@endSection