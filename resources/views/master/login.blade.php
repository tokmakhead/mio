<!DOCTYPE html>
<html lang="tr" class="h-full bg-gray-900">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Master Panel Giriş | MIONEX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body
    class="h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div
                class="mx-auto h-16 w-16 bg-gradient-to-tr from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/30">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                    </path>
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-white tracking-tight">
                Master Panel
            </h2>
            <p class="mt-2 text-center text-sm text-gray-400">
                MIONEX Merkezi Yönetim Sistemi
            </p>
        </div>

        <div
            class="bg-gray-800/50 backdrop-blur-sm py-8 px-4 shadow-2xl sm:rounded-lg sm:px-10 border border-gray-700/50">
            <form class="space-y-6" action="{{ route('master.login.action') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300">
                        E-posta Adresi
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="appearance-none block w-full px-3 py-3 border border-gray-600 bg-gray-900/50 rounded-lg shadow-sm placeholder-gray-500 text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent sm:text-sm transition duration-200"
                            placeholder="admin@mionex.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300">
                        Parola
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="appearance-none block w-full px-3 py-3 border border-gray-600 bg-gray-900/50 rounded-lg shadow-sm placeholder-gray-500 text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent sm:text-sm transition duration-200"
                            placeholder="••••••••">
                    </div>
                </div>

                @if($errors->any())
                    <div class="rounded-md bg-red-900/30 p-4 border border-red-800/50">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-300">Giriş başarısız</h3>
                                <div class="mt-2 text-sm text-red-400">
                                    <ul role="list" class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-purple-500 transition duration-200 transform hover:scale-[1.01]">
                        Giriş Yap
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-gray-800 text-gray-500">
                            Güvenli Yönetim Paneli
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center text-xs text-gray-600">
            &copy; {{ date('Y') }} MIONEX. Tüm hakları saklıdır.
        </div>
    </div>
</body>

</html>