@extends('layout.main')
@section('content')

<style>
    .about-header {
        padding: 80px 0 60px;
        text-align: center;
    }

    .accent-text {
        color: #ff6b6b;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 3px;
        font-size: 1.1rem;
        display: block;
        margin-bottom: 10px;
    }

    .member-card {
        background: white;
        border-radius: 25px;
        padding: 30px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.02);
        height: 100%;
        text-align: center;
    }

    .member-img-container {
        width: 150px;
        height: 150px;
        margin: 0 auto 20px;
        border-radius: 50%;
        padding: 8px;
        border: 2px dashed #ff6b6b;
    }

    .member-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        background: #f8f9fa;
    }

    .member-name {
        font-weight: 800;
        color: #2d3436;
        margin-bottom: 5px;
        font-size: 1.25rem;
    }

    .member-role {
        color: #ff6b6b;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        margin-bottom: 15px;
        display: block;
    }

    .fb-btn {
        background: #e7f3ff;
        color: #1877f2;
        padding: 8px 20px;
        border-radius: 13px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .fb-btn:hover {
        background: #1877f2;
        color: white;
        transform: scale(1.05);
    }

    .fb-btn i {
        font-size: 1.1rem;
    }
</style>

<div class="container">
    <div class="about-header">
        <span class="accent-text">Meet the Team</span>
        <h1 class="display-4 fw-bold">GROUP 13</h1>
    </div>

    <div class="row g-4 justify-content-center">
        @php
        $team = [
        ['id' => 'MEM1', 'name' => 'Luis Gabriel F. Dela Cruz', 'role' => 'Project Manager', 'fb_link' => 'https://www.facebook.com/luisgabriel.delacruz.9003'],
        ['id' => 'MEM2', 'name' => 'Francheska E. Ramirez', 'role' => 'Frontend Dev', 'fb_link' => 'https://www.facebook.com/siskarmrz/'],
        ['id' => 'MEM3', 'name' => 'Juan Miguel G. Torres', 'role' => 'UI/UX Designer', 'fb_link' => 'https://www.facebook.com/the.jeyemz'],
        ['id' => 'MEM4', 'name' => 'Josef Karol A. Velayo', 'role' => 'Backend Dev', 'fb_link' => 'https://www.facebook.com/josef.velayo'],
        ];
        @endphp

        @foreach($team as $member)
        <div class="col-md-6 col-lg-5">
            <div class="member-card">
                <div class="member-img-container">
                    <img src="{{ asset('images/' . $member['id'] . '.jpg') }}" alt="{{ $member['name'] }}" class="member-img">
                </div>

                <h4 class="member-name">{{ $member['name'] }}</h4>
                <span class="member-role">{{ $member['role'] }}</span>

                <div class="mt-3">
                    <a href="{{ $member['fb_link'] }}" target="_blank" class="fb-btn">
                        <i class="fab fa-facebook"></i> View Profile
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endSection