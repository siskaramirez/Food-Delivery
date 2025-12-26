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
        border: 5px solid white;
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

    .btn-cancel {
        background-color: white;
        color: #333;
        border: none;
        transition: all 0.3s;
    }

    .btn-cancel:hover {
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
            <p class="text-white small mb-4">Member since {{ $user['joined'] }}</p>

            <a href="{{ route('profile.page') }}" class="btn btn-cancel rounded-pill px-5 w-100 fw-bold mt-auto mb-1 text-decoration-none text-center">Cancel</a>
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
                        <input type="email" name="email" id="input-email" class="edit-input" value="{{ $user['email'] }}" required>
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="info-label">Phone Number</label>
                        <input type="text" name="phone" id="input-phone" class="edit-input" value="{{ $user['phone'] }}" required>
                        @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="info-label">Address</label>
                        <select name="address" id="input-address" class="edit-input mb-2" required>
                            <option value="" disabled>Select your City</option>
                            <option value="Caloocan City, Metro Manila" {{ $user['address'] == 'Caloocan City, Metro Manila' ? 'selected' : '' }}>Caloocan City, Metro Manila</option>
                            <option value="Manila City, Metro Manila" {{ $user['address'] == 'Manila City, Metro Manila' ? 'selected' : '' }}>Manila City, Metro Manila</option>
                            <option value="Marikina City, Metro Manila" {{ $user['address'] == 'Marikina City, Metro Manila' ? 'selected' : '' }}>Marikina City, Metro Manila</option>
                            <option value="Pasay City, Metro Manila" {{ $user['address'] == 'Pasay City, Metro Manila' ? 'selected' : '' }}>Pasay City, Metro Manila</option>
                            <option value="Quezon City, Metro Manila" {{ $user['address'] == 'Quezon City, Metro Manila' ? 'selected' : '' }}>Quezon City, Metro Manila</option>
                            <option value="Taguig City, Metro Manila" {{ $user['address'] == 'Taguig City, Metro Manila' ? 'selected' : '' }}>Taguig City, Metro Manila</option>
                            <option value="Valenzuela City, Metro Manila" {{ $user['address'] == 'Valenzuela City, Metro Manila' ? 'selected' : '' }}>Valenzuela City, Metro Manila</option>
                        </select>
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
        const email = document.getElementById('input-email').value;
        const phone = document.getElementById('input-phone').value;
        const addressSelect = document.getElementById('input-address');
        const selectedAddress = addressSelect.options[addressSelect.selectedIndex].value;

        localStorage.setItem('user_name', name);
        localStorage.setItem('user_email', email);
        localStorage.setItem('user_phone', phone);
        localStorage.setItem('user_address', selectedAddress);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const fields = ['name', 'email', 'phone', 'address'];
        fields.forEach(field => {
            const saved = localStorage.getItem(`user_${field}`);
            if (saved) {
                document.getElementById(`input-${field}`).value = saved;
            }
        });
    });
</script>

@endSection