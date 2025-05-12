<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Halaman Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .product-card:hover {
            transform: scale(1.02);
            transition: 0.3s;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm px-4">
        <a class="navbar-brand" href="#">
            <span>FOODMART</span> <span>MR BANGKOK</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <form class="d-flex ms-auto my-2 my-lg-0">
                <select class="form-select me-2">
                    <option>All Categories</option>
                    <option>Makanan</option>
                    <option>Minuman</option>
                    <option>Snack</option>
                </select>
                <input class="form-control me-2" type="search" placeholder="Search for more than 20,000 products">
            </form>
        </div>
    </nav>

    {{-- Produk --}}
    <div class="container py-4">
        <h2 class="mb-4">Produk Terbaru</h2>
        <div class="row">
           @foreach($makanans as $makanan)
<div class="col-md-3 mb-4">
    <div class="card product-card h-100 shadow-sm">
        <img src="{{ asset('storage/' . $makanan->gambar) }}" class="card-img-top" alt="{{ $makanan->nama_makanan }}" style="height: 150px; object-fit: contain;">
        <div class="card-body text-center">
            <h5 class="card-title">{{ $makanan->nama_makanan }}</h5>
            
            {{-- Rating saja, tanpa UNIT --}}
            <p class="mb-1 text-muted" style="font-size: 0.9rem;">
                <span class="text-warning">★</span> <strong>{{ number_format($makanan->rating ?? 5.00, 2) }}</strong>
            </p>

            <p class="card-text fw-bold text-primary">Rp {{ number_format($makanan->harga_makanan, 0, ',', '.') }}</p>

            <form action="{{ route('konsumen.addToCart') }}" method="POST" class="mt-2">
                @csrf
                <input type="hidden" name="makanan_id" value="{{ $makanan->id }}">
                <div class="input-group justify-content-center mb-2" style="width: 140px; margin: auto;">
                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">−</button>
                    <input type="number" name="quantity" class="form-control form-control-sm text-center" value="1" min="1">
                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">+</button>
                </div>
                <button type="submit" class="btn btn-primary w-100">Tambah ke Keranjang</button>
            </form>
        </div>
    </div>
</div>
@endforeach

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
