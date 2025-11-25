<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kidicode LMS') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    <!-- New Fonts for Dashboards -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <!-- Dashboard Custom CSS -->
    <link href="{{ asset('css/admin-dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/instructor-dashboard.css') }}" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Quick Logout Script -->
    <script>
    function instantLogout() {
        if (confirm('Are you sure you want to logout?')) {
            document.getElementById('logout-form').submit();
        }
    }
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    🚀 Kidicode LMS
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('courses.index') }}">Courses</a>
                            </li>
                            
                            <!-- Admin Links - Sirf Admin Ke Liye -->
                            @if(auth()->user()->isAdmin())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    👑 Admin Panel
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">👥 Manage Users</a></li>
                                    <li><a class="dropdown-item" href="{{ route('courses.index') }}">📚 Manage Courses</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">📊 Analytics</a></li>
                                </ul>
                            </li>
                            @endif
                            
                            <!-- Instructor Links -->
                            @if(auth()->user()->isInstructor() || auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('courses.create') }}">Create Course</a>
                            </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <!-- Quick Logout Button (Visible on desktop) -->
                            <li class="nav-item d-none d-md-block">
                                <a class="nav-link text-danger" href="#" onclick="instantLogout()">
                                    🚪 Logout
                                </a>
                            </li>

                            <!-- User Dropdown -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    👤 {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('home') }}">
                                        🏠 My Dashboard
                                    </a>
                                    
                                    <!-- Admin Specific Links -->
                                    @if(auth()->user()->isAdmin())
                                    <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                        👑 Admin Panel
                                    </a>
                                    <hr class="dropdown-divider">
                                    @endif
                                    
                                    <a class="dropdown-item" href="{{ route('courses.index') }}">
                                        📚 All Courses
                                    </a>
                                    <a class="dropdown-item" href="{{ route('courses.my-courses') }}">
                                        🎯 My Courses
                                    </a>
                                    <hr class="dropdown-divider">
                                    
                                    <!-- Instant Logout in Dropdown -->
                                    <a class="dropdown-item text-danger" href="#" onclick="instantLogout()">
                                        🚪 <strong>Instant Logout</strong>
                                    </a>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Mobile Logout Button (Visible only on mobile) -->
        @auth
        <div class="d-md-none fixed-bottom bg-white border-top py-2">
            <div class="container">
                <div class="d-grid">
                    <a href="#" class="btn btn-danger btn-sm" onclick="instantLogout()">
                        🚪 Logout ({{ Auth::user()->name }})
                    </a>
                </div>
            </div>
        </div>
        @endauth

        <main class="py-4" style="@auth margin-bottom: 70px; @endauth">
            @if(session('success'))
                <div class="container">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ✅ {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="container">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ❌ Please fix the following errors:
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Hidden Logout Form -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</body>
</html>