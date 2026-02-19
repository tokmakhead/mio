<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Sayfa Bulunamadı | MIONEX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .brand-gradient {
            background: linear-gradient(135deg, #de4968 0%, #be3a56 100%);
        }

        .text-brand {
            color: #de4968;
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full text-center">
        <!-- Brand Icon -->
        <div class="mb-8 flex justify-center">
            <div
                class="w-20 h-20 brand-gradient rounded-3xl flex items-center justify-center shadow-2xl shadow-rose-500/20 rotate-12">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-9xl font-bold text-gray-200 dark:text-gray-800 mb-4 select-none">404</h1>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Aradığınız sayfa kaybolmuş.</h2>
        <p class="text-gray-500 dark:text-gray-400 mb-10 text-sm">
            İstediğiniz sayfa taşınmış, silinmiş veya hiç var olmamış olabilir. Lütfen URL'yi kontrol edin.
        </p>

        <a href="/"
            class="inline-flex items-center justify-center px-8 py-3 brand-gradient text-white font-semibold rounded-xl transition-all hover:scale-105 active:scale-95 shadow-lg shadow-rose-500/25">
            Anasayfaya Dön
        </a>

        <div class="mt-16 text-xs text-gray-400 font-medium tracking-widest uppercase">
            MIONEX &bull; 2026
        </div>
    </div>
</body>

</html>