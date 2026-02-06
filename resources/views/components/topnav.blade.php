<div>
    <nav
        class="sticky top-0 z-50 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <div
                            class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary-600 to-primary-800 flex items-center justify-center">
                            <span class="text-white font-bold text-lg">M</span>
                        </div>
                        <span
                            class="text-xl font-bold bg-gradient-to-r from-primary-700 to-primary-900 dark:from-accent-300 dark:to-accent-500 bg-clip-text text-transparent">MIOLY</span>
                    </a>
                </div>

                <!-- Main Navigation - Centered -->
                <div class="hidden md:flex items-center space-x-1">
                    <x-nav-link href="{{ route('dashboard') }}"
                        :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                    <x-nav-link href="{{ route('customers.index') }}"
                        :active="request()->routeIs('customers.*')">Müşteriler</x-nav-link>
                    <x-nav-link href="{{ route('providers.index') }}"
                        :active="request()->routeIs('providers.*')">Sağlayıcılar</x-nav-link>
                    <x-nav-link href="{{ route('services.index') }}"
                        :active="request()->routeIs('services.*')">Hizmetler</x-nav-link>
                    <x-nav-link href="{{ route('invoices.index') }}"
                        :active="request()->routeIs('invoices.*')">Muhasebe</x-nav-link>
                    <x-nav-link href="{{ route('quotes.index') }}"
                        :active="request()->routeIs('quotes.*')">Teklifler</x-nav-link>
                    <x-nav-link href="#" :active="false">Raporlar</x-nav-link>
                    <x-nav-link href="#" :active="false">Ayarlar</x-nav-link>
                </div>

                <!-- Right Side: Theme Toggle + User Dropdown -->
                <div class="flex items-center space-x-4">
                    <!-- Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode"
                        class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                        <svg x-show="!darkMode" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                        <svg x-show="darkMode" class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </button>

                    <!-- User Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-600 to-primary-800 flex items-center justify-center text-white font-semibold text-sm">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span
                                class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Log
                                    Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>