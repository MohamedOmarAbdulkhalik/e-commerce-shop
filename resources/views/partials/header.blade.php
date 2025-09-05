
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
                    <li class="nav-item"><a class="nav-link" href="{{ url('/add-product') }}">Add Product</a></li>

                </ul>

                <div class="ms-lg-3">
                    <a class="btn custom-btn custom-border-btn" href="#">
                        Reservation <i class="bi-arrow-up-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

