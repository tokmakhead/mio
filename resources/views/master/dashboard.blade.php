@extends('master.layout')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Master Dashboard</h1>
        <p class="text-gray-500 dark:text-gray-400">MIONEX dağıtım ve lisans yönetim paneli.</p>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Licenses -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Toplam Lisans</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalLicenses }}</p>
                </div>
                <div
                    class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.542 16.742a2 2 0 01-.442.558l-1.332 1.332a2 2 0 01-.987.496l-3.374.844a.75.75 0 01-.929-.929l.844-3.374a2 2 0 01.496-.987l1.332-1.332a2 2 0 01.558-.442L14.058 8.257A6 6 0 0120 14z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-green-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                <span>%100 Aktif</span>
            </div>
        </div>

        <!-- Active Licenses -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Aktif Kullanım</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $activeLicenses }}</p>
                </div>
                <div
                    class="w-12 h-12 bg-green-50 dark:bg-green-900/30 rounded-lg flex items-center justify-center text-green-600 dark:text-green-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Releases -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Yayınlanan Sürüm</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalReleases }}</p>
                </div>
                <div
                    class="w-12 h-12 bg-purple-50 dark:bg-purple-900/30 rounded-lg flex items-center justify-center text-purple-600 dark:text-purple-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Latest Version -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 bg-gradient-to-br from-primary-600 to-primary-800 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-indigo-100">Son Sürüm</p>
                    <p class="text-3xl font-bold mt-1">{{ $latestRelease ? $latestRelease->version : 'v1.0.0' }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-xs text-indigo-100">
                {{ $latestRelease ? $latestRelease->published_at : now()->format('d M Y') }}
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('master.licenses.create') }}"
            class="group block p-6 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl hover:border-blue-500 hover:bg-blue-50 dark:hover:border-blue-500 dark:hover:bg-blue-900/20 transition-all text-center">
            <div
                class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto text-blue-600 dark:text-blue-400 mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-blue-600">Yeni Lisans Oluştur</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Müşteri için yeni bir lisans anahtarı üret.</p>
        </a>

        <div class="block p-6 border border-gray-100 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 shadow-sm">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4">Sistem Sağlığı</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">API Durumu</span>
                    <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">Operational</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Lisans Sunucusu</span>
                    <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">Online</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Update CDN</span>
                    <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">Active</span>
                </div>
            </div>
        </div>
    </div>
@endsection