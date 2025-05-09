<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Halaman Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-card {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 10px;
            margin: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: 0.3s;
        }
        .product-card:hover {
            transform: scale(1.02);
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <nav class="navbar navbar-light bg-light px-4">
        <a class="navbar-brand fw-bold text-warning">FOODMART <span class="text-secondary">MR BANGKOK</span></a>
        <form class="d-flex">
            <select class="form-select me-2">
                <option>All Categories</option>
            </select>
            <input class="form-control me-2" type="search" placeholder="Search for more than 20,000 products">
        </form>
    </nav>

    <h2>Produk Terbaru</h2>
<div style="display: flex; gap: 20px;">
    @foreach($makanans as $makanan)
<div class="card">
    <img src="{{ asset('storage/' . $makanan->gambar) }}" alt="{{ $makanan->nama_makanan }}" width="150">
    <h4>{{ $makanan->nama_makanan }}</h4>
    <p>Rp {{ number_format($makanan->harga_makanan, 0, ',', '.') }}</p>

    <!-- Tambah input quantity -->
    <form action="{{ route('konsumen.addToCart') }}" method="POST">
        @csrf
        <input type="hidden" name="makanan_id" value="{{ $makanan->id }}">
        <label for="quantity">Qty:</label>
        <input type="number" name="quantity" value="1" min="1" style="width: 60px;">
        <button type="submit">Add to Cart</button>
    </form>
</div>
@endforeach
