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

    .custom-dialog {
        border: none;
        border-radius: 20px;
        padding: 30px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        /*background: #fff;*/
    }

    .custom-dialog::backdrop {
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(1px);
    }

    .dialog-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 20px;
    }

    .btn-confirm,
    .btn-cancel {
        flex: 1;
        height: 45px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        border: none;
        display: flex;
        transition: all 0.2s ease;
        align-items: center;
        justify-content: center;
        text-transform: uppercase;
        cursor: pointer;
    }

    .btn-confirm {
        background: #ff6b6b;
        color: white;
        /*transition: 0.2s;*/
    }

    .btn-confirm:hover {
        background: #ee5253;
    }

    .btn-cancel {
        background: #f0f1f1ff;
        color: #6c757d;
        /*transition: 0.2s;*/
    }

    .btn-cancel:hover {
        background: #e2e6ea;
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

            <a href="{{ route('profile.edit') }}" class="btn btn-edit-profile rounded-pill px-5 w-100 fw-bold mt-auto mb-1">Edit Profile</a>
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
                <div class="col-md-6">
                    <label class="info-label">Age</label>
                    <p id="display-age" class="fw-bold fs-5 border-bottom pb-2 text-dark">{{ $user['age'] . ' years old' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="info-label">Gender</label>
                    <p id="display-gender" class="fw-bold fs-5 border-bottom pb-2 text-dark">{{ $user['gender'] }}</p>
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="button" onclick="openDeleteModal('{{ Auth::user()->userid }}')" class="btn btn-save-profile rounded-pill px-4 py-2 fw-bold shadow-sm">Delete Account</button>
            </div>

            <!-- WARNING BUTTON
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
            </div> -->
        </div>
    </div>
</div>

<form id="delete-account-form"
    action="{{ route('profile.delete', Auth::user()->userid) }}"
    method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<dialog id="deleteUserModal" class="custom-dialog">
    <div class="dialog-content">
        <h3 class="fw-bold mb-3">Confirm Deletion</h3>
        <p class="text-muted">Are you sure you want to remove your account?<br>This will permanently remove all the records.</p>
        <div class="dialog-actions">
            <button type="button" onclick="closeDeleteModal()" class="btn-cancel">Cancel</button>
            <button type="button" id="btnConfirmDeleteUser" class="btn-confirm">Delete</button>
        </div>
    </div>
</dialog>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isAuth = localStorage.getItem('eatsway_authenticated');

        if (isAuth !== 'true') {
            window.location.href = "{{ route('signin.page') }}";
            return;
        }

        localStorage.setItem('user_name', "{{ $user['name'] }}");
        localStorage.setItem('user_phone', "{{ $user['phone'] }}");
        localStorage.setItem('user_address', "{{ $user['address'] }}");

        const cachedData = {
            name: localStorage.getItem('user_name'),
            email: localStorage.getItem('user_email'),
            phone: localStorage.getItem('user_phone'),
            address: localStorage.getItem('user_address'),
            age: localStorage.getItem('user_age'),
            gender: localStorage.getItem('user_gender'),
            joined: localStorage.getItem('user_joined')
        };

        if (cachedData.name) {
            document.getElementById('sidebar-name').innerText = cachedData.name;
            document.getElementById('display-name').innerText = cachedData.name;
        }

        if (cachedData.email) document.getElementById('display-email').innerText = cachedData.email;
        if (cachedData.phone) document.getElementById('display-phone').innerText = cachedData.phone;
        //if (cachedData.address) document.getElementById('display-address').innerText = cachedData.address;
        if (cachedData.age) document.getElementById('display-age').innerText = cachedData.age;
        //if (cachedData.gender) document.getElementById('display-gender').innerText = cachedData.gender;
        //if (cachedData.gender) {
        //const capitalized = cachedData.gender.charAt(0).toUpperCase() + cachedData.gender.slice(1);
        //document.getElementById('display-gender').innerText = capitalized;
        //}

        if (cachedData.joined) {
            const memberSince = document.getElementById('member-since');
            memberSince.innerText = "Member since " + cachedData.joined;
            memberSince.classList.remove('d-none');
        }

        const deleteModal = document.getElementById('deleteUserModal');
        const btnConfirmDelete = document.getElementById('btnConfirmDeleteUser');
        let userToDelete = null;

        window.openDeleteModal = function(userId) {
            userToDelete = userId;
            if (deleteModal) {
                deleteModal.showModal();
            }
        };

        window.closeDeleteModal = function() {
            if (deleteModal) {
                deleteModal.close();
            }
            userToDelete = null;
        };

        if (btnConfirmDelete) {
            btnConfirmDelete.addEventListener('click', function() {
                const form = document.getElementById('delete-account-form');

                if (form) {
                    btnConfirmDelete.disabled = true;
                    btnConfirmDelete.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';

                    localStorage.clear();

                    form.submit();
                } else {
                    console.error("Error: Delete form not found in the DOM.");
                }
            });
        }

        deleteModal.addEventListener('click', (e) => {
            const dialogDimensions = deleteModal.getBoundingClientRect();
            if (
                e.clientX < dialogDimensions.left ||
                e.clientX > dialogDimensions.right ||
                e.clientY < dialogDimensions.top ||
                e.clientY > dialogDimensions.bottom
            ) {
                closeDeleteModal();
            }
        });
    });
</script>

@endSection