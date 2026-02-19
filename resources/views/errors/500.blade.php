<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Sistem Hatası | MIONEX</title>
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
                class="w-20 h-20 brand-gradient rounded-3xl flex items-center justify-center shadow-2xl shadow-rose-500/20">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>
        </div>

        <h1 class="text-9xl font-bold text-gray-200 dark:text-gray-800 mb-4 select-none">500</h1>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Beklenmedik bir sorun oluştu.</h2>
        <p class="text-gray-500 dark:text-gray-400 mb-10 text-sm">
            Şu anda sistemsel bir aksaklık yaşıyoruz. Ekibimiz konuyla ilgileniyor. Lütfen kısa bir süre sonra tekrar
            deneyin.
        </p>

        @if(isset($exception))
            <div
                class="mb-10 p-4 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 rounded-xl text-left overflow-x-auto">
                <p class="text-xs font-bold text-red-600 dark:text-red-400 uppercase mb-2">Hata Detayı:</p>
                <p class="text-sm font-mono text-gray-800 dark:text-gray-200 break-words mb-4">
                    {{ $exception->getMessage() }}
                </p>
                <p class="text-[10px] font-mono text-gray-400 dark:text-gray-500 max-h-40 overflow-y-auto">
                    {{ $exception->getFile() }}:{{ $exception->getLine() }}<br>
                    {{ Str::limit($exception->getTraceAsString(), 500) }}
                </p>
            </div>
        @endif

        <a href="/"
            class="inline-flex items-center justify-center px-8 py-3 brand-gradient text-white font-semibold rounded-xl transition-all hover:scale-105 active:scale-95 shadow-lg shadow-rose-500/25">
            Tekrar Dene
        </a>

        <div class="mt-16 text-xs text-gray-400 font-medium tracking-widest uppercase">
            MIONEX &bull; 2026
        </div>
    </div>
</body>

</html>