<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>
     <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    {{-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.14/dist/tailwind.min.css" rel="stylesheet"> --}}
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-2xl shadow-xl p-8 text-center max-w-md w-11/12">
        <!-- Icon success -->
        <div class="flex justify-center mb-4">
            <div class="bg-green-100 rounded-full p-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <!-- Text -->
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Pembayaran Berhasil!</h1>
        <p class="text-gray-600 mb-6">
            Terima kasih, pembayaran Anda telah dikonfirmasi.<br>
            Silakan kembali ke aplikasi mobile untuk melanjutkan proses.
        </p>

        <!-- Button -->
        <a href="myapp://nipl-success"
           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-lg transition duration-300">
            Kembali ke Aplikasi
        </a>
    </div>
</body>
</html>
