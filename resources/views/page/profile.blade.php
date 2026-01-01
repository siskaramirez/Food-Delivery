@extends('layout.main')
@section('content')

<style>
    .main-profile-card {
        min-height: 500px;
        border-radius: 30px;
        background-color: white;
    }

    .profile-sidebar {
        background: linear-gradient(130deg, #2d3436ed 0%, #f06e6eef 100%);
        color: white;
    }

    .profile-avatar-container img {
        width: 150px;
        border: 3px solid white;
        transition: transform 0.3s ease;
    }

    .info-label {
        color: #ff6b6b;
        font-size: 0.80rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-edit-profile {
        background-color: white;
        color: #333;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-edit-profile:hover {
        background-color: #333;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1) !important;
    }

    .stat-box {
        background-color: #f8e9e1ff;
    }

    .edit-input {
        border: none;
        border-bottom: 2px solid #ff6b6b;
        background: transparent;
        width: 100%;
        font-weight: bold;
        font-size: 1.25rem;
        outline: none;
        padding-bottom: 5px;
        color: #333;
    }

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

<div class="container py-5 mt-2">
    <div class="main-profile-card shadow-sm border-0 d-flex flex-wrap overflow-hidden">

        <div class="col-lg-4 p-5 d-flex flex-column align-items-center text-center profile-sidebar">
            <div class="profile-avatar-container mb-4">
                <img src="{{ $user['profile_pix'] }}" alt="Profile" class="rounded-circle shadow-sm">
            </div>
            <h2 class="fw-bold mb-2 text-white" id="sidebar-name">{{ $user['name'] }}</h2>
            <p class="text-white mb-4 d-none" id="member-since">Member since {{ $user['joined'] }}</p>

            <a href="{{ route('profile.edit') }}" class="btn btn-edit-profile btn-danger rounded-pill px-5 w-100 fw-bold mt-auto mb-1">Edit Profile</a>
        </div>

        <div class="col-lg-8 p-5">
            <h3 class="fw-bold mb-4 text-dark">Account Information</h3>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="info-label">Full Name</label>
                    <p id="display-name" class="fw-bold fs-5 border-bottom pb-2 text-dark">{{ $user['name'] }}</p>
                </div>
                <div class="col-md-6">
                    <label class="info-label">Email Address</label>
                    <p id="display-email" class="fw-bold fs-5 border-bottom pb-2 text-dark">{{ $user['email'] }}</p>
                </div>
                <div class="col-md-6">
                    <label class="info-label">Phone Number</label>
                    <p id="display-phone" class="fw-bold fs-5 border-bottom pb-2 text-dark">{{ $user['phone'] }}</p>
                </div>
                <div class="col-md-6">
                    <label class="info-label">Address</label>
                    <p id="display-address" class="fw-bold fs-5 border-bottom pb-2 text-dark">{{ $user['address'] }}</p>
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const data = {
            name: localStorage.getItem('user_name'),
            email: localStorage.getItem('user_email'),
            address: localStorage.getItem('user_address'),
            phone: localStorage.getItem('user_phone'),
            joined: localStorage.getItem('user_joined')
        };

        if (data.name) {
            const displayName = document.getElementById('display-name');
            if (displayName) {
                displayName.innerText = data.name;
            }

            const sidebarName = document.getElementById('sidebar-name');
            if (sidebarName) {
                sidebarName.innerText = data.name;
            }
        }

        if (data.email) {
            const displayEmail = document.getElementById('display-email');
            if (displayEmail) {
                displayEmail.innerText = data.email;
            }

            const memberSince = document.getElementById('member-since');
            if (memberSince) {
                const displayJoined = data.joined || "{{ $user['joined'] }}";

                memberSince.innerText = "Member since " + displayJoined;
                memberSince.classList.remove('d-none');
            }
        }

        if (data.phone) {
            const displayPhone = document.getElementById('display-phone');
            if (displayPhone) displayPhone.innerText = data.phone;
        }

        if (data.address) {
            const displayAddress = document.getElementById('display-address');
            if (displayAddress) displayAddress.innerText = data.address;
        }
    });
</script>

@endSection