@extends('layout.main')
@section('content')

<nav class="navbar navbar-expand-lg bg-body-tertiary shadow-sm">
    <div class="container-fluid">

        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="#">AppName</a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <!-- LEFT NAV BUTTONS -->
            <ul class="navbar-nav mb-2 mb-lg-0 me-3">
                <li class="nav-item">
                    <a class="nav-link fw-semibold active" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="#">Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="#">Drivers</a>
                </li>
            </ul>

            <!-- SEARCH BAR -->
            <form class="d-flex me-auto" role="search">
                <input
                    class="form-control me-2"
                    type="search"
                    placeholder="Search"
                    aria-label="Search" />
                <button class="btn btn-outline-primary" type="button">
                    Search
                </button>
            </form>

            <!-- LOGOUT BUTTON -->
            <button class="btn btn-outline-danger fw-semibold" type="button">
                Logout
            </button>

        </div>
    </div>
</nav>


<style>
    .content-placeholder {
        min-height: 100vh;
        padding: 40px 20px;
        background-color: #f8f9fa;
    }

    .footer-bar {
        transform: translateY(100%);
        transition: transform 0.3s ease-in-out;
        z-index: 1050;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        background-color: #f8f9fa !important;
        border-top: 1px solid #e9ecef;
    }

    .footer-bar.visible {
        transform: translateY(0);
    }
</style>

<div class="container content-placeholder shadow-sm rounded-lg mt-4">
    <h1 class="display-5 fw-bold text-center mb-4">User Management</h1>
    <p class="lead text-center mb-5">Click a user to view details and order history.</p>

    <div class="accordion" id="userAccordion">

        <!-- USER ITEM -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#user1"
                    aria-expanded="false"
                    aria-controls="user1">
                    <strong>User:</strong>&nbsp;John Doe &nbsp;|&nbsp; <strong>UserID:</strong> 1001
                </button>
            </h2>

            <div id="user1" class="accordion-collapse collapse" data-bs-parent="#userAccordion">
                <div class="accordion-body">

                    <div class="row">

                        <!-- USER COLUMN -->
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3">User Details</h5>
                            <table class="table table-sm table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Username</th>
                                        <td>JohnDoe</td>
                                    </tr>
                                    <tr>
                                        <th>UserID</th>
                                        <td>1001</td>
                                    </tr>
                                    <tr>
                                        <th>Password</th>
                                        <td>••••••••</td>
                                    </tr>
                                    <tr>
                                        <th>Contact No</th>
                                        <td>09123456789</td>
                                    </tr>
                                    <tr>
                                        <th>Age</th>
                                        <td>21</td>
                                    </tr>
                                    <tr>
                                        <th>Gender</th>
                                        <td>Male</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>Manila, PH</td>
                                    </tr>
                                    <tr>
                                        <th>Date Registered</th>
                                        <td>2024-01-15</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- ORDER HISTORY COLUMN -->
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3">Order History</h5>
                            <table class="table table-sm table-striped table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>OrderID</th>
                                        <th>Order Date</th>
                                        <th>Payment Status</th>
                                        <th>Last Modified</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>ORD-001</td>
                                        <td>2024-03-01</td>
                                        <td><span class="badge bg-success">Paid</span></td>
                                        <td>2024-03-02</td>
                                    </tr>
                                    <tr>
                                        <td>ORD-002</td>
                                        <td>2024-04-10</td>
                                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                                        <td>2024-04-11</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>


    </div>
</div>



<script>
    const footerBar = document.getElementById('stickyFooter');
    const scrollThreshold = 25;

    window.addEventListener('scroll', () => {
        if (window.scrollY > scrollThreshold) {
            footerBar.classList.add('visible');
        } else {
            footerBar.classList.remove('visible');
        }
    });

    window.onload = function() {
        if (window.scrollY > scrollThreshold) {
            footerBar.classList.add('visible');
        }
    };
</script>

@endSection