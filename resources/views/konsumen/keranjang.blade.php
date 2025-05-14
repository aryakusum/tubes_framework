<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keranjang Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2>Keranjang Belanja</h2>

    @if($cart && count($cart) > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Kuantitas</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $id => $item)
                        <tr>
                            <td>{{ $item['name'] ?? 'Nama tidak tersedia' }}</td>
                            <td>Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</td>
                            <td>{{ $item['quantity'] ?? 0 }}</td>
                            <td>Rp {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('cart.increase', $id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">+</button>
                                </form>
                                <form action="{{ route('cart.decrease', $id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning">-</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <a href="{{ route('checkout') }}" class="btn btn-primary">Checkout</a>
    @else
        <p>Keranjang Anda kosong.</p>
    @endif
</div>
</body>
</html>
