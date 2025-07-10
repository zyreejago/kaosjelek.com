<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - KaosJelek</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md text-center max-w-md">
            <div class="text-6xl mb-4">✅</div>
            <h1 class="text-2xl font-bold text-green-600 mb-4">Pembayaran Berhasil!</h1>
            <p class="text-gray-600 mb-4">Terima kasih atas pembelian Anda.</p>
            <p class="text-sm text-gray-500 mb-2">Order ID: <span class="font-mono">{{ $orderId }}</span></p>
            <p class="text-xs text-gray-400 mb-6">Stok produk telah diupdate otomatis</p>
            <a href="/" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>