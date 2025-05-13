<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
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

<div class="container py-4">
    <h2 class="mb-4">Keranjang Belanja</h2>

    @php
        $cart = session('cart', []);
    @endphp

    @if(count($cart) > 0)
        <div class="row">
            @php $total = 0; @endphp
            @foreach($cart as $id => $item)
                @php
                    $subtotal = $item['harga'] * $item['quantity'];
                    $total += $subtotal;
                @endphp
                <div class="col-md-12 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">{{ $item['nama'] }}</h5>
                                <p class="card-text mb-1">Rp {{ number_format($item['harga'], 0, ',', '.') }}</p>
                                <p class="card-text text-muted">Subtotal: Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                            </div>

                            <div class="d-flex align-items-center">
                                <form action="{{ route('cart.decrease', $id) }}" method="POST" class="me-2">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-secondary btn-sm">−</button>
                                </form>

                                <span class="badge bg-primary me-2">{{ $item['quantity'] }}</span>

                                <form action="{{ route('cart.increase', $id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-secondary btn-sm">+</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="col-md-12 mt-3">
                <div class="text-end fw-bold fs-5">
                    Total: Rp {{ number_format($total, 0, ',', '.') }}
                </div>
                <a href="{{ route('checkout') }}" class="btn btn-success mt-3">Checkout</a>
            </div>
        </div>
    @else
        <div class="alert alert-info mt-4">Keranjang Anda kosong.</div>
    @endif

    <a href="{{ route('konsumen.dashboard') }}" class="btn btn-secondary mt-4">← Kembali Belanja</a>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
