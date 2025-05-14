<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Halaman Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #d7f5f5, #e1f3ff);
            min-height: 100vh;
        }

        .navbar-brand span:first-child {
            color: #3399ff;
            font-weight: bold;
        }

        .navbar-brand span:last-child {
            color: #555;
            font-weight: bold;
        }

        .product-card {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 10px;
            margin: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: 0.3s;
        }

        .product-card:hover {
            transform: scale(1.02);
            transition: 0.3s;
        }
    </style>
</head>

<body>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Header --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm px-4">
        <a class="navbar-brand" href="#">
            <span>FOODMART</span> <span>MR BANGKOK</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <form class="d-flex ms-auto my-2 my-lg-0 me-3">
                <select class="form-select me-2">
                    <option>All Categories</option>
                    <option>Makanan</option>
                    <option>Minuman</option>
                    <option>Snack</option>
                </select>
                <input class="form-control me-2" type="search" placeholder="Search for more than 20,000 products">
            </form>
            {{-- Tombol Keranjang --}}
            @php
            $cart = session('cart', []);
            $totalQuantity = collect($cart)->sum('quantity');
            @endphp
            <!-- Link ke halaman Keranjang -->
            <a href="{{ route('cart.view') }}" class="btn position-relative me-3">
                🛒
                @if($totalQuantity > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $totalQuantity }}
                </span>
                @endif
            </a>
            <!-- Icon Profile dan Dropdown -->
            <div class="dropdown">
                <a class="btn dropdown-toggle d-flex align-items-center" href="#" role="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://img.icons8.com/ios-glyphs/30/000000/user--v1.png" alt="Profile" class="rounded-circle" width="30" height="30" />
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Produk --}}
    <div class="container py-4">
        <h2 class="mb-4">Produk Terbaru</h2>
        <div class="row">
            @foreach($makanan as $m)
            <div class="col-md-3 mb-4">
                <div class="card product-card h-100 shadow-sm">
                    <img src="{{ asset('storage/' . $m->gambar) }}" class="card-img-top" alt="{{ $m->nama_makanan }}" style="height: 150px; object-fit: contain;">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $m->nama_makanan }}</h5>
                        {{-- Rating --}}
                        <p class="mb-1 text-muted" style="font-size: 0.9rem;">
                            <span class="text-warning">★</span> <strong>{{ number_format($m->rating ?? 5.00, 2) }}</strong>
                        </p>
                        <p class="card-text fw-bold text-primary">Rp {{ number_format($m->harga_makanan, 0, ',', '.') }}</p>
                        <div class="input-group justify-content-center mb-2" style="width: 140px; margin: auto;">
                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateQuantity({{ $m->id }}, 'minus')">−</button>
                            <input type="number" id="quantity-{{ $m->id }}" class="form-control form-control-sm text-center" value="1" min="1" readonly>
                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateQuantity({{ $m->id }}, 'plus')">+</button>
                        </div>
                        <button type="button" class="btn btn-primary w-100" onclick="addToCart({{ $m->id }})">Tambah ke Keranjang</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Set up CSRF token for all AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function updateQuantity(productId, action) {
            const input = document.getElementById(`quantity-${productId}`);
            let value = parseInt(input.value);

            if (action === 'plus') {
                value++;
            } else if (action === 'minus' && value > 1) {
                value--;
            }

            input.value = value;
        }

        function addToCart(productId) {
            const quantity = document.getElementById(`quantity-${productId}`).value;
            const data = {
                makanan_id: productId,
                quantity: parseInt(quantity)
            };

            fetch('/konsumen/add-to-cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Update cart badge
                        const cartBadge = document.querySelector('.badge.rounded-pill');
                        if (cartBadge) {
                            cartBadge.textContent = data.jmlbarangdibeli;
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else {
                        throw new Error(data.message || 'Gagal menambahkan produk ke keranjang!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.message || 'Terjadi kesalahan saat menambahkan ke keranjang!'
                    });
                });
        }
    </script>
</body>

</html>