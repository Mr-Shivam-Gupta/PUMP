<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Petrol Pump</title>

    <!-- Bootstrap 5 -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            overflow: hidden;
            background: #000;
        }

        /* Background with dark gradient overlay */
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
            background: url('https://images.unsplash.com/photo-1581091215367-59ab6c422e0e?auto=format&fit=crop&w=1740&q=80') no-repeat center center/cover;
            filter: brightness(0.4);
            z-index: 0;
        }

        .hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, rgba(0, 0, 0, 0.8), rgba(0, 90, 180, 0.7));
            /* background: linear-gradient(-45deg, var(--vz-primary) 50%, var(--vz-success)); */
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
            color: #d0e7ff;
            max-width: 500px;
        }

        .btn-custom {
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(90deg, #00b4db, #0083b0);
            margin-right: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 180, 219, 0.4);
        }

        .btn-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 180, 219, 0.6);
        }

        /* Right-side illustration */
        .hero-illustration {
            flex: 1;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2;
            animation: fadeIn 2s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .hero-illustration img {
            width: 70%;
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

        .footer a:hover {
            text-decoration: underline;
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .hero {
                flex-direction: column;
                text-align: center;
                justify-content: center;
                padding: 20px;
            }

            .hero-content {
                padding: 40px 20px;
                align-items: center;
            }

            .hero-content h1 {
                font-size: 2rem;
            }

            .hero-content p {
                font-size: 1rem;
            }

            .hero-illustration img {
                width: 60%;
            }

            .btn-custom {
                margin: 10px 5px;
                width: 80%;
            }
        }

        @media (max-width: 576px) {
            .hero-content h1 {
                font-size: 1.8rem;
            }

            .hero-content p {
                font-size: 0.9rem;
            }

            .hero-content .logo {
                width: 80px;
            }
        }
    </style>
</head>

<body>
    <section class="hero">
        <div class="hero-content">
            <img src="https://cdn-icons-png.flaticon.com/512/9154/9154309.png" alt="Logo" class="logo">

            <h1>Welcome to Petrol Pump</h1>
            <p>Streamline your fuel station operations with a secure and efficient multi-tenant platform.</p>

            <div>
                <a href="/owner/login" class="btn btn-custom">Owner Login</a>
                <a href="/login" class="btn btn-custom">Tenant Login</a>
            </div>
        </div>

        <div class="hero-illustration">
            <img src="https://cdn-icons-png.flaticon.com/512/3589/3589058.png" alt="Petrol Illustration">
        </div>

        <div class="footer">
            Designed & Developed by <a href="http://ins.com/" target="_blank">INS</a>
        </div>
    </section>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
</body>

</html>
