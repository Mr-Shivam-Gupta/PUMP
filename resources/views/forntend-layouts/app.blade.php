<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Petrol Pump ERP' }}</title>

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">

    <style>
        body {
            background: linear-gradient(135deg, #009688, #3949ab);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }

        /* NAVBAR */
        .navbar {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-brand img {
            height: 42px;
            filter: drop-shadow(0 0 6px rgba(0, 255, 255, 0.4));
        }

        .navbar-nav .nav-link {
            color: #e9f4ff !important;
            font-weight: 500;
            margin-right: 25px;
            letter-spacing: .5px;
        }

        .navbar-nav .nav-link:hover {
            color: #00ffe5 !important;
        }

        .btn-login {
            background: linear-gradient(90deg, #00c6ff, #0072ff);
            color: #fff;
            padding: 8px 22px;
            border-radius: 30px;
            font-weight: 600;
            border: none;
        }

        .btn-login:hover {
            box-shadow: 0 4px 20px rgba(0, 255, 255, 0.4);
            transform: translateY(-2px);
        }

        /* WHITE CARD SECTIONS */
        .white-section {
            background: #fff;
            border-radius: 18px;
            padding: 40px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
    </style>

    @yield('styles')
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="https://cdn-icons-png.flaticon.com/512/9154/9154309.png">
            </a>

            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse"
                data-bs-target="#navMenu">
                ☰
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#plans">Plans</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <li class="nav-item mt-2 mt-lg-0">
                        <a href="/login" class="btn-login">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div style="padding-top: 90px;">
        @yield('content')
    </div>

    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @yield('scripts')
</body>

</html>
