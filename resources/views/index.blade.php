@extends('frontend-layouts.app')

@section('content')
    <style>
        .section {
            padding: 80px 0;
        }

        .white-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.15);
        }
    </style>

    <!-- HOME -->
    <section id="home" class="section text-white text-center" style="min-height:70vh; display:flex; align-items:center;">
        <div class="container">
            <h1 class="display-4 fw-bold">Fuel Your Business with Smart Automation</h1>
            <p class="fs-5 mt-3">
                A complete multi-tenant Petrol Pump ERP System built for speed, accuracy & control.
            </p>
            <a href="#plans" class="btn-login mt-4">View Plans</a>
        </div>
    </section>

    <!-- ABOUT -->
    <section id="about" class="section">
        <div class="container white-card">
            <h2 class="fw-bold text-center mb-4">About Our Petrol Pump ERP</h2>

            <p class="fs-5">
                Our petrol pump ERP system is a complete automation suite designed for
                <b>multi-tenant fuel stations</b> with advanced reporting and management tools.
            </p>

            <h4 class="mt-4 mb-3">🔧 Key Features</h4>
            <ul class="fs-5">
                <li>Real-time Pump & Nozzle Monitoring</li>
                <li>Daily Sale & Shift Management</li>
                <li>Stock & Dip Readings</li>
                <li>Employee Attendance & Roles</li>
                <li>Finance, Ledger & Expense Tracking</li>
                <li>Multi-Tenant Login Architecture</li>
            </ul>
        </div>
    </section>

    <!-- PLANS -->
    <section id="plans" class="section">
        <div class="container white-card">
            <h2 class="fw-bold text-center mb-4">Our Plans</h2>

            <div class="row g-4">

                @foreach ($plans as $plan)
                    <div class="col-md-4">
                        <div class="white-card text-center">
                            <h4 class="fw-bold">{{ $plan->name }}</h4>
                            <p class="fs-5">₹{{ $plan->price }}/month</p>
                            <p>{{ $plan->description }}</p>

                            <a href="{{ url('/plan/select/' . $plan->id) }}" class="btn-login mt-3">
                                Select Plan
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>

    <!-- CONTACT -->
    <section id="contact" class="section">
        <div class="container white-card">
            <h2 class="fw-bold text-center mb-4">Contact Us</h2>

            <div class="row g-4">

                <div class="col-md-6">
                    <form method="POST">
                        @csrf

                        <div class="mb-3">
                            <label><b>Name</b></label>
                            <input type="text" name="name" class="form-control" required
                                placeholder="Enter your name">
                        </div>

                        <div class="mb-3">
                            <label><b>Email</b></label>
                            <input type="email" name="email" class="form-control" required placeholder="Enter email">
                        </div>

                        <div class="mb-3">
                            <label><b>Message</b></label>
                            <textarea name="message" rows="4" class="form-control" required></textarea>
                        </div>

                        <button class="btn-login w-100">Submit</button>
                    </form>
                </div>

                <div class="col-md-6 d-flex flex-column justify-content-center">
                    <h4 class="fw-bold">📞 24×7 Customer Support</h4>
                    <p class="mb-1">Phone: <b>+91 98765 43210</b></p>
                    <p class="mb-1">Email: <b>support@petrolpump.com</b></p>

                    <h5 class="mt-4">Office Address</h5>
                    <p>INS, India Petroleum Automation & Digital Solutions</p>
                </div>

            </div>
        </div>
    </section>

    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener("click", function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute("href")).scrollIntoView({
                    behavior: "smooth"
                });
            });
        });
    </script>
@endsection
