<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- SweetAlert2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    {{-- Custom CSS --}}
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 220px;
            background-color: #343a40;
            color: #fff;
            padding-top: 20px;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .product-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .product-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 4px;
        }

        .product-title {
            font-size: 1.1rem;
            margin: 10px 0;
        }

        .product-price {
            font-size: 1.2rem;
            color: #28a745;
            font-weight: bold;
            margin: 10px 0;
        }

        .product-actions {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    {{-- Sidebar --}}
    <div class="sidebar">
        <h4 class="text-center">Konsumen</h4>
        <a href="/dashboard">Dashboard</a>
        <a href="/galeri">Galeri Makanan</a>
        <a href="/lihatkeranjang">Keranjang</a>
        <a href="/lihatriwayat">Riwayat Pesanan</a>
        <a href="/ubahpassword">Ubah Password</a>
        <a href="/logout">Logout</a>
    </div>

    {{-- Main Content --}}
    <div class="main-content">
        {{-- Navbar atas --}}
        <nav class="navbar navbar-light bg-light mb-3">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">@yield('title')</span>
            </div>
        </nav>

        {{-- Konten halaman --}}
        @yield('content')
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Custom Scripts --}}
    @stack('scripts')
</body>
</html>
