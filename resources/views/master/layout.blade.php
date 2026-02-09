<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MIONEX Master - Master Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 dark:bg-gray-900 font-sans antialiased">

    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex-shrink-0 hidden md:flex flex-col">
            <div class="h-16 flex items-center px-6 bg-gray-950 gap-3 group">
                <img src="{{ asset('assets/img/nexwhite.png') }}" alt="MIONEX Logo" class="h-8 w-auto">
                <span class="text-xl font-black tracking-tighter text-white leading-none uppercase">MIONEX <span
                        class="text-blue-500 font-bold ml-1">MASTER</span></span>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('master.dashboard') }}"
                    class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('master.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('master.licenses.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('master.licenses.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.542 16.742a2 2 0 01-.442.558l-1.332 1.332a2 2 0 01-.987.496l-3.374.844a.75.75 0 01-.929-.929l.844-3.374a2 2 0 01.496-.987l1.332-1.332a2 2 0 01.558-.442L14.058 8.257A6 6 0 0120 14z">
                        </path>
                    </svg>
                    Lisans Yönetimi
                </a>

                <a href="{{ route('master.releases.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('master.releases.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                        </path>
                    </svg>
                    Sürüm & Depo
                </a>

                <a href="{{ route('master.announcements.index') }}"
                    class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('master.announcements.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                        </path>
                    </svg>
                    Duyurular
                </a>
            </nav>

            <div class="p-4 bg-gray-950 border-t border-gray-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                            MA</div>
                        <div>
                            <div class="text-sm font-medium text-white">Master Admin</div>
                            <div class="text-xs text-gray-500">master@mioly.com</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('master.logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-gray-400 hover:text-white p-2 rounded-lg hover:bg-gray-800 transition-colors"
                            title="Çıkış Yap">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top Header (Mobile Only & User Menu) -->
            <header
                class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 h-16 flex items-center justify-between px-6 md:hidden">
                <span class="font-bold text-gray-900 dark:text-white">MIONEX Master</span>
                <button class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </header>

            <div class="flex-1 overflow-auto p-6 md:p-8">
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative"
                        role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal"
        class="fixed inset-0 z-50 flex items-center justify-center invisible opacity-0 pointer-events-none transition-all duration-200">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl transform scale-95 transition-transform w-full max-w-md mx-4 overflow-hidden">
            <div class="p-6">
                <div
                    class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 dark:bg-red-900/30 rounded-full mb-4">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-center text-gray-900 dark:text-white mb-2" id="modalTitle">Onay
                    Gerekiyor</h3>
                <p class="text-center text-gray-500 dark:text-gray-400 mb-6" id="modalMessage">Bu işlemi yapmak
                    istediğinize emin misiniz?</p>

                <div class="flex space-x-3">
                    <button onclick="closeModal()"
                        class="flex-1 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium">
                        Vazgeç
                    </button>
                    <form id="modalForm" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="_method" id="modalMethod" value="POST">
                        <button type="submit"
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium shadow-lg shadow-red-500/30">
                            Onayla ve Sil
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(actionUrl, title, message, method = 'POST', buttonText = 'Onayla') {
            const modal = document.getElementById('confirmationModal');
            const form = document.getElementById('modalForm');
            const methodInput = document.getElementById('modalMethod');

            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalMessage').innerText = message;
            form.action = actionUrl;
            methodInput.value = method;

            // Handle DELETE method Laravel spoofing
            if (method === 'DELETE') {
                methodInput.value = 'DELETE';
            } else {
                methodInput.value = method; // Usually POST for cancels
            }

            modal.classList.remove('invisible', 'opacity-0', 'pointer-events-none');
            modal.querySelector('div.transform').classList.remove('scale-95');
            modal.querySelector('div.transform').classList.add('scale-100');
        }

        function closeModal() {
            const modal = document.getElementById('confirmationModal');
            modal.classList.add('invisible', 'opacity-0', 'pointer-events-none');
            modal.querySelector('div.transform').classList.add('scale-95');
            modal.querySelector('div.transform').classList.remove('scale-100');
        }
    </script>
</body>

</html>