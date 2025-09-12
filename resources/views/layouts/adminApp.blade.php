<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100vh;
            background: #343a40;
            color: #fff;
            padding-top: 1rem;
        }
        .sidebar a {
            color: #adb5bd;
            display: block;
            padding: 10px 15px;
            text-decoration: none;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #495057;
            color: #fff;
            border-radius: 5px;
        }
        .content {
            padding: 20px;
        }
        .navbar {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
        }
    </style>

    @yield('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 sidebar d-md-block">
                <h4 class="px-3">لوحة التحكم</h4>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house"></i> الرئيسية
                </a>
                <a href="{{ route('profile.edit') }}">
                    <i class="bi bi-person"></i> الملف الشخصي
                </a>
                <a href="#">
                    <i class="bi bi-box-seam"></i> المنتجات
                </a>
                <a href="#">
                    <i class="bi bi-gear"></i> الإعدادات
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-box-arrow-right"></i> تسجيل الخروج
                    </button>
                </form>
            </nav>

            <!-- Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <nav class="navbar navbar-light">
                    <span class="navbar-text">
                        مرحباً {{ Auth::user()->name }}
                    </span>
                </nav>

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    @yield('scripts')
</body>
</html>
