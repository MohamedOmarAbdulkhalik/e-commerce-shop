<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Barista Cafe')</title>

    <!-- CSS FILES -->                
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;600;700&display=swap" rel="stylesheet">
            
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vegas.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tooplate-barista.css') }}" rel="stylesheet">

    @yield('styles')
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg">                
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('images/coffee-beans.png') }}" class="navbar-brand-image img-fluid" alt="Barista Cafe Template">
                Barista
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-lg-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/about-us') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/products') }}">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/cart') }}">Cart</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}">Contact</a></li>
                </ul>

                <div class="ms-lg-3">
                    <a class="btn custom-btn custom-border-btn" href="#">
                        Reservation <i class="bi-arrow-up-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- محتوى الصفحة --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="site-footer">
        <div class="container">
            <div class="row">

                <div class="col-lg-4 col-12 me-auto">
                    <em class="text-white d-block mb-4">Where to find us?</em>
                    <strong class="text-white">
                        <i class="bi-geo-alt me-2"></i>
                        Bandra West, Mumbai, Maharashtra 400050, India
                    </strong>
                    <ul class="social-icon mt-4">
                        <li class="social-icon-item"><a href="#" class="social-icon-link bi-facebook"></a></li>
                        <li class="social-icon-item"><a href="https://x.com/minthu" target="_new" class="social-icon-link bi-twitter"></a></li>
                        <li class="social-icon-item"><a href="#" class="social-icon-link bi-whatsapp"></a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-12 mt-4 mb-3 mt-lg-0 mb-lg-0">
                    <em class="text-white d-block mb-4">Contact</em>
                    <p class="d-flex mb-1"><strong class="me-2">Phone:</strong>
                        <a href="tel:305-240-9671" class="site-footer-link">(65) 305 2409 671</a>
                    </p>
                    <p class="d-flex"><strong class="me-2">Email:</strong>
                        <a href="mailto:hello@barista.co" class="site-footer-link">hello@barista.co</a>
                    </p>
                </div>

                <div class="col-lg-5 col-12">
                    <em class="text-white d-block mb-4">Opening Hours.</em>
                    <ul class="opening-hours-list">
                        <li class="d-flex">Monday - Friday <span class="underline"></span> <strong>9:00 - 18:00</strong></li>
                        <li class="d-flex">Saturday <span class="underline"></span> <strong>11:00 - 16:30</strong></li>
                        <li class="d-flex">Sunday <span class="underline"></span> <strong>Closed</strong></li>
                    </ul>
                </div>

                <div class="col-lg-8 col-12 mt-4">
                    <p class="copyright-text mb-0">
                        Copyright © Barista Cafe 2048 -
                        Design: <a rel="sponsored" href="https://www.tooplate.com" target="_blank">Tooplate</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JAVASCRIPT FILES -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.sticky.js') }}"></script>
    <script src="{{ asset('js/click-scroll.js') }}"></script>
    <script src="{{ asset('js/vegas.min.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>

</body>
</html>
