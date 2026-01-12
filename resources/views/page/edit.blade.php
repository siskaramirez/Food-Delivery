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
        border: 2px solid white;
        transition: transform 0.3s ease;
    }

    .info-label {
        color: #ff6b6b;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .edit-input {
        border: none;
        border-bottom: 2px solid #ff6b6b;
        background: transparent;
        width: 100%;
        font-weight: bold;
        font-size: 1.1rem;
        outline: none;
        padding: 5px 0;
        color: #333;
        transition: border-color 0.3s;
    }

    .edit-input:focus {
        border-bottom-color: #2d3436;
    }

    select.edit-input.form-select {
        border-radius: 0px;
        padding: 5px 12px;
        background-color: transparent;
        cursor: pointer;
    }

    select.edit-input.form-select:focus {
        border-color: #2d3436;
        box-shadow: none;
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

    .btn-save-profile {
        background-color: #ff6b6b;
        color: white;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-save-profile:hover {
        background-color: #2d3436;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
    }

    select.edit-input {
        appearance: none;
        -webkit-appearance: none;
        cursor: pointer;
        padding-right: 25px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23ff6b6b' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: calc(100% - 5px) center;
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
            <p class="text-white mb-4">Member since {{ $user['joined'] }}</p>
            
            <a href="{{ route('profile.page') }}" class="btn btn-edit-profile rounded-pill px-5 w-100 fw-bold mt-auto mb-1">Cancel</a>
        </div>

        <div class="col-lg-8 p-5">
            <h3 class="fw-bold mb-4 text-dark">Edit Account Information</h3>

            <form action="{{ route('profile.update') }}" method="POST" onsubmit="syncToLocalStorage()">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="info-label">Full Name</label>
                        <input type="text" name="name" id="input-name" class="edit-input" value="{{ $user['name'] }}" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="info-label">Email Address</label>
                        <input type="email" name="email" id="input-email" class="edit-input" value="{{ $user['email'] }}" readonly>
                        <small class="text-muted">Email cannot be changed.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="info-label">Phone Number</label>
                        <input type="text" name="phone" id="input-phone" class="edit-input" value="{{ $user['phone'] }}" required>
                        @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="info-label">Address</label>
                        <input type="text" name="address" id="input-address" class="edit-input" value="{{ $user['address'] }}" required>
                        @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-save-profile rounded-pill px-4 py-2 fw-bold shadow-sm">Done & Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function syncToLocalStorage() {
        const name = document.getElementById('input-name').value;
        const phone = document.getElementById('input-phone').value;
        const address = document.getElementById('input-address').value;

        localStorage.setItem('user_name', name);
        localStorage.setItem('user_phone', phone);
        localStorage.setItem('user_address', address);

        const sidebarName = document.getElementById('sidebar-name');
        if (sidebarName) sidebarName.innerText = name;
    }

    document.addEventListener('DOMContentLoaded', () => {
        const fields = ['name', 'email', 'phone', 'address'];
        
        fields.forEach(field => {
            const savedValue = localStorage.getItem(`user_${field}`);
            const inputElement = document.getElementById(`input-${field}`);

            if (savedValue && inputElement) {
                inputElement.value = savedValue;
            }
        });
    });
</script>

@endSection