<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'IwakMart') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #F0F8FF;
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, #1565C0, #0D47A1);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
        }

        .nav-link {
            font-weight: 500;
        }

        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
            background: linear-gradient(135deg, #1565C0, #0D47A1);
            color: white;
        }

        .btn-primary {
            background: #1565C0;
            border-color: #1565C0;
        }

        .btn-primary:hover {
            background: #0D47A1;
            border-color: #0D47A1;
        }
    </style>

    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">IwakMart</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('fishmarket') ? 'active' : '' }}" href="{{ route('fishmarket') }}">Pasar Ikan</a>
                    </li>
                    @auth
                        @if(auth()->user()->role && auth()->user()->role !== 'penjual_biasa')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('locations*') ? 'active' : '' }}" href="{{ route('locations') }}">Lokasi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('appointments*') ? 'active' : '' }}" href="{{ route('appointments') }}">Janji Temu</a>
                        </li>
                        @endif
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('locations*') ? 'active' : '' }}" href="{{ route('locations') }}">Lokasi</a>
                        </li>
                    @endauth
                </ul>

                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Daftar</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link position-relative {{ request()->routeIs('cart') ? 'active' : '' }}" href="{{ route('cart') }}">
                                <i class="fas fa-shopping-cart"></i>
                                <span id="cartCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    0
                                </span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                                @if(auth()->user()->role && auth()->user()->role !== 'penjual_biasa')
                                <li><a class="dropdown-item" href="{{ route('appointments') }}"><i class="fas fa-calendar-check me-2"></i>Janji Temu</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Keluar</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>IwakMart</h5>
                    <p class="text-muted">Platform jual beli ikan segar terpercaya</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0 text-muted">&copy; {{ date('Y') }} IwakMart. Hak Cipta Dilindungi</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Update cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });

        // Function to update cart count
        function updateCartCount() {
            fetch('/api/cart')
                .then(response => response.json())
                .then(data => {
                    if (data.cart && data.cart.items) {
                        const cartCountElement = document.getElementById('cartCount');
                        if (cartCountElement) {
                            const itemCount = data.cart.items.length;
                            cartCountElement.textContent = itemCount;

                            if (itemCount === 0) {
                                cartCountElement.classList.add('d-none');
                            } else {
                                cartCountElement.classList.remove('d-none');
                            }
                        }
                    }
                })
                .catch(error => console.error('Error fetching cart:', error));
        }
    </script>

    @stack('scripts')
</body>
</html>
