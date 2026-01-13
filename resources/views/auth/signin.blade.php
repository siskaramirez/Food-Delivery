@extends('layout.main')
@section('content')

<style>
    .signin-wide-wrapper {
        background: white;
        border-radius: 30px;
        width: 100%;
        max-width: 1100px;
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

    .btn-admin-submit {
        background-color: transparent;
        color: #6c757d;
        border: 2px solid #6c757d;
        border-radius: 12px;
        transition: all 0.3s;
    }

    .btn-admin-submit:hover {
        color: white;
        background-color: #6c757d;
    }

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

    .login-divider {
        display: flex;
        align-items: center;
        text-align: center;
        color: #6c757d;
        font-size: 1.1rem;
    }

    .login-divider::before,
    .login-divider::after {
        content: "";
        flex: 1;
        border-bottom: 1px solid #dee2e6;
    }

    .login-divider::before {
        margin-right: 20px;
    }

    .login-divider::after {
        margin-left: 20px;
    }

    @media (max-width: 992px) {
        .signin-form-side {
            width: 100%;
            padding: 40px;
        }

        .signin-wide-wrapper {
            max-width: 500px;
        }
    }
</style>

<div class="container d-flex justify-content-center align-items-center py-5">
    <div class="signin-wide-wrapper shadow-sm border-0 d-flex overflow-hidden">

        <div class="signin-form-side">
            @php $isAdmin = request()->query('role') === 'admin'; @endphp
            <h2 class="fw-bold mb-2" style="color: {{ $isAdmin ? '#6c757d' : '#ff6b6b' }};">
                {{ $isAdmin ? 'Admin Portal' : 'Sign In' }}
            </h2>
            <p class="text-muted small mb-4">{{ $isAdmin ? 'Administrator access only.' : 'Welcome back! Please enter your details.' }}</p>

            <form action="{{ route('signin.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="role" value="{{ request()->query('role', 'customer') }}">
                <div class="mb-3">
                    <label for="email" class="form-label small fw-bold">Email address</label>
                    <input type="email" id="email" name="email" class="form-control custom-input" placeholder="Enter your email" required>
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-1 mb-4">
                    <label for="password" class="form-label small fw-bold">Password</label>
                    <input type="password" id="password" name="password" class="form-control custom-input" placeholder="Enter password" required>
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!--
                <div class="text-end mb-4">
                    <a href="#" class="small text-muted">Forgot password?</a>
                </div>
                -->

                <button type="submit" class="btn {{ $isAdmin ? 'btn-admin-submit' : 'btn-signin-submit' }} w-100 py-2 fw-bold">
                    {{ $isAdmin ? 'Login' : 'Sign In' }}
                </button>
            </form>

            @if(!$isAdmin)
            <div class="text-center mt-5">
                <div class="login-divider mb-4">
                    <span>or</span>
                </div>

                <h2 class="fw-bold">
                    <a href="{{ route('signup.page') }}" class="btn-signup-link">Go to Sign Up</a>
                </h2>
            </div>
            @endif
        </div>

        <div class="signin-image-side d-none d-lg-block">
            <div class="image-overlay">
                @if ($isAdmin)
                <h1 class="fw-bold">Welcome, Admin!</h1>
                <p class="small" style="font-size: 1.1rem;">
                    Every click you make, every task you complete, <br>brings us closer to excellence—keep pushing forward!
                </p>
                @else
                <h1 class="fw-bold">Hungry?</h1>
                <p class="small" style="font-size: 1.1rem;">
                    Your favorite meals are just a few clicks away.
                </p>
                @endif
            </div>
        </div>
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