@extends('layout.main')
@section('content')

<style>
    /* Wide Wrapper Styling */
    .signup-wide-wrapper {
        background: white;
        border-radius: 30px;
        width: 100%;
        max-width: 1100px; /* Wide format */
        min-height: 500px;
    }

    /* Left Side Image Styling */
    .signup-image-side {
        width: 40%;
        background: url('https://images.unsplash.com/photo-1473093226795-af9932fe5856?q=80&w=1000&auto=format&fit=crop');
        position: relative;
    }

    .image-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.30); /* Darkens image slightly for text readability */
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 40px;
        color: white;
    }

    /* Form Side Styling */
    .signup-form-side {
        width: 60%;
        padding: 50px;
    }

    .form-check-input:checked {
        background-color: #ff6b6b;
        border-color: #ff6b6b;
    }

    @media (max-width: 992px) {
        .signup-form-side { width: 100%; }
        .signup-wide-wrapper { max-width: 600px; }
    }

    .custom-input {
        background-color: #fcfcfc;
        border: 2px solid #f0f0f0;
        border-radius: 12px;
        padding: 10px;
        transition: 0.3s;
    }

    .custom-input:focus {
        border-color: #ff6b6b; 
        box-shadow: none;
    }

    .btn-signup-submit {
        background-color: transparent;
        color: #ff6b6b;
        border: 2px solid #ff6b6b;
        border-radius: 12px; /* Matches the input rounding */
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .btn-signup-submit:hover {
        background-color: #ff6b6b;
        color: white;
    }

    /* Login-style Hover Link */
    .btn-signin-link {
        color: #ff6b6b;
        font-weight: 700;
        text-decoration: none;
        padding: 5px 8px;
        border-bottom: 2px solid transparent;
        transition: all 0.3s;
    }

    .btn-signin-link:hover {
        border-bottom: 2px solid #ff6b6b;
        color: #ff6b6b;
    }
</style>

<div class="container d-flex justify-content-center align-items-center py-5">
    <div class="signup-wide-wrapper shadow-sm border-0 d-flex overflow-hidden">
        
        <div class="signup-image-side d-none d-lg-block">
            <div class="image-overlay">
                <h2 class="fw-bold">Fresh Food,<br>Fast Delivery.</h2>
                <p class="small" style="font-size: 1.1rem;">Join the EatsWay community today!</p>
            </div>
        </div>

        <div class="signup-form-side">
            <h2 class="fw-bold mb-2" style="color: #ff6b6b;">Create an account</h2>
            <p class="text-muted small mb-4">Nice to meet you! Please enter your details to join us.</p>
            
            <form action="{{ route('signup.submit') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">First Name</label>
                            <input type="text" name="first_name" class="form-control custom-input" placeholder="Juan" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">E-mail</label>
                            <input type="email" name="email" class="form-control custom-input" placeholder="juan@email.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Birthday</label>
                            <input type="date" name="birthday" class="form-control custom-input" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Address</label>
                            <input type="text" name="address" class="form-control custom-input" placeholder="Street, City" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Last Name</label>
                            <input type="text" name="last_name" class="form-control custom-input" placeholder="Dela Cruz" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control custom-input" placeholder="••••••••" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold d-block" required>Gender</label>
                            <div class="d-flex flex-column gap-1 mt-2">
                                <div class="form-check"><input class="form-check-input" type="radio" name="gender" value="male" id="m"><label class="form-check-label small" for="m">Male</label></div>
                                <div class="form-check"><input class="form-check-input" type="radio" name="gender" value="female" id="f"><label class="form-check-label small" for="f">Female</label></div>
                                <div class="form-check"><input class="form-check-input" type="radio" name="gender" value="other" id="o"><label class="form-check-label small" for="o">Other</label></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-signup-submit w-100 py-2 fw-bold">Sign Up</button>
                </div>
            </form>

            <div class="text-center mt-5 border-top pt-4" style="font-size: 1.2rem;">
                <p class="text-muted small mb-1">Already have an account?</p>
                <a href="{{ route('signin.page') }}" class="btn-signin-link">Go to Sign In</a>
            </div>
        </div>
    </div>
</div>

@endsection