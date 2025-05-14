<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

        .table th {
            background-color: #f8f9fa;
        }

        .quantity-badge {
            font-size: 1.1em;
            padding: 0.3em 0.6em;
        }

        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('konsumen.dashboard') }}">
                <span>FOODMART</span> <span>MR BANGKOK</span>
            </a>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Keranjang Belanja</h2>
                    <a href="{{ route('konsumen.dashboard') }}" class="btn btn-outline-secondary">
                        ← Kembali Belanja
                    </a>
                </div>

                @php
                $cart = session('cart', []);
                $total = 0;
                @endphp

                @if(count($cart) > 0)
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Produk</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-center">Kuantitas</th>
                                        <th class="text-end">Subtotal</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $id => $item)
                                    @php
                                    $subtotal = $item['harga'] * $item['quantity'];
                                    $total += $subtotal;
                                    @endphp
                                    <tr>
                                        <td>{{ $item['nama'] }}</td>
                                        <td class="text-end">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                <form action="{{ route('cart.decrease', $id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-secondary btn-action">−</button>
                                                </form>

                                                <span class="badge bg-primary quantity-badge">{{ $item['quantity'] }}</span>

                                                <form action="{{ route('cart.increase', $id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-secondary btn-action">+</button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('cart.decrease', $id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="remove" value="1">
                                                <button type="submit" class="btn btn-danger btn-action">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Total:</td>
                                        <td class="text-end fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('checkout') }}" class="btn btn-primary">
                                Lanjut ke Pembayaran
                            </a>
                        </div>
                    </div>
                </div>
                @else
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <h4 class="text-muted mb-3">Keranjang Anda kosong</h4>
                        <p class="text-muted">Silakan tambahkan beberapa produk ke keranjang Anda.</p>
                        <a href="{{ route('konsumen.dashboard') }}" class="btn btn-primary">
                            Mulai Belanja
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>