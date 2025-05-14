<!DOCTYPE html>
<html>

<head>
    <title>Checkout - FOODMART MR BANGKOK</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Detail Pembayaran</h2>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Ringkasan Pesanan</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($cart as $id => $item)
                        <tr>
                            <td>{{ $item['nama'] }}</td>
                            <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>Rp {{ number_format($item['harga'] * $item['quantity'], 0, ',', '.') }}</td>
                        </tr>
                        @php $total += $item['harga'] * $item['quantity']; @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Total</th>
                            <th>Rp {{ number_format($total, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="text-center">
            <button id="pay-button" class="btn btn-primary btn-lg">Bayar Sekarang</button>
            <a href="{{ route('cart.view') }}" class="btn btn-secondary btn-lg ms-2">Kembali ke Keranjang</a>
        </div>
    </div>

    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    window.location.href = '{{ route("payment.finish") }}?' + new URLSearchParams({
                        order_id: result.order_id,
                        transaction_id: result.transaction_id,
                        payment_type: result.payment_type,
                        status_code: result.status_code
                    }).toString();
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    window.location.href = '{{ route("payment.finish") }}?' + new URLSearchParams({
                        order_id: result.order_id,
                        transaction_id: result.transaction_id,
                        payment_type: result.payment_type,
                        status_code: result.status_code
                    }).toString();
                },
                onError: function(result) {
                    console.error('Payment error:', result);
                    window.location.href = '{{ route("payment.error") }}';
                },
                onClose: function() {
                    console.log('Customer closed the popup without finishing the payment');
                    window.location.href = '{{ route("payment.cancel") }}';
                }
            });
        };
    </script>
</body>

</html>