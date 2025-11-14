<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Petrol Pump</title>

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            overflow: hidden;
            background: #000;
        }

        .hero {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100vh;
            overflow: hidden;
        }

        .hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: url('https://cdn-icons-png.flaticon.com/512/9154/9154309.png') no-repeat center center/cover;
            filter: brightness(0.4);
            z-index: 0;
        }

        .hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #005881, #3949ab);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            flex: 1;
            padding: 80px;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            animation: fadeInLeft 1.2s ease-in-out;
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .hero-content .logo {
            width: 100px;
            margin-bottom: 25px;
            animation: floatLogo 3s ease-in-out infinite;
        }

        @keyframes floatLogo {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .hero-content h1 {
            font-size: 3rem;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 15px;
            text-transform: uppercase;
            color: #d0e7ff;
            text-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
        }

        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 40px;
            max-width: 500px;
            color: #d0e7ff;
        }

        /* Tabs Styling */
        .nav-tabs .nav-link {
            color: #d0e7ff;
            font-weight: 600;
            border: none;
            background: transparent;
        }

        .nav-tabs .nav-link.active {
            color: #00c3ff;
            border-bottom: 3px solid #00c3ff;
        }

        /* Input fields */
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            height: 48px;
        }

        .form-control::placeholder {
            color: #ccc;
        }

        .btn-custom {
            border: none;
            padding: 12px 40px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(90deg, #00b4db, #0083b0);
            transition: 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 180, 219, 0.4);
        }

        .btn-custom:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 180, 219, 0.6);
        }

        .hero-illustration {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2;
        }

        .hero-illustration img {
            width: 40%;
            filter: drop-shadow(0 0 30px rgba(0, 200, 255, 0.5));
            animation: floatUpDown 4s ease-in-out infinite;
        }

        @keyframes floatUpDown {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .footer {
            position: absolute;
            bottom: 15px;
            width: 100%;
            text-align: center;
            color: #ccc;
            font-size: 0.9rem;
            z-index: 3;
        }

        .footer a {
            color: #00c3ff;
            text-decoration: none;
            font-weight: 600;
        }

        @media (max-width: 992px) {
            .hero {
                flex-direction: column;
                text-align: center;
            }

            .hero-content {
                padding: 40px 20px;
            }

            .hero-content h1 {
                font-size: 2rem;
            }

            .hero-illustration img {
                width: 60%;
            }
        }
    </style>
</head>

<body>
    <section class="hero">
        <div class="hero-content">


            <p>
                <a href="{{ url('/') }}" class="btn btn-sm mb-3"
                    style="display:inline-flex;align-items:center;gap:.5rem;padding:.4rem .95rem;border-radius:999px;
                          background:linear-gradient(90deg,#00b4db,#0083b0);color:#fff;border:none;
                          box-shadow:0 6px 18px rgba(0,180,219,0.35);font-weight:700;text-decoration:none;
                          transition:transform .18s ease,box-shadow .18s ease,opacity .18s ease;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" style="color:rgba(255,255,255,0.95)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Home
                </a>
            </p>

            <h1>Welcome to Petrol Pump</h1>
            <p>Streamline your fuel station operations with a secure multi-tenant platform.</p>

            <!-- LOGIN TABS -->
            <ul class="nav nav-tabs mb-4" id="loginTabs" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab"
                        data-bs-target="#superadmin">Super Admin</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab"
                        data-bs-target="#owner">Owner</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab"
                        data-bs-target="#tenant">Tenant</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab"
                        data-bs-target="#employee">Employee</button></li>
            </ul>

            <!-- TAB CONTENT -->
            <div class="tab-content">

                <!-- SUPER ADMIN -->
                <div class="tab-pane fade show active" id="superadmin">
                    <form method="POST" action="/super-admin/login">
                        @csrf
                        <input type="email" class="form-control mb-3" name="email" placeholder="Super Admin Email"
                            required>
                        <input type="password" class="form-control mb-3" name="password" placeholder="Password"
                            required>
                        <button class="btn btn-custom w-100">Login as Super Admin</button>
                    </form>
                </div>

                <!-- OWNER -->
                <div class="tab-pane fade" id="owner">
                    <form method="POST" action="/owner/login">
                        @csrf
                        <input type="email" class="form-control mb-3" name="email" placeholder="Owner Email"
                            required>
                        <input type="password" class="form-control mb-3" name="password" placeholder="Password"
                            required>
                        <button class="btn btn-custom w-100">Login as Owner</button>
                    </form>
                </div>

                <!-- TENANT -->
                <div class="tab-pane fade" id="tenant">
                    <form method="POST" action="/tenant/login">
                        @csrf
                        <input type="email" class="form-control mb-3" name="email" placeholder="Tenant Email"
                            required>
                        <input type="password" class="form-control mb-3" name="password" placeholder="Password"
                            required>
                        <button class="btn btn-custom w-100">Login as Tenant</button>
                    </form>
                </div>

                <!-- EMPLOYEE -->
                <div class="tab-pane fade" id="employee">
                    <form method="POST" action="/employee/login">
                        @csrf
                        <input type="text" class="form-control mb-3" name="employee_id" placeholder="Employee ID"
                            required>
                        <input type="password" class="form-control mb-3" name="password" placeholder="Password"
                            required>
                        <button class="btn btn-custom w-100">Login as Employee</button>
                    </form>
                </div>

            </div>
        </div>

        <div class="hero-illustration">
            <img src="https://cdn-icons-png.flaticon.com/512/9154/9154309.png">
        </div>

        <div class="footer">
            Designed & Developed by <a href="http://ins.com/" target="_blank">INS</a>
        </div>
    </section>

    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
