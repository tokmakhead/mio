<div class="mb-8">
    <div class="sm:hidden">
        <label for="tabs" class="sr-only">Ayarlar menüsü</label>
        <select id="tabs" name="tabs" class="block w-full rounded-md border-gray-300 focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300" onchange="window.location.href=this.value">
            @foreach([
                ['route' => 'settings.brand', 'label' => 'Marka & Görünüm', 'icon' => 'sparkles'],
                ['route' => 'settings.languages.index', 'label' => 'Diller & Çeviri', 'icon' => 'translate'],
                ['route' => 'settings.gateways', 'label' => 'Ödeme Altyapıları', 'icon' => 'credit-card'],
                ['route' => 'settings.site', 'label' => 'Site Ayarları', 'icon' => 'globe-alt'],
                ['route' => 'settings.financial', 'label' => 'Finansal', 'icon' => 'banknotes'],
                ['route' => 'settings.smtp', 'label' => 'SMTP', 'icon' => 'server'],
                ['route' => 'settings.templates', 'label' => 'Şablonlar', 'icon' => 'document-duplicate'],
                ['route' => 'settings.system', 'label' => 'Sistem', 'icon' => 'cog'],
            ] as $tab)
                <option value="{{ route($tab['route']) }}" {{ request()->routeIs($tab['route']) ? 'selected' : '' }}>{{ $tab['label'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="hidden sm:block">
        <nav class="flex justify-center space-x-4 p-2 bg-white dark:bg-gray-800/50 backdrop-blur-md rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/50" aria-label="Tabs">
            @foreach([
                ['route' => 'settings.brand', 'label' => 'Marka & Görünüm', 'icon' => 'sparkles'],
                ['route' => 'settings.languages.index', 'label' => 'Diller & Çeviri', 'icon' => 'translate'],
                ['route' => 'settings.gateways', 'label' => 'Ödeme Altyapıları', 'icon' => 'credit-card'],
                ['route' => 'settings.site', 'label' => 'Site Ayarları', 'icon' => 'globe-alt'],
                ['route' => 'settings.financial', 'label' => 'Finansal Ayarlar', 'icon' => 'banknotes'],
                ['route' => 'settings.smtp', 'label' => 'SMTP Yapılandırma', 'icon' => 'server'],
                ['route' => 'settings.templates', 'label' => 'E-posta Şablonları', 'icon' => 'document-duplicate'],
                ['route' => 'settings.system', 'label' => 'Sistem & Bakım', 'icon' => 'cog'],
            ] as $tab)
                @php
                    // Check if current route matches tab route or any sub-routes (e.g. settings.languages.create)
                    $isActive = request()->routeIs($tab['route']) || (Str::startsWith($tab['route'], 'settings.languages') && request()->routeIs('settings.languages.*'));
                @endphp
                <a href="{{ route($tab['route']) }}"
                   class="{{ $isActive 
                        ? 'bg-primary-500 text-white shadow-md shadow-primary-500/30' 
                        : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700' }}
                       group flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 ease-in-out">
                    
                    @if($tab['icon'] == 'sparkles')
                        <svg class="{{ $isActive ? 'text-white' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300' }} -ml-0.5 mr-2.5 h-5 w-5 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    @elseif($tab['icon'] == 'translate')
                        <svg class="{{ $isActive ? 'text-white' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300' }} -ml-0.5 mr-2.5 h-5 w-5 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                        </svg>
                    @elseif($tab['icon'] == 'credit-card')
                        <svg class="{{ $isActive ? 'text-white' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300' }} -ml-0.5 mr-2.5 h-5 w-5 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    @elseif($tab['icon'] == 'globe-alt')
                        <svg class="{{ $isActive ? 'text-white' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300' }} -ml-0.5 mr-2.5 h-5 w-5 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                    @elseif($tab['icon'] == 'banknotes')
                        <svg class="{{ $isActive ? 'text-white' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300' }} -ml-0.5 mr-2.5 h-5 w-5 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @elseif($tab['icon'] == 'server')
                        <svg class="{{ $isActive ? 'text-white' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300' }} -ml-0.5 mr-2.5 h-5 w-5 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                        </svg>
                    @elseif($tab['icon'] == 'document-duplicate')
                        <svg class="{{ $isActive ? 'text-white' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300' }} -ml-0.5 mr-2.5 h-5 w-5 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                        </svg>
                    @elseif($tab['icon'] == 'cog')
                        <svg class="{{ $isActive ? 'text-white' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300' }} -ml-0.5 mr-2.5 h-5 w-5 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    @endif
                    
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </nav>
    </div>
</div>
