<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $siteSettings->site_name ?? config('app.name', 'MIONEX') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Dynamic Favicon -->
    @if(isset($brandSettings['favicon_path']))
        <link rel="icon" href="{{ $brandSettings['favicon_path'] }}" type="image/x-icon" />
    @endif

    <!-- Dynamic Colors -->
    @php
        $hex = $brandSettings['primary_color'] ?? '#de4968'; // MIONEX Brand Red Fallback
        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
        $rgb = "$r $g $b";
    @endphp
    <style>
        :root {
            --color-primary-500:
                {{ $rgb }}
            ;
            --color-primary-600:
                {{ $rgb }}
            ;
            --color-primary-700:
                {{ $rgb }}
            ;
            --color-primary-50:
                {{ $r }}
                {{ $g }}
                {{ $b }}
                / 0.1;
            --color-primary-900:
                {{ $r }}
                {{ $g }}
                {{ $b }}
            ;
        }

        /* Force Override for Auth Pages */
        .bg-primary-600,
        .bg-primary-700 {
            background-color: rgb({{ $rgb }}) !important;
        }

        .text-primary-600,
        .text-primary-500 {
            color: rgb({{ $rgb }}) !important;
        }

        .focus\:ring-primary-500 {
            --tw-ring-color: rgb({{ $rgb }}) !important;
        }

        .focus\:border-primary-500 {
            border-color: rgb({{ $rgb }}) !important;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased text-gray-900">
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
        <!-- Left Side: Brand & Visual -->
        <div class="relative hidden lg:flex flex-col justify-between bg-zinc-900 text-white p-12 overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute inset-0 z-0">
                <img src="{{ $brandSettings['login_image_path'] ?? 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=2564&auto=format&fit=crop' }}"
                    alt="Background" class="w-full h-full object-cover opacity-40 mix-blend-overlay">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-900/90 to-zinc-900/90 mix-blend-multiply">
                </div>
            </div>

            <!-- Content -->
            <div class="relative z-10">
                <div class="flex items-center gap-3 group">
                    @if(isset($brandSettings['logo_path']))
                        <img src="{{ $brandSettings['logo_path'] }}" alt="Logo" class="h-10 w-auto brightness-0 invert">
                    @else
                        <img src="{{ asset('assets/img/nexwhite.png') }}" alt="MIONEX Logo" class="h-10 w-auto">
                    @endif
                    <div class="relative pr-10">
                        <span class="text-2xl font-black tracking-tighter text-white leading-none uppercase">
                            {{ $brandSettings['site_title'] ?? ($siteSettings->site_name ?? 'MIONEX') }}
                        </span>
                        @if(!isset($brandSettings['site_title']))
                            <span
                                class="absolute -top-2 -right-0 bg-[#C72D52] text-[8px] font-black text-white px-1.5 py-0.5 rounded-md leading-none">BETA</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="relative z-10 max-w-md">
                <blockquote class="space-y-6">
                    <p class="text-lg font-medium leading-relaxed text-white/90">
                        "İş süreçlerinizi modernize eden, finansal akışınızı hızlandıran ve müşteri ilişkilerinizi
                        güçlendiren yeni nesil yönetim platformu."
                    </p>
                    <footer class="flex items-center gap-4">
                        <div class="flex flex-col">
                            <cite class="text-sm font-semibold not-italic text-white">MIONEX Ekibi</cite>
                            <span class="text-xs text-zinc-400">Ürün Geliştirme</span>
                        </div>
                    </footer>
                </blockquote>
            </div>

            <div class="relative z-10 text-xs text-zinc-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Tüm hakları saklıdır.
            </div>
        </div>

        <!-- Right Side: Form -->
        <div
            class="flex flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-white dark:bg-gray-900">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-10 text-center flex flex-col items-center justify-center">
                    @if(isset($brandSettings['logo_path']))
                        <img src="{{ $brandSettings['logo_path'] }}" alt="Logo"
                            class="h-12 w-auto dark:brightness-0 dark:invert mb-4">
                    @else
                        <img src="{{ asset('assets/img/nexblack.png') }}" alt="Logo" class="h-12 w-auto dark:hidden mb-4">
                        <img src="{{ asset('assets/img/nexwhite.png') }}" alt="Logo"
                            class="h-12 w-auto hidden dark:block mb-4">
                    @endif
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter">
                        {{ config('app.name') }}
                    </h1>
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>