@extends('layout.main')
@section('content')

<style>
    /* Main Card Layout */
    .main-profile-card {
        min-height: 500px;
        border-radius: 30px;
        background-color: white;
    }

    /* Sidebar Gradient - Clean & Professional */
    .profile-sidebar {
        background: linear-gradient(130deg, #2d3436ed 0%, #f06e6eef 100%);
        color: white;
    }

    /* Avatar Styling */
    .profile-avatar-container img {
        width: 150px;
        border: 5px solid white;
        transition: transform 0.3s ease;
    }

    /* Info Labels */
    .info-label {
        color: #ff6b6b;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Buttons */
    .btn-edit-profile {
        background-color: white;
        color: #333;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-edit-profile:hover {
        background-color: #333 !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* Stats Boxes */
    .stat-box {
        background-color: #f8e9e1ff;
    }

    /* Responsive adjustment */
    @media (max-width: 991px) {
        .main-profile-card {
            flex-direction: column;
        }
        .profile-sidebar {
            padding: 40px 20px !important;
        }
        .profile-avatar-container img {
            width: 120px;
        }
    }
</style>

<div class="container py-5 mt-4">
    <div class="main-profile-card shadow-sm border-0 d-flex flex-wrap overflow-hidden">
        
        <div class="col-lg-4 p-5 d-flex flex-column align-items-center text-center profile-sidebar">
            <div class="profile-avatar-container mb-4">
                <img src="{{ $user['profile_pix'] }}" alt="Profile" class="rounded-circle shadow-sm">
            </div>
            <h2 class="fw-bold mb-2 text-white">{{ $user['name'] }}</h2>
            <p class="text-white small mb-4">Member since {{ $user['joined'] }}</p>
            
            <button class="btn btn-edit-profile btn-danger rounded-pill px-5 w-100 fw-bold mt-auto mb-1">Edit Profile</button>
        </div>

        <div class="col-lg-8 p-5">
            <h3 class="fw-bold mb-4 text-dark">Account Information</h3>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="info-label">Full Name</label>
                    <p class="fw-bold fs-5 border-bottom pb-2 text-dark">{{ $user['name'] }}</p>
                </div>
                <div class="col-md-6">
                    <label class="info-label">Email Address</label>
                    <p class="fw-bold fs-5 border-bottom pb-2 text-dark">{{ $user['email'] }}</p>
                </div>
                <div class="col-md-6">
                    <label class="info-label">Phone Number</label>
                    <p class="fw-bold fs-5 border-bottom pb-2 text-dark">{{ $user['phone'] }}</p>
                </div>
                <div class="col-md-6">
                    <label class="info-label">Address</label>
                    <p class="fw-bold fs-5 border-bottom pb-2 text-dark">{{ $user['address'] }}</p>
                </div>
            </div>

            <div class="mt-4">
                <h4 class="fw-bold mb-3 text-dark">Quick Stats</h4>
                <div class="d-flex gap-3">
                    <div class="stat-box p-3 rounded-4 flex-grow-1 text-center">
                        <span class="d-block fw-bold fs-4" style="color: #ff6b6b;">12</span>
                        <span class="text-muted small">Orders Made</span>
                    </div>
                    <div class="stat-box p-3 rounded-4 flex-grow-1 text-center">
                        <span class="d-block fw-bold fs-4" style="color: #ff6b6b;">₱2,450</span>
                        <span class="text-muted small">Total Spent</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endSection