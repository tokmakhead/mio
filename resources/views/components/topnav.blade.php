<div>
    <nav
        class="sticky top-0 z-50 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 transition-colors duration-200">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="flex items-center">
                            @if(isset($brandSettings['logo_path']))
                                <img src="{{ $brandSettings['logo_path'] }}" alt="Logo" class="h-8 w-auto">
                            @else
                                <img src="{{ asset('assets/img/nexblack.png') }}" alt="Logo" class="h-8 w-auto dark:hidden">
                                <img src="{{ asset('assets/img/nexwhite.png') }}" alt="Logo"
                                    class="h-8 w-auto hidden dark:block">
                            @endif
                        </div>
                        <span
                            class="text-xl font-black tracking-tighter text-gray-900 dark:text-white leading-none uppercase">
                            {{ $brandSettings['site_title'] ?? ($siteSettings->site_name ?? 'MIONEX') }}
                        </span>
                    </a>
                </div>

                <!-- Main Navigation - Centered -->
                <div class="hidden md:flex items-center space-x-1">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">Gösterge
                        Paneli</x-nav-link>
                    <x-nav-link href="{{ route('customers.index') }}" :active="request()->routeIs('customers.*')">Cari
                        Yönetimi</x-nav-link>
                    <x-nav-link href="{{ route('providers.index') }}"
                        :active="request()->routeIs('providers.*')">Tedarikçiler</x-nav-link>
                    <x-nav-link href="{{ route('services.index') }}" :active="request()->routeIs('services.*')">Hizmet
                        Yönetimi</x-nav-link>
                    <x-nav-link href="{{ route('invoices.index') }}" :active="request()->routeIs('invoices.*')">Finans
                        Merkezi</x-nav-link>
                    <x-nav-link href="{{ route('quotes.index') }}" :active="request()->routeIs('quotes.*')">Teklif
                        Havuzu</x-nav-link>
                    <x-nav-link href="{{ route('reports.index') }}" :active="request()->routeIs('reports.*')">Analiz
                        Merkezi</x-nav-link>

                    <x-nav-link href="{{ route('settings.brand') }}" :active="request()->routeIs('settings.*')">Sistem
                        Ayarları</x-nav-link>
                </div>

                <!-- Right Side: Theme Toggle + User Dropdown -->
                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <div x-data="{ langOpen: false }" class="relative" x-cloak>
                        <button @click.stop="langOpen = !langOpen"
                            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center gap-1">
                            @if(app()->getLocale() == 'tr')
                                <svg class="w-6 h-6 rounded-sm shadow-sm" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 1200 800">
                                    <rect width="1200" height="800" fill="#E30A17" />
                                    <circle cx="425" cy="400" r="200" fill="#fff" />
                                    <circle cx="475" cy="400" r="160" fill="#E30A17" />
                                    <path fill="#fff"
                                        d="M583.334 400l18.066 55.604h58.466l-47.297 34.364 18.068 55.604-47.301-34.366-47.301 34.366 18.068-55.604-47.297-34.364h58.466z" />
                                </svg>
                            @else
                                <svg class="w-6 h-6 rounded-sm shadow-sm" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 60 30">
                                    <clipPath id="s">
                                        <path d="M0,0 v30 h60 v-30 z" />
                                    </clipPath>
                                    <clipPath id="t">
                                        <path d="M30,15 h30 v15 z v15 h-30 z h-30 v-15 z v-15 h30 z" />
                                    </clipPath>
                                    <g clip-path="url(#s)">
                                        <path d="M0,0 v30 h60 v-30 z" fill="#012169" />
                                        <path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6" />
                                        <path d="M0,0 L60,30 M60,0 L0,30" clip-path="url(#t)" stroke="#C8102E"
                                            stroke-width="4" />
                                        <path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10" />
                                        <path d="M30,0 v30 M0,15 h60" stroke="#C8102E" stroke-width="6" />
                                    </g>
                                </svg>
                            @endif
                            <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="langOpen" @click.away="langOpen = false" x-transition
                            class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                            <a href="{{ route('lang.switch', 'tr') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ app()->getLocale() == 'tr' ? 'bg-gray-50 dark:bg-gray-700' : '' }}">
                                <svg class="w-5 h-5 rounded-sm shadow-sm shrink-0" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 1200 800">
                                    <rect width="1200" height="800" fill="#E30A17" />
                                    <circle cx="425" cy="400" r="200" fill="#fff" />
                                    <circle cx="475" cy="400" r="160" fill="#E30A17" />
                                    <path fill="#fff"
                                        d="M583.334 400l18.066 55.604h58.466l-47.297 34.364 18.068 55.604-47.301-34.366-47.301 34.366 18.068-55.604-47.297-34.364h58.466z" />
                                </svg>
                                Türkçe
                            </a>
                            <a href="{{ route('lang.switch', 'en') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 {{ app()->getLocale() == 'en' ? 'bg-gray-50 dark:bg-gray-700' : '' }}">
                                <svg class="w-5 h-5 rounded-sm shadow-sm shrink-0" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 60 30">
                                    <clipPath id="s">
                                        <path d="M0,0 v30 h60 v-30 z" />
                                    </clipPath>
                                    <clipPath id="t">
                                        <path d="M30,15 h30 v15 z v15 h-30 z h-30 v-15 z v-15 h30 z" />
                                    </clipPath>
                                    <g clip-path="url(#s)">
                                        <path d="M0,0 v30 h60 v-30 z" fill="#012169" />
                                        <path d="M0,0 L60,30 M60,0 L0,30" stroke="#fff" stroke-width="6" />
                                        <path d="M0,0 L60,30 M60,0 L0,30" clip-path="url(#t)" stroke="#C8102E"
                                            stroke-width="4" />
                                        <path d="M30,0 v30 M0,15 h60" stroke="#fff" stroke-width="10" />
                                        <path d="M30,0 v30 M0,15 h60" stroke="#C8102E" stroke-width="6" />
                                    </g>
                                </svg>
                                English
                            </a>
                            <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                            <a href="{{ route('settings.languages.index') }}"
                                class="block px-4 py-2 text-xs text-primary-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                {{ __('Dilleri Yönet') }}
                            </a>
                        </div>
                    </div>

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
                    <div x-data="{ userOpen: false }" class="relative" x-cloak>
                        <button @click.stop="userOpen = !userOpen"
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
                        <div x-show="userOpen" @click.away="userOpen = false" x-transition
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