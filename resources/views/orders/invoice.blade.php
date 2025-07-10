<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
        }
        .company-info h1 {
            margin: 0;
            color: #1f2937;
            font-size: 28px;
            font-weight: 700;
        }
        .invoice-info {
            text-align: right;
        }
        .invoice-info h2 {
            margin: 0;
            color: #374151;
            font-size: 24px;
            font-weight: 600;
        }
        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        .customer-info, .order-info {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
        }
        .customer-info h3, .order-info h3 {
            margin: 0 0 15px 0;
            color: #374151;
            font-weight: 600;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background: #374151;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        .items-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        .items-table tr:nth-child(even) {
            background: #f9fafb;
        }
        .total-section {
            text-align: right;
            margin-top: 30px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .total-row.final {
            font-weight: 700;
            font-size: 18px;
            border-bottom: 2px solid #374151;
            color: #374151;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        @media print {
            body { margin: 0; padding: 0; }
            .invoice-container { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="company-info">
                <h1>Kaosjelek</h1>
                <p>Fashion Store</p>
            </div>
            <div class="invoice-info">
                <h2>INVOICE</h2>
                <p><strong>#{{ $order->order_number }}</strong></p>
            </div>
        </div>

        <div class="invoice-details">
            <div class="customer-info">
                <h3>📋 Informasi Pelanggan</h3>
                <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
                <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                <p><strong>Telepon:</strong> {{ $order->customer_phone }}</p>
            </div>
            <div class="order-info">
                <h3>📦 Informasi Pesanan</h3>
                <p><strong>Tanggal:</strong> {{ $order->created_at->format('d F Y') }}</p>
                <p><strong>Status:</strong> 
                    <span style="background: #10b981; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
                <p><strong>Total:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Size</th>
                    <th>Warna</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->size ?? '-' }}</td>
                    <td>{{ $item->color ?? '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <div style="width: 300px; margin-left: auto;">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($order->shipping_cost > 0)
                <div class="total-row">
                    <span>Ongkir:</span>
                    <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($order->tax_amount > 0)
                <div class="total-row">
                    <span>Pajak:</span>
                    <span>Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="total-row final">
                    <span>TOTAL:</span>
                    <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        @if($order->notes)
        <div style="margin-top: 30px; padding: 20px; background: #fef3c7; border-radius: 8px;">
            <h4 style="margin: 0 0 10px 0; color: #92400e;">📝 Catatan:</h4>
            <p style="margin: 0; color: #92400e;">{{ $order->notes }}</p>
        </div>
        @endif

        <div class="footer">
            <p>Terima kasih atas pembelian Anda!</p>
            <p>Invoice ini dibuat secara otomatis pada {{ now()->format('d F Y H:i') }}</p>
        </div>
    </div>

    <script>
        // Auto print ketika halaman dimuat
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>